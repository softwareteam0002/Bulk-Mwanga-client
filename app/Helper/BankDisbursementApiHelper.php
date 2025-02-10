<?php


namespace App\Helper;


use App\Http\Controllers\Api\BankPaymentController;
use App\Http\Controllers\Api\OrganizationController;
use App\Jobs\BankBalanceCheckForDisbursement;
use App\Jobs\BankProcessBatch;
use App\Jobs\ScheduledDisbursement;
use App\Models\BankBatchPayment;
use App\Models\BankBatchProcessing;
use App\Models\BankDisbursementVerification;
use App\Models\BankPaymentDisbursement;
use App\Models\BankVerificationBatch;
use App\Models\Batch;
use App\Models\BatchProcessing;
use App\Models\DisbursementOpeningBalance;
use App\Models\Organization;
use App\Models\OrganizationAccountBalance;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;

class BankDisbursementApiHelper
{
    const QUEUED = 0x1;
    const ERROR_ALREADY_IN_PROGRESS = 0x2;
    const ERROR_NEEDS_VERIFICATION_FIRST = 0x3;
    const ERROR_COULD_NOT_QUEUE = 0x99;
    const ERROR_HAS_ITEMS_ON_QUEUE = 0x999;
    const ERROR_FAILED_TO_CHECK_BALANCE = 0x9999;
    const SCHEDULED = 0x99999;

    /**
     * Return the status of the given batch number, including the number of the entries
     * that have been processed so far if in its recent operation
     * @param $batch_no
     * @param $extendedv g g
     * @param $operation
     * @return array
     */
    public static function getStatus($batch_no, $extended = true, $operation = null)
    {
        if (empty($operation)) {
            return self::getDisbursementStatus($batch_no, false, $extended) ?? self::getVerificationStatus($batch_no, false, $extended);
        } else {
            return $operation == 'disburse' || $operation == 'payment' || $operation == 'disbursement' ?
                self::getDisbursementStatus($batch_no, false, $extended) : self::getVerificationStatus($batch_no, false, $extended);
        }
    }


    /**
     * Get batch verication status
     * @param $batch_no
     * @param bool $percentage_only if true, return only percentage of the processed
     * @param bool $extended_status if true, return extended status that include amounts in different status
     * @return array|false|float|int|null
     */
    public static function getVerificationStatus($batch_no, $percentage_only = false, $extended_status = false)
    {
        $batch = BankVerificationBatch::query()->where(['batch_no' => $batch_no])->first();
        if (empty($batch)) {
            return null;
        }
        $extended = [];
        $processed_q = BankDisbursementVerification::query()
            ->where(['batch_no' => $batch->batch_no])
            ->whereIn('payment_status', [BankDisbursementVerification::STATUS_PAID, BankDisbursementVerification::STATUS_ERROR]);
        $processed = $processed_q->count();
        $pending_q = BankDisbursementVerification::query()
            ->where(['batch_no' => $batch->batch_no])
            ->where('payment_status', '=', BankDisbursementVerification::STATUS_NOT_PAID);
        $pending = $pending_q->count();
        $failed_q = BankDisbursementVerification::query()
            ->where(['batch_no' => $batch->batch_no])
            ->where(['payment_status' => BankDisbursementVerification::STATUS_ERROR]);
        $failed = $failed_q->count();

        $successful_q = BankDisbursementVerification::query()
            ->where(['batch_no' => $batch->batch_no])
            ->where(['payment_status' => BankDisbursementVerification::STATUS_PAID]);
        $successful = $successful_q->count();

        if ($extended_status) {
            $sum = function ($item) {
                return $item->amount + $item->withdrawal_fee;
            };
            $extended['processed'] = $processed_q->get()->sum($sum);
            $extended['pending'] = $pending_q->get()->sum($sum);
            $extended['failed'] = $failed_q->get()->sum($sum);
            $extended['successful'] = $successful_q->get()->sum($sum);
            $extended['total'] = $extended['processed'] + $extended['pending'];
        }

        $total = $pending + $processed;
        $percentage = $total <= 0 ? 0 : ceil($processed / floatval($total) * 100);
        if ($percentage_only) {
            return $percentage;
        }

        return ['operation' => 'verification',
            'status' => $batch->batch_status_id,
            'status_description' => $batch->status_description,
            'total' => $processed + $pending,
            'pending' => $pending,
            'processed' => $processed,
            'successful' => $successful,
            'failed' => $failed,
            'percentage' => $percentage,
            'scheduled_at' => null,//always null for verification status
            'extended' => $extended];
    }

    /**
     * Get batch disbursement status
     * @param $batch_no
     * @param bool $percentage_only if true, return only percentage of the processed
     * @param bool $extended_status if true, return extended status that include amounts in different status
     * @return array|false|float|int|null
     */
    public static function getDisbursementStatus($batch_no, $percentage_only = false, $extended_status = false)
    {
        $batch_payment = BankBatchPayment::query()->where(['batch_no' => $batch_no])->first();
        if (empty($batch_payment)) {
            return null;
        }
        $extended = [];
        $processed_q = BankPaymentDisbursement::query()
            ->where(['batch_no' => $batch_payment->batch_no])
            ->whereIn('payment_status', [BankPaymentDisbursement::STATUS_PAID, BankPaymentDisbursement::STATUS_SENT, BankPaymentDisbursement::STATUS_ERROR]);
        $processed = $processed_q->count();
        $pending_q = BankPaymentDisbursement::query()
            ->where(['batch_no' => $batch_payment->batch_no])
            ->where('payment_status', '=', BankPaymentDisbursement::STATUS_NOT_PAID);
        $pending = $pending_q->count();
        $failed_q = BankPaymentDisbursement::query()
            ->where(['batch_no' => $batch_payment->batch_no])
            ->where(['payment_status' => BankPaymentDisbursement::STATUS_ERROR]);
        $failed = $failed_q->count();

        $successful_q = BankPaymentDisbursement::query()
            ->where(['batch_no' => $batch_payment->batch_no])
            ->where(['payment_status' => BankPaymentDisbursement::STATUS_PAID]);

        $successful = $successful_q->count();

        if ($extended_status) {
            $sum = function ($item) {
                return $item->amount + $item->withdrawal_fee;
            };
            //opening balance
            //add  transaction type to filter out the data
            $dob = DisbursementOpeningBalance::query()
                ->where(['batch_id' => $batch_payment->id, 'sufficient' => 'YES'])
                ->where('transaction_type', 'bank')
                ->first();
            if (!empty($dob)) {
                $oab = OrganizationAccountBalance::query()->where(['id' => $dob->balance_id])->first();
                $extended['opening_balance'] = $oab->available_balance;
            }
            $extended['processed'] = $processed_q->get()->sum($sum);
            $extended['pending'] = $pending_q->get()->sum($sum);
            $extended['failed'] = $failed_q->get()->sum($sum);
            $extended['successful'] = $successful_q->get()->sum($sum);
            $extended['total'] = $extended['processed'] + $extended['pending'];
        }

        $total = $pending + $processed;
        $percentage = $total <= 0 ? 0 : ceil($successful / floatval($total) * 100);
        if ($percentage_only) {
            return $percentage;
        }

        return ['operation' => 'payment',
            'status' => $batch_payment->batch_status_id,
            'status_description' => $batch_payment->status_description,
            'total' => $processed + $pending,
            'processed' => $processed,
            'pending' => $pending,
            'successful' => $successful,
            'failed' => $failed,
            'percentage' => $percentage,
            'extended' => $extended,
            'scheduled_at' => $batch_payment->schedule_at
        ];

    }

    /**
     * Performing a blocking batch verification - Network name search and customer name search
     *
     * @param $batch_processing_id
     * @return int
     * @throws Exception
     */
    public static function doBatchVerification($batch_processing_id)
    {

        return (new BankPaymentController())->batchProcessor($batch_processing_id, 'verify');
    }


    /**
     *  Performing a blocking batch payments for verified entries
     *
     * @param $batch_processing_id
     * @return int
     * @throws Exception
     */
    public static function doBatchPayment($batch_processing_id)
    {
        return (new BankPaymentController())->batchProcessor($batch_processing_id, 'disburse');
    }


    /**
     * Put the batch with the given Id on the queue for verification
     * @param $batch_id
     * @return int
     */

    public static function queueVerifyBatch($batch_id)
    {
        return self::queueBatchOperation($batch_id, 'verify');
    }

    /**
     * @param $batch_id
     * @return int
     */

    public static function queueBatchPayment($batch_id)
    {
        //Check if there is another item queued for that organization
        $batch = BankBatchPayment::query()->where(['id' => $batch_id])->first();
        if (!empty($batch)) {
            $others = BankBatchPayment::query()
                ->where(['organization_id' => $batch->organization_id])
                ->whereIn('batch_status_id', [Batch::STATUS_ON_PROGRESS, Batch::STATUS_QUEUED])
                ->first();
            if ($others) {
                return self::ERROR_HAS_ITEMS_ON_QUEUE;
            }
        }

        return self::queueBatchOperation($batch_id, 'disburse');//add the batch to disbursement, parameter: disburse
    }

    /**
     * @param $short_code
     * @return array
     */
    public static function requestOrganizationKYC($short_code)
    {
        return (new OrganizationController())->requestOrganizationKYC($short_code);
    }

    /**
     * @param $batch_id
     * @param $operation
     * @return int
     */

    private static function queueBatchOperation($batch_id, $operation)
    {
        $batch = ($operation == 'verify' ? BankVerificationBatch::query() : BankBatchPayment::query())
            ->where(['id' => $batch_id])
            ->first();

        if (!BankPaymentController::checkBatchValidity($batch, $operation)) {

            return self::ERROR_COULD_NOT_QUEUE;

        }
        if ($operation == 'verify') {

            $batch_processing = BankBatchProcessing::addQueuedBatch($operation, $batch, Auth::id());

            Queue::later(0, new BankProcessBatch($batch_id, $operation, $batch_processing->id));


        } else {
            if (strtotime($batch->schedule_at) - time() > 0) {
                //schedule
                $batch_processing = BatchProcessing::addQueuedBatch($operation, $batch, Auth::id(), BatchProcessing::STATUS_SCHEDULED);
                $batch->update(['batch_status_id' => Batch::STATUS_SCHEDULED,]);
                Queue::later(Carbon::now()->createFromTimestamp(strtotime($batch->schedule_at)), new ScheduledDisbursement($batch_processing->id, Auth::id()));
                return self::SCHEDULED;
            } else {
                //Queue now - check balance first
                BankBatchProcessing::addQueuedBatch($operation, $batch, Auth::id());

                Queue::later(0, new BankBalanceCheckForDisbursement($batch_id, Auth::id()));

            }
        }
        $batch->update(['batch_status_id' => Batch::STATUS_QUEUED]);
        return self::QUEUED;
    }


    public static function notifyInititor($initiator_id, $message)
    {
        $user = User::query()->where(['id' => $initiator_id])->first();
        if (!empty($user)) {
            SMSHelper::sendSingle($user->phone_number, $message);
        }
    }

    /**
     * Check organization account balance
     * @param $organization_id
     * @param int $initiator
     * @return array
     */

    public static function doBalanceCheck($organization_id, $initiator = null)
    {
        return (new BankPaymentController())->checkBalance($organization_id, $initiator);
    }

    /************************* ***************************/
    /****************** For API testing **************/
    /************************* ***************************/

    public static function testCustomNameSearch($entry_id)
    {
        $entry = BankDisbursementVerification::query()->where(['id' => $entry_id])->first();

        if (empty($entry)) {
            throw new Exception("Disbursement entry with an id of $entry_id is not found");
        }

        return (new BankPaymentController())->customerNameSearch($entry);
    }

    public static function testPayment($entry_id)
    {
        $entry = BankPaymentDisbursement::query()->where(['id' => $entry_id])->first();
        if (empty($entry)) {
            throw new Exception("Disbursement entry with an id of $entry_id is not found");
        }

        $batch = BankBatchPayment::query()->where(['batch_no' => $entry->batch_no])->first();
        $organization = Organization::query()->where(['id' => $batch->organization_id])->first();

        if (empty($organization)) {
            throw new Exception("Disbursement entry with an id of $entry_id has an invalid short code");
        }

        return (new BankPaymentController())->disbursement($organization, $entry, BankBatchProcessing::query()->first(),$batch->with_withdrawal_fee == 'YES');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Helper\BankDisbursementApiHelper;
use App\Helper\CredentialsRepo;
use App\Helper\DisbursementApiHelper;
use App\Helper\HttpHelper;
use App\Helper\Util;
use App\Helper\XMLHelper;
use App\Http\Controllers\Controller;
use App\Jobs\BankProcessBatch;
use App\Jobs\ProcessBatch;
use App\Models\BankBatchPayment;
use App\Models\BankBatchProcessing;
use App\Models\BankPaymentDisbursement;
use App\Models\Batch;
use App\Models\BatchPayment;
use App\Models\BatchProcessing;
use App\Models\Disbursement;
use App\Models\DisbursementOpeningBalance;
use App\Models\DisbursementPayment;
use App\Models\Organization;
use App\Models\OrganizationAccountBalance;
use App\Models\TransactionCharge;
use App\Models\TxBankDisbursement;
use App\Models\TxCheckBalance;
use App\Models\TxCustomerNameSearch;
use App\Models\TxDisbursement;
use App\Models\WithdrawalFee;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

/**
 * Class paymentController
 * @package App\Http\Controllers\Api
 */
class PaymentController extends Controller
{
    const PROCESSING_STATUS_SUCCESS = 1;
    const PROCESSING_STATUS_PARTIAL_FAILURE = 2;
    const PROCESSING_STATUS_FAILED = 3;
    const PROCESSING_STATUS_INVALID_BATCH = 4;
    const PROCESSING_STATUS_CANCELLED = 5;

    /**
     * @param Request $request
     * @return int
     */
    public function disbursementCallback(Request $request)
    {
        $content = $request->getContent();
        Log::channel('tx')->info($content);
        $data = XMLHelper::XMLStringToArray($content);

        if (empty($data)) {
            return XMLHelper::arrayToXML(['responseCode' => 999, 'responseDesc' => 'Could not parse the request XML'], 'response');
        }

        $tx = TxDisbursement::query()
            ->where(['reference_number' => $data['ID']])
            ->first();

        if (empty($tx)) {
            return '';
        } elseif (is_numeric($data['RESULT_CODE']) && $data['RESULT_CODE'] == 0) {
            try {
                DB::beginTransaction();
                $entry = DisbursementPayment::query()->where(['id' => $tx->entry_id])->first();
                $batch = BatchPayment::query()->lockForUpdate()->where(['batch_no' => $entry->batch_no])->first();
                $entry->update(['payment_status' => DisbursementPayment::STATUS_PAID, 'status_description' => 'Payment successful']);
                $tx->update([
                    'mpesa_receipt' => $data['MPESA_RECEIPT'],
                    'status' => 'SUCCESS',
                    'callback_dump' => $content,
                    'callback_result_desc' => $data['RESULT_DESC']
                ]);

                $tx_charge = TransactionCharge::query()->whereRaw("{$entry->amount} BETWEEN min_amount AND max_amount")->first();
                $charge = $tx_charge->charge ?? null;
                if ($batch->with_withdrawal_fee == 'YES' && strtolower($entry->network_name) == 'vodacom') {
                    $withdrawal_fee = WithdrawalFee::query()->whereRaw("{$entry->amount} BETWEEN min_amount AND max_amount")->first();
                    $fee = $withdrawal_fee->fee ?? null;
                    $entry->update(['withdrawal_fee' => $fee, 'tx_charge' => $charge]);
                    $batch->update([
                        'total_withdrawal_fees' => $batch->total_withdrawal_fees + $fee,
                        'total_tx_charges' => $batch->total_tx_charges + $charge,
                    ]);
                } else {
                    $entry->update(['tx_charge' => $charge]);
                    $batch->update([
                        'total_tx_charges' => $batch->total_tx_charges + $charge,
                    ]);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();

                Log::error($e->getMessage() . "\n" . $e->getTraceAsString());
            }

            $org_account = $tx->short_code;
            $org = Organization::query()->where(['short_code' => $org_account])->first();
            $amount = number_format($tx->amount);
            $org_name = $org->name ?? " ";
            $time = date('d/n/y') . ' at ' . date('h:i A');
            $msg = "{$data['MPESA_RECEIPT']} Confirmed.You have received Tsh{$amount} from {$org_account} - {$org_name} on {$time}";
            //            SMSHelper::sendSingle(/*$tx->phone_number*/'255753387833', $msg);
            return response('', 200)->header('Content-Type', 'text/xml');
        } else if ($data['RESULT_CODE'] == 'TS28009') { //when we have insufficient balance
            Log::info("Not enough balance on callback");
            DB::beginTransaction(); //we need to do lockForUpdate - prevent other sessions from modifying statuses while we are updating
            try {
                $entry = DisbursementPayment::query()->where(['id' => $tx->entry_id])->first();
                $batch = BatchPayment::query()->lockForUpdate()->where(['batch_no' => $entry->batch_no])->first();
                $bp = BatchProcessing::query()->lockForUpdate()->find($tx->batch_processing_id);
                //Check if we did cancel this batch processing
                if ($bp->result != BatchProcessing::RESULT_INSUFFIENT_BALANCE_FAILURE || $batch->status != Batch::STATUS_ON_HOLD) {
                    //We need to cancel the ongoing process
                    $batch->update(['batch_status_id' => Batch::STATUS_ON_HOLD, 'status_description' => 'insufficient balance']);
                    $bp->update(['status' => BatchProcessing::STATUS_PROCESSED, 'result' => BatchProcessing::RESULT_INSUFFIENT_BALANCE_FAILURE]);
                    DisbursementApiHelper::notifyInititor(
                        $bp->initiator_id,
                        "Batch no: {$batch->user_batch_no} processing could not complete due to insufficient balance, please top up the account and retry"
                    );
                }
                $entry->update(['payment_status' => DisbursementPayment::STATUS_ERROR, 'status_description' => 'insufficient balance']);
                $tx->update([

                    'mpesa_receipt' => $data['MPESA_RECEIPT'] ?? null,
                    'status' => 'FAILED',
                    'callback_dump' => $content,
                    'callback_result_desc' => $data['RESULT_DESC'] ?? null

                ]);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
            }

            return response('', 200)->header('Content-Type', 'text/xml');
        } elseif (is_numeric($data['RESULT_CODE']) && $data['RESULT_CODE'] == 1) {
            if ($data['TRANSACTION_STATUS']) {
                $transaction_status = strtolower($data['TRANSACTION_STATUS']);

                if ($transaction_status == 'authorized') {
                    return response('', 200)->header('Content-Type', 'text/xml');
                }
            }
        } elseif (is_numeric($data['RESULT_CODE']) && $data['RESULT_CODE'] == -1) {
            return response('', 200)->header('Content-Type', 'text/xml');
        }

        DisbursementPayment::query()->where([
            'id' => $tx->entry_id
        ])->update([
            'payment_status' => DisbursementPayment::STATUS_ERROR,
            'status_description' => ($data['RESULT_DESC'] == 'Process service request successfully.') ? $data['TRANSACTION_STATUS'] : $data['RESULT_DESC'] ?? 'Payment failed'
            //'status_description' => $data['RESULT_DESC'] ?? 'Payment failed'
        ]);

        $tx->update([
            'mpesa_receipt' => $data['MPESA_RECEIPT'] ?? null,
            'status' => 'FAILED',
            'callback_dump' => $content,
            'callback_result_desc' => $data['RESULT_DESC'] ?? null
        ]);

        $entry = DisbursementPayment::query()->where(['id' => $tx->entry_id])->first();

        $batchStatusUpdate = DisbursementPayment::query();

        $failed = $batchStatusUpdate->where(['batch_no' => $entry->batch_no, 'payment_status' => 2])->count();

        $total = $batchStatusUpdate->where(['batch_no' => $entry->batch_no])->count();

        if ($total != $failed) {

            BatchPayment::query()
                ->where(['batch_no' => $entry->batch_no])
                ->update(['batch_status_id' => Batch::STATUS_COMPLETED_WITH_FAILED_ITEM, 'status_description' => 'Some items failed']);
        } else {
            BatchPayment::query()
                ->where(['batch_no' => $entry->batch_no])
                ->update(['batch_status_id' => Batch::STATUS_FAILED, 'status_description' => 'failed']);
        }
        return response('', 200)->header('Content-Type', 'text/xml');
    }


    //callback for bank disbursement
    public static function bankDisbursementCallback(Request $request)
    {
        $content = $request->getContent();
        Log::channel('tx')->info($content);
        $data = XMLHelper::XMLStringToArray($content);

        if (empty($data)) {
            return XMLHelper::arrayToXML(['responseCode' => 999, 'responseDesc' => 'Could not parse the request XML'], 'response');
        }

        $tx = TxDisbursement::query()
            ->where(['reference_number' => $data['conversationID']])
            ->first();

        if (empty($tx)) {
            return '';
        } elseif (is_numeric($data['RESULT_CODE']) && $data['RESULT_CODE'] == 0) {
            try {
                DB::beginTransaction();
                $entry = DisbursementPayment::query()->where(['id' => $tx->entry_id])->first();
                $batch = BatchPayment::query()->lockForUpdate()->where(['batch_no' => $entry->batch_no])->first();
                $entry->update(['payment_status' => DisbursementPayment::STATUS_PAID, 'status_description' => 'Payment successful']);
                $tx->update([
                    'mpesa_receipt' => $data['MPESA_RECEIPT'],
                    'status' => 'SUCCESS',
                    'callback_dump' => $content,
                    'callback_result_desc' => $data['RESULT_DESC']
                ]);

                $tx_charge = TransactionCharge::query()->whereRaw("{$entry->amount} BETWEEN min_amount AND max_amount")->first();
                $charge = $tx_charge->charge ?? null;

                $entry->update(['tx_charge' => $charge]);
                $batch->update([
                    'total_tx_charges' => $batch->total_tx_charges + $charge,
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();

                Log::error($e->getMessage() . "\n" . $e->getTraceAsString());
            }

            $org_account = $tx->short_code;
            $org = Organization::query()->where(['short_code' => $org_account])->first();
            $amount = number_format($tx->amount);
            $org_name = $org->name ?? " ";
            $time = date('d/n/y') . ' at ' . date('h:i A');
            $msg = "{$data['MPESA_RECEIPT']} Confirmed.You have received Tsh{$amount} from {$org_account} - {$org_name} on {$time}";
            //            SMSHelper::sendSingle(/*$tx->phone_number*/'255753387833', $msg);
            return '';
        } else if ($data['RESULT_CODE'] == 'TS28009') { //when we have insufficient balance
            Log::info("Not enough balance on callback");
            DB::beginTransaction(); //we need to do lockForUpdate - prevent other sessions from modifying statuses while we are updating
            try {
                $entry = DisbursementPayment::query()->where(['id' => $tx->entry_id])->first();
                $batch = BatchPayment::query()->lockForUpdate()->where(['batch_no' => $entry->batch_no])->first();
                $bp = BatchProcessing::query()->lockForUpdate()->find($tx->batch_processing_id);
                //Check if we did cancel this batch processing
                if ($bp->result != BatchProcessing::RESULT_INSUFFIENT_BALANCE_FAILURE || $batch->status != Batch::STATUS_ON_HOLD) {
                    //We need to cancel the ongoing process
                    $batch->update(['batch_status_id' => Batch::STATUS_ON_HOLD, 'status_description' => 'insufficient balance']);
                    $bp->update(['status' => BatchProcessing::STATUS_PROCESSED, 'result' => BatchProcessing::RESULT_INSUFFIENT_BALANCE_FAILURE]);
                    DisbursementApiHelper::notifyInititor(
                        $bp->initiator_id,
                        "Batch no: {$batch->user_batch_no} processing could not complete due to insufficient balance, please top up the account and retry"
                    );
                }
                $entry->update(['payment_status' => DisbursementPayment::STATUS_ERROR, 'status_description' => 'insufficient balance']);
                $tx->update([

                    'mpesa_receipt' => $data['MPESA_RECEIPT'] ?? null,
                    'status' => 'FAILED',
                    'callback_dump' => $content,
                    'callback_result_desc' => $data['RESULT_DESC'] ?? null

                ]);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
            }

            return '';
        } elseif (is_numeric($data['RESULT_CODE']) && $data['RESULT_CODE'] == 1) {
            if ($data['TRANSACTION_STATUS']) {
                $transaction_status = strtolower($data['TRANSACTION_STATUS']);

                if ($transaction_status == 'authorized') {
                    return '';
                }
            }
        } elseif (is_numeric($data['RESULT_CODE']) && $data['RESULT_CODE'] == -1) {
            return '';
        }

        DisbursementPayment::query()->where([
            'id' => $tx->entry_id
        ])->update([
            'payment_status' => DisbursementPayment::STATUS_ERROR,
            'status_description' => ($data['RESULT_DESC'] == 'Process service request successfully.') ? $data['TRANSACTION_STATUS'] : $data['RESULT_DESC'] ?? 'Payment failed'
            //'status_description' => $data['RESULT_DESC'] ?? 'Payment failed'
        ]);

        $tx->update([
            'mpesa_receipt' => $data['MPESA_RECEIPT'] ?? null,
            'status' => 'FAILED',
            'callback_dump' => $content,
            'callback_result_desc' => $data['RESULT_DESC'] ?? null
        ]);

        $entry = DisbursementPayment::query()->where(['id' => $tx->entry_id])->first();

        $batchStatusUpdate = DisbursementPayment::query();

        $failed = $batchStatusUpdate->where(['batch_no' => $entry->batch_no, 'payment_status' => 2])->count();

        $total = $batchStatusUpdate->where(['batch_no' => $entry->batch_no])->count();

        if ($total != $failed) {

            BatchPayment::query()
                ->where(['batch_no' => $entry->batch_no])
                ->update(['batch_status_id' => Batch::STATUS_COMPLETED_WITH_FAILED_ITEM, 'status_description' => 'Some items failed']);
        } else {
            BatchPayment::query()
                ->where(['batch_no' => $entry->batch_no])
                ->update(['batch_status_id' => Batch::STATUS_FAILED, 'status_description' => 'failed']);
        }
        return '';
    }


    public static function checkBatchValidity($batch, $operation)
    {
        switch ($operation) {
            case 'verify':
            {
                return !empty($batch)
                    && Disbursement::query()->where(['batch_no' => $batch->batch_no])->count() > 0
                    && ($batch->batch_status_id == Batch::STATUS_PENDING
                        || $batch->batch_status_id == Batch::STATUS_FAILED
                        || $batch->batch_status_id == Batch::STATUS_QUEUED);
            }
            case 'disburse':
            {
                return !empty($batch)
                    && DisbursementPayment::query()->where(['batch_no' => $batch->batch_no])->count() > 0
                    && ($batch->batch_status_id == Batch::STATUS_PENDING
                        || $batch->batch_status_id == Batch::STATUS_QUEUED
                        || $batch->batch_status_id == Batch::STATUS_FAILED
                        || $batch->batch_status_id == Batch::STATUS_ON_HOLD);
            }
        }

        return false;
    }

    /**
     * @param int $batch_processing_id
     * @param string $operation
     * @return int
     * @throws Exception
     */
    public function batchProcessor($batch_processing_id, $operation = 'verify')
    {

        $bp = BatchProcessing::query()->find($batch_processing_id);
        $batch_id = $bp->batch_id;

        if ($operation == 'verify') {
            $batch = Batch::query()->where(['id' => $batch_id])->first();

            if (!$this->checkBatchValidity($batch, $operation)) {
                return self::PROCESSING_STATUS_INVALID_BATCH;
            }
            //check if it has not been verified
            $batch->update(['batch_status_id' => Batch::STATUS_ON_PROGRESS]);
            $result = $this->processBatchVerification($batch, $bp);
            if ($result == 1) {
                $batch->update(['batch_status_id' => Batch::STATUS_COMPLETED]);
            } else {
                $batch->update(['batch_status_id' => Batch::STATUS_FAILED]);
                return self::PROCESSING_STATUS_PARTIAL_FAILURE;
            }
        } else if ($operation == 'disburse') {
            $batch = BatchPayment::query()->where(['id' => $batch_id])->first();

            if (!$this->checkBatchValidity($batch, $operation)) {
                return self::PROCESSING_STATUS_INVALID_BATCH;
            }

            $batch->update(['batch_status_id' => Batch::STATUS_ON_PROGRESS]);
            $result = $this->processBatchPayment($batch, $bp);
            if ($result == 1) {
                $batch->update(['batch_status_id' => Batch::STATUS_COMPLETED, 'batch_completed_date' => Carbon::now('Africa/Nairobi')]);
            } elseif ($result == -1) {
                $batch->update(['batch_status_id' => Batch::STATUS_ON_HOLD]);
                return self::PROCESSING_STATUS_CANCELLED;
            } else {
                $batch->update(['batch_status_id' => Batch::STATUS_FAILED, 'batch_completed_date' => Carbon::now('Africa/Nairobi')]);
                return self::PROCESSING_STATUS_PARTIAL_FAILURE;
            }
        } else {
            throw new Exception("Invalid operation $operation");
        }

        return self::PROCESSING_STATUS_SUCCESS;
    }


    /**
     * @param Batch $batch
     * @param BatchProcessing $batch_processing
     * @param bool $reverify
     * @return int
     */
    public function processBatchVerification($batch, $batch_processing, $reverify = false)
    {

        $batch_entries = Disbursement::query()->where(['batch_no' => $batch->batch_no])->get();

        $failed_requests = 0;
        $entries_count = count($batch_entries);

        foreach ($batch_entries as $entry) {

            if ($entry->payment_status == Disbursement::STATUS_PAID && !$reverify) {
                continue;
            }

            ['data' => $data, 'error' => $error] = $this->customerNameSearch($entry);

            //Failed to search customer name - Connectivity issue
            if (empty($data) || !empty($error)) {
                $entry->update([
                    'payment_status' => Disbursement::STATUS_ERROR,
                    'status_description' => $data['MESSAGE'] ?? 'Name check failed (Network)'
                ]);
                ++$failed_requests;
                continue;
            }

            if (!is_numeric($data['TXNSTATUS']) || $data['TXNSTATUS'] != 0) {
                $entry->update([
                    'payment_status' => Disbursement::STATUS_ERROR,
                    'status_description' => $data['MESSAGE'] ?? 'Name check failed'
                ]);
                continue;
            }

            //halotel phone numbers all names comes on the FIRSTNAME field
            if (empty($data['LASTNAME']) && !empty($data['FIRSTNAME'])) {
                $names = explode(" ", $data['FIRSTNAME']);
                $first_name = $names[0];
                $last_name = count($names) > 1 ? $names[1] : '';
            } else {
                $first_name = $data['FIRSTNAME'];
                $last_name = $data['LASTNAME'];
            }

            $entry->update([
                'verified_first_name' => $first_name,
                'verified_last_name' => $last_name,
                'network_name' => $data['REFERENCEID'],
                'payment_status' => Disbursement::STATUS_PAID,
                'status_description' => 'Name check successful'
            ]);
        }
        $total_amount = DB::table('disbursements')->where('batch_no', '=', $batch->batch_no)->sum('amount');
        $total_amount += DB::table('disbursements')->where('batch_no', '=', $batch->batch_no)->sum('withdrawal_fee');

        $batch->update(['total_amount' => $total_amount]);
        /*Log::info("--------------------SENDING EMAILS TO APPROVER(S) -----------------");
        $approvers  = OrganizationApproval::query()
                ->select('users.email', 'users.id','users.phone_number','organization_approval.approval_level')
                ->join('users', 'users.id', '=', 'organization_approval.user_id')
                ->where(['organization_approval.organization_id' => $batch_processing->organization_id])->get();


            foreach($approvers as $first_approvers){
                if($first_approvers->approval_level = 1){
                    $mail = new MailController();
                    $token = $batch->batch_no;
                    if(!is_null($first_approvers->email)){
                        $mail->sendApprovalEmail($first_approvers->email, 5, $token);
                    }

                }
            }
        Log::info("--------------------COMPLETE SENDING EMAILS TO APPROVER(S) -----------------");*/
        return 1;
    }


    /**
     * @param $batch
     * @param BatchProcessing $batch_processing
     * @return int
     */
    public function processBatchPayment($batch, $batch_processing)
    {

        $batch_entries = DisbursementPayment::query()->where(['batch_no' => $batch->batch_no])->get();
        $organization = Organization::query()->where(['id' => $batch->organization_id])->first();

        $failed_requests = 0;

        foreach ($batch_entries as $entry) {
            //check is the entry is not paid yet
            if (
                $entry->payment_status == DisbursementPayment::STATUS_PAID ||
                $entry->payment_status == DisbursementPayment::STATUS_SENT
            ) {
                continue;
            }

            $batch_processing->refresh();
            if ($batch_processing->status != BatchProcessing::STATUS_ON_PROGRESS) {
                echo "Cancelled payment";
                return -1; //Cancelled payment
            }

            $tx = TxDisbursement::query()
                ->where(['entry_id' => $entry->id])
                ->whereIn('status', ['ON-PROGRESS', 'SUCCESS'])
                ->first();

            if (!empty($tx)) {
                $entry->update([
                    'payment_status' => $tx->status == 'SUCCESS' ? DisbursementPayment::STATUS_PAID : DisbursementPayment::STATUS_SENT
                ]);
                continue;
            }

            ['data' => $data, 'error' => $error] = $this->disbursement($organization, $entry, $batch_processing, $batch->with_withdrawal_fee == 'YES');

            if (!empty($data)) {
                $entry->update(['payment_status' => DisbursementPayment::STATUS_SENT, 'status_description' => 'Sent - Waiting Confirmation']);
            } else {
                $entry->update(['payment_status' => DisbursementPayment::STATUS_ERROR, 'status_description' => 'failed to send']);
                ++$failed_requests;
            }
        }

        /*Log::info("--------------------SENDING EMAILS TO INITIATOR(S) -----------------");
        $details = DB::table('users')->select('email')->where('id',$batch_processing->initiator_id)->first();

            $mail = new MailController();
            $token = $batch->batch_no;
            if(!is_null($details->email)){
                $mail->sendApprovalEmail($details->email, 6, $token);
            }

        Log::info("--------------------COMPLETE SENDING EMAILS TO INITIATOR(S) -----------------");*/

        return $failed_requests == 0 ? 1 : 0; //1 success, 0 - completed with failures
    }

    //function used to serarch customer account name
    public function customerAccountNameSearch($entry)
    {
        $reference = Util::generateRandom(20);
        $phone_number = Util::addPhonePrefix($entry->phone_number);
        $reference = Util::generateRandom(20);
        $msisdn = $entry->phone_number;
        $account_number = $entry->account_number;
        $bank_name = $entry->bank_name;
        $req_data = [
            'id' => $reference,
            'username' => CredentialsRepo::getMPesaBankDisbursementAPIUsername(),
            'password' => CredentialsRepo::getMPesaBankDisbursementAPIPassword(),
            'msisdn' => $msisdn,
            'accountNo' => $account_number,
            'bankname' => $bank_name
        ];

        $req_xml = XMLHelper::arrayToXML($req_data, 'message');

        //[TODO] - Waiting for model (TxCustomerAccountNameSearch) to be created

        // $tx = TxCustomerNameSearch::query()->create([
        //     'entry_id' => $entry->id,
        //     'reference_number' => $reference,
        //     'phone_number' => $phone_number,
        //     'status' => 'PENDING',
        //     'request_dump' => $req_xml,
        // ]);

        ['code' => $httpCode, 'data' => $raw_response, 'error' => $error] = HttpHelper::send($req_xml, true, 'raw', HttpHelper::API_ENDPONT_BANK_ACCOUNT_NAMECHECK);

        $data = empty($raw_response) ? null : XMLHelper::XMLStringToArray($raw_response);

        //[TODO] - Waiting for initial API testing for creating the application logic

        // if (!empty($error) || $httpCode != 200) {
        //     $tx->update(['status' => 'FAILED', 'failure_reason' => HttpHelper::guessFailureReason($httpCode, $error)]);
        //     return ['data' => null, 'error' => $error];
        // } else if (empty($data)) {
        //     $tx->update(['status' => 'FAILED', 'failure_reason' => 'INVALID_RESPONSE', 'response_dump' => $raw_response]);
        //     return ['data' => null, 'error' => "Invalid response!"];
        // } else if (is_numeric($data['TXNSTATUS']) && $data['TXNSTATUS'] == 0) {
        //     $tx->update(['status' => 'SUCCESS', 'network_name' => $data['REFERENCEID'], 'response_dump' => $raw_response]);
        //     return ['data' => $data, 'error' => null];
        // } else {
        //     $tx->update(['status' => 'FAILED', 'response_dump' => $raw_response]);
        //     return ['data' => null, 'error' => $data->responseDesc ?? "Invalid response"];
        // }
    }

    /**
     * @param $entry
     * @return array
     */
    public function customerNameSearch($entry)
    {
        $phone_number = Util::addPhonePrefix($entry->phone_number);
        $reference = Util::generateRandom(20);
        $req_data = [
            'initiator' => CredentialsRepo::getInitiatorUsername($entry->batch->organization_id),
            'initiatorPassword' => CredentialsRepo::getInitiatorPassword($entry->batch->organization_id),
            'TYPE' => 'QuerySubscriberReq',
            'REFERENCEID' => $reference,
            'MSISDN' => $phone_number,
            'MSISDN1' => $phone_number
        ];

        $req_xml = XMLHelper::arrayToXML($req_data, 'COMMAND');

        $tx = TxCustomerNameSearch::query()->create([
            'entry_id' => $entry->id,
            'reference_number' => $reference,
            'phone_number' => $phone_number,
            'status' => 'PENDING',
            'request_dump' => $req_xml,
        ]);

        ['code' => $httpCode, 'data' => $raw_response, 'error' => $error] = HttpHelper::send($req_xml, true, 'raw', HttpHelper::API_ENDPOINT_NAMECHECK_SIMULATION);

        $data = empty($raw_response) ? null : XMLHelper::XMLStringToArray($raw_response);

        if (!empty($error) || $httpCode != 200) {
            $tx->update(['status' => 'FAILED', 'failure_reason' => HttpHelper::guessFailureReason($httpCode, $error)]);
            return ['data' => null, 'error' => $error];
        } else if (empty($data)) {
            $tx->update(['status' => 'FAILED', 'failure_reason' => 'INVALID_RESPONSE', 'response_dump' => $raw_response]);
            return ['data' => null, 'error' => "Invalid response!"];
        } else if (is_numeric($data['TXNSTATUS']) && $data['TXNSTATUS'] == 0) {
            $tx->update(['status' => 'SUCCESS', 'network_name' => $data['REFERENCEID'], 'response_dump' => $raw_response]);
            return ['data' => $data, 'error' => null];
        } else {
            $tx->update(['status' => 'FAILED', 'response_dump' => $raw_response]);
            return ['data' => null, 'error' => $data->responseDesc ?? "Invalid response"];
        }
    }

    //function to disburse to bank
    public function bankDisbursement($organization, $entry, $batch_processing)
    {
        $network = $entry->network_name;
        $amount = $entry->amount;
        $bank_id = $entry->bank_id;
        $organization_account = $organization->short_code;
        $account_number = $entry->account_number;
        $bank_name = $entry->bank_name;
        $phone_number = Util::addPhonePrefix($entry->phone_number);
        $service = "disbursementToBank";
        $reference = Util::generateRandom(20);
        //use the reference number from the database
        $reference = $entry->conversation_id;
        $req_data = [
            'username' => CredentialsRepo::getMPesaBankDisbursementAPIUsername(),
            'password' => CredentialsRepo::getMPesaBankDisbursementAPIPassword(),
            'reference_number' => $reference,
            'service' => $service,
            'recipient' => $phone_number,
            'initiator' => CredentialsRepo::getInitiatorUsername($organization->id),
            'initiatorPassword' => CredentialsRepo::getInitiatorPassword($organization->id),
            'AccountNo' => $account_number,
            'bankID' => $bank_id,
            'orgAccount' => $organization_account,
            'amount' => $amount,
            'bankName' => $bank_name,
            'datetime' => date('dmYHis'),
        ];

        $req_xml = XMLHelper::arrayToXML($req_data, 'request');

        //save the request to the database
        $tx = TxBankDisbursement::query()->create(
            [
                'entry_id' => $entry->id,
                'batch_processing_id' => $batch_processing->id,
                'reference_number' => $reference,
                'short_code' => $organization->short_code,
                'phone_number' => $phone_number,
                'bank_id' => $bank_id,
                'bank_name' => $bank_name,
                'account_number' => $account_number,
                'amount' => $amount,
                'status' => 'PENDING',
                'request_dump' => $req_xml,
            ]
        );

        //send the request to vodacom apis
        ['code' => $httpCode, 'data' => $raw_response, 'error' => $error] = HttpHelper::send($req_xml, true, 'raw', HttpHelper::API_ENDPONT_BANK_DISBURSE);

        $data = empty($raw_response) ? null : XMLHelper::XMLStringToArray($raw_response);

        //handle the requst response before callback hit
        if (!empty($error) || $httpCode != 200) {
            $tx->update(['status' => 'FAILED', 'failure_reason' => HttpHelper::guessFailureReason($httpCode, $error)]);
            return ['data' => null, 'error' => $error];
        } else if (empty($data)) {
            $tx->update(['status' => 'FAILED', 'failure_reason' => 'INVALID_RESPONSE', 'response_dump' => $raw_response]);
            return ['data' => null, 'error' => "Invalid response"];
        } else if ((!empty($data['responseDesc']) && $data['responseDesc'] == 'Request received')
            || (is_numeric($data['responseCode']) && $data['responseCode'] == 0)
        ) { //API returns different response for Vodacom numbers and other numbers
            $tx->update(['status' => 'ON-PROGRESS', 'response_dump' => $raw_response]);
            return ['data' => $data, 'error' => null];
        } else {
            $tx->update(['status' => 'FAILED', 'response_dump' => $raw_response]);
            return ['data' => null, 'error' => $data->responseDesc ?? "Invalid response"];
        }
    }

    //function to disburse to mno
    public function disbursement($organization, $entry, $batch_processing, $with_withdrawal_charges = false)
    {
        $network = $entry->network_name;
        $amount = $entry->amount + (empty($entry->withdrawal_fee) ? 0 : $entry->withdrawal_fee);
        $phone_number = Util::addPhonePrefix($entry->phone_number);
        //$reference = Util::generateRandom(20);
        //use the reference number from the database
        $reference = $entry->conversation_id;
        if (strtolower($network) == 'vodacom') {
            $service = $with_withdrawal_charges ? 'disbursementWithWithdrawCharge' : 'disbursementWithoutWithdrawCharge';
        } else {
            $service = 'disbursementToOtherNetwork';
        }
        $req_data = [
            'username' => CredentialsRepo::getMpesaApiUsername(),
            'password' => CredentialsRepo::getMpesaApiPassword(),
            'initiator' => CredentialsRepo::getInitiatorUsername($organization->id),
            'initiatorPassword' => CredentialsRepo::getInitiatorPassword($organization->id),
            'conversationID' => $reference,
            'service' => $service,
            'recipient' => $phone_number,
            'network' => $network,
            'amount' => $amount,
            'orgAccount' => $organization->short_code,
            'orgName' => $organization->name,
            'datetime' => date('dmYHis'),
        ];

        $req_xml = XMLHelper::arrayToXML($req_data, 'request');

        $tx = TxDisbursement::query()->create(
            [
                'entry_id' => $entry->id,
                'batch_processing_id' => $batch_processing->id,
                'reference_number' => $reference,
                'short_code' => $organization->short_code,
                'phone_number' => $phone_number,
                'network_name' => $network,
                'amount' => $amount,
                'status' => 'PENDING',
                'request_dump' => $req_xml,
            ]
        );

        ['code' => $httpCode, 'data' => $raw_response, 'error' => $error] = HttpHelper::send($req_xml, true, 'raw', HttpHelper::API_ENDPOINT_DISBURSE_SIMULATION);

        $data = empty($raw_response) ? null : XMLHelper::XMLStringToArray($raw_response);

        if (!empty($error) || $httpCode != 200) {
            $tx->update(['status' => 'FAILED', 'failure_reason' => HttpHelper::guessFailureReason($httpCode, $error)]);
            return ['data' => null, 'error' => $error];
        } else if (empty($data)) {
            $tx->update(['status' => 'FAILED', 'failure_reason' => 'INVALID_RESPONSE', 'response_dump' => $raw_response]);
            return ['data' => null, 'error' => "Invalid response"];
        } else if ((!empty($data['msg']) && $data['msg'] == 'Request received')
            || (is_numeric($data['responseCode']) && $data['responseCode'] == 0)
        ) { //API returns different response for Vodacom numbers and other numbers
            $tx->update(['status' => 'ON-PROGRESS', 'response_dump' => $raw_response]);
            return ['data' => $data, 'error' => null];
        } else {
            $tx->update(['status' => 'FAILED', 'response_dump' => $raw_response]);
            return ['data' => null, 'error' => $data->responseDesc ?? "Invalid response"];
        }
    }


    /**
     * @param $organization_id
     * @param $initiator
     * @return array
     */

    public function checkBalance($organization_id, $initiator = null)
    {
        $organization = Organization::query()->where(['id' => $organization_id])->first();

        if (empty($organization)) {
            return ['status' => 'failed', 'message' => 'Failed to process your request please try again later!!'];
        }
        $account_number = $organization->short_code;
        $conversation_id = Util::generateRandom(20);

        $request = [
            'service' => 'checkOrgBalance',
            'username' => CredentialsRepo::getMpesaApiUsername(),
            'password' => CredentialsRepo::getMpesaApiPassword(),
            'initiator' => CredentialsRepo::getInitiatorUsername($organization_id),
            'initiatorPassword' => CredentialsRepo::getInitiatorPassword($organization_id),
            'orgAccount' => $account_number,
            'conversationID' => $conversation_id,
        ];

        $request_xml = XMLHelper::arrayToXML($request, 'request');

        $tx_id = DB::table('tx_check_balance')->insertGetId([
            'organization_id' => $organization_id,
            'account_number' => $account_number,
            'conversation_id' => $conversation_id,
            'request_dump' => $request_xml,
            'status' => 'PENDING',
            'initiator_id' => Auth::id() ?? $initiator ?? null,
        ]);

        //post request to our end point and return raw xml string
        $response = HttpHelper::send($request_xml, true, 'raw', HttpHelper::API_ENDPOINT_BALANCE);

        Log::info($response);
        if (empty($response['data'])) {
            TxCheckBalance::query()->where('conversation_id', $conversation_id)->update(['status' => 'FAILED']);
            return ['status' => 'failed', 'message' => 'Failed to process your request please try again later!!'];
        } else {
            $data = XMLHelper::XMLStringToObject($response['data']);
            if (is_numeric($data->responseCode) && $data->responseCode == 0) {
                TxCheckBalance::query()->where(['conversation_id' => $conversation_id])->update(['status' => 'PENDING', 'response_dump' => $response['data']]);
                return ['status' => 'success', 'message' => 'Balance Inquiry sent', 'tx_id' => $tx_id];
            } else {
                TxCheckBalance::query()->where(['conversation_id' => $conversation_id])->update(['status' => 'FAILED', 'response_dump' => $response['data']]);
                return ['status' => 'failed', 'message' => $data->responseDesc . ' (From Service)'];
            }
        }
    }

    public function balanceCallback(Request $request)
    {
        $content = $request->getContent();
        Log::channel('tx')->info($content);

        $data = XMLHelper::XMLStringToObject($content);
        $tx = TxCheckBalance::query()->where(['conversation_id' => $data->conversationID])->first();

        if (!empty($tx)) {
            $oab = null;
            if (is_numeric($data->resultCode) && $data->resultCode == 0) {
                $tx->update([
                    'status' => 'SUCCESS',
                    'callback_dump' => $content,
                    'current_balance' => $data->CurrentBalance
                ]);

                $oab = OrganizationAccountBalance::query()->create([
                    'organization_id' => $tx->organization_id,
                    'current_balance' => $data->CurrentBalance,
                    'account_type' => $data->accountType,
                    'available_balance' => $data->AvailableBalance,
                    'reserved_balance' => $data->ReservedBalance,
                    'uncleared_balance' => $data->UnclearedBalance,
                ]);
            } else {
                $tx->update(['status' => 'FAILED', 'callback_dump' => $content]);
            }

            $this->handlePendingOpeningBalanceRx($tx, $oab);
        }


        return XMLHelper::arrayToXML([
            'service' => 'checkOrgBalanceResult',
            'responseCode' => '0',
            'responseDesc' => 'success',
        ], 'response');
    }

    private function handlePendingOpeningBalanceRx($tx, $oab)
    {

        $batch = BatchPayment::query()->where(['batch_status_id' => Batch::STATUS_QUEUED, 'organization_id' => $tx->organization_id])->first();

        $bankBatch = BankBatchPayment::query()->where(['batch_status_id' => Batch::STATUS_QUEUED, 'organization_id' => $tx->organization_id])->first();

        if (!empty($batch)) {

            if (empty($oab)) {
                $batch->update([
                    'batch_status_id' => Batch::STATUS_FAILED,
                    'status_description' => 'Failed to query balance'
                ]);
                BatchProcessing::query()
                    ->where(['batch_id' => $batch->id, 'status' => BatchProcessing::STATUS_QUEUED, 'operation' => 'DISBURSE'])
                    ->update(['status' => BatchProcessing::STATUS_PROCESSED, 'result' => BatchProcessing::RESULT_FAILED]);
                DisbursementApiHelper::notifyInititor(
                    $tx->initiator_id,
                    "Disbursement for batch no: {$batch->user_batch_no} could be processed, System failed to query account balance. please retry again"
                );
                //update all batch entries set to failed
                DisbursementPayment::query()
                    ->where(['batch_no' => $batch->batch_no, 'payment_status' => 0])
                    ->update(['payment_status' => 2, 'failure_reason' => "Failed to query balance", 'status_description' => "Failed to query balance"]);
            } else {
                $unpaid_amount = (float)DisbursementPayment::query()
                    ->where(['batch_no' => $batch->batch_no])
                    ->whereIn('payment_status', [DisbursementPayment::STATUS_NOT_PAID, DisbursementPayment::STATUS_ERROR])
                    ->sum('amount');
                $dob = DisbursementOpeningBalance::query()->create([
                    'batch_id' => $batch->id,
                    'balance_id' => $oab->id,
                    'transaction_type' => 'mno',
                    'sufficient' => ($oab->available_balance >= $unpaid_amount) ? 'YES' : 'NO',
                ]);

                if ($dob->sufficient != 'YES') {
                    DisbursementApiHelper::notifyInititor(
                        $tx->initiator_id,
                        "Disbursement for batch no: {$batch->user_batch_no} failed due to insufficient balance. Available balance is {$oab->available_balance} while batch total amount for unpaid ebtries is {$unpaid_amount}."
                    );
                    BatchProcessing::query()
                        ->where(['batch_id' => $batch->id, 'status' => BatchProcessing::STATUS_QUEUED, 'operation' => 'DISBURSE'])
                        ->update(['status' => BatchProcessing::STATUS_PROCESSED, 'result' => BatchProcessing::RESULT_FAILED]);
                    $batch->update([
                        'batch_status_id' => Batch::STATUS_FAILED,
                        'status_description' => 'Insufficient balance'
                    ]);
                    //update all batch entries set to failed
                    DisbursementPayment::query()
                        ->where(['batch_no' => $batch->batch_no, 'payment_status' => 0])
                        ->update(['payment_status' => 2, 'failure_reason' => "Insufficient balance", 'status_description' => "Insufficient balance"]);
                } else {
                    //Queue batch if the disbursement opening balance is sufficient
                    $batch_processing = BatchProcessing::query()
                        ->where(['batch_id' => $batch->id, 'status' => BatchProcessing::STATUS_QUEUED])
                        ->first();
                    Queue::later(0, new ProcessBatch($batch->id, 'disburse', $batch_processing->id ?? null));
                    $batch->update([
                        'batch_status_id' => Batch::STATUS_QUEUED,
                        'status_description' => 'Queued'
                    ]);
                }
            }
        }


        if (!empty($bankBatch)) {

            if (empty($oab)) {
                $bankBatch->update([
                    'batch_status_id' => Batch::STATUS_FAILED,
                    'status_description' => 'Failed to query balance'
                ]);
                BankBatchProcessing::query()
                    ->where(['batch_id' => $bankBatch->id, 'status' => BatchProcessing::STATUS_QUEUED, 'operation' => 'DISBURSE'])
                    ->update(['status' => BatchProcessing::STATUS_PROCESSED, 'result' => BatchProcessing::RESULT_FAILED]);
                BankDisbursementApiHelper::notifyInititor(
                    $tx->initiator_id,
                    "Disbursement for batch no: {$bankBatch->user_batch_no} could be processed, System failed to query account balance. please retry again"
                );
                //update all batch entries set to failed
                BankPaymentDisbursement::query()
                    ->where(['batch_no' => $batch->batch_no, 'payment_status' => 0])
                    ->update(['payment_status' => 2, 'failure_reason' => "Failed to query balance", 'status_description' => "Failed to query balance"]);
            } else {
                $unpaid_amount = (float)BankPaymentDisbursement::query()
                    ->where(['batch_no' => $bankBatch->batch_no])
                    ->whereIn('payment_status', [DisbursementPayment::STATUS_NOT_PAID, DisbursementPayment::STATUS_ERROR])
                    ->sum('amount');
                $dob = DisbursementOpeningBalance::query()->create([
                    'batch_id' => $bankBatch->id,
                    'balance_id' => $oab->id,
                    'transaction_type' => 'bank',
                    'sufficient' => ($oab->available_balance >= $unpaid_amount) ? 'YES' : 'NO',
                ]);

                if ($dob->sufficient != 'YES') {
                    BankDisbursementApiHelper::notifyInititor(
                        $tx->initiator_id,
                        "Disbursement for batch no: {$bankBatch->user_batch_no} failed due to insufficient balance. Available balance is {$oab->available_balance} while batch total amount for unpaid ebtries is {$unpaid_amount}."
                    );
                    BankBatchProcessing::query()
                        ->where(['batch_id' => $bankBatch->id, 'status' => BatchProcessing::STATUS_QUEUED, 'operation' => 'DISBURSE'])
                        ->update(['status' => BatchProcessing::STATUS_PROCESSED, 'result' => BatchProcessing::RESULT_FAILED]);
                    $bankBatch->update([
                        'batch_status_id' => Batch::STATUS_FAILED,
                        'status_description' => 'Insufficient balance'
                    ]);
                    //update all batch entries set to failed
                    BankPaymentDisbursement::query()
                        ->where(['batch_no' => $bankBatch->batch_no, 'payment_status' => 0])
                        ->update(['payment_status' => 2, 'failure_reason' => "Insufficient balance", 'status_description' => "Insufficient balance"]);
                } else {
                    $batch_processingb = BankBatchProcessing::query()
                        ->where(['batch_id' => $bankBatch->id, 'status' => BatchProcessing::STATUS_QUEUED])
                        ->first();
                    Queue::later(0, new BankProcessBatch($bankBatch->id, 'disburse', $batch_processingb->id ?? null));
                    $bankBatch->update([
                        'batch_status_id' => Batch::STATUS_QUEUED,
                        'status_description' => 'Queued'
                    ]);
                }
            }
        }
    }
}

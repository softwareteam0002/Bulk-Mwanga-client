<?php

namespace App\Http\Controllers\Payment;

use App\Audit\Audit;
use App\Helper\AmountMissMatch;
use App\Helper\BankDisbursementApiHelper;
use App\Helper\ConstantList;
use App\Http\Controllers\Controller;
use App\Models\BankBatchPayment;
use App\Models\BankPaymentDisbursement;
use App\Models\BatchPayment;
use App\Models\BatchPaymentApproval;
use App\Models\Organization;
use App\Models\OrganizationAccountBalance;
use App\Models\OrganizationApproval;
use App\Models\TxCheckBalance;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class BankPaymentController extends Controller
{

    public const WAIT_FOR_APPROVAL = 1;

    public function __construct()
    {
        $this->middleware(['auth', 'ctoken', 'orgapproved']);
    }

    /* get batch per organization */
    public static function getBatchPerOrganization()
    {

        $organizationId = Auth::user()->organization_id;

        $shortCode = Organization::where('id', $organizationId)->first();

        if ($shortCode) {

            $disbursements = DB::table('batches')->where('organization_id', $organizationId)->paginate(300);

            return $disbursements;

        }

        return false;

    }

    /* function to return disbursement payment view.*/

    public function payments()
    {

        $orgaDisbursements = BankBatchPayment::query()->where(['organization_id' => Auth::user()->organization_id])->latest()
            ->paginate(300);

        Audit::saveActivityLogDb(Auth::user()->username, '', "View all batches payments", "modify", 'success');

        return view('disbursements.bank.payments', compact('orgaDisbursements'));

    }

    public function view()
    {

        return view('disbursements.view_records_per_organization');

    }

    public function progress()
    {

        return view('disbursements.progress');

    }

    public function disbusementView($batch_no)
    {

        //        dd($batch_no);
        try {

            $batch_no = decrypt($batch_no);



            $batch = BankBatchPayment::query()->where(['batch_no' => $batch_no])->first();

            $user_batch_no = $batch->user_batch_no;
            $disbursements = BankPaymentDisbursement::query()->where(['batch_no' => $batch_no])->latest()->get();

            // return response()->json($disbursements);

            $operation = "payment";

            $amountCheck = AmountMissMatch::compareAmount($batch_no, 'bank');
            //dd($amountCheck);
            //return $amountCheck;

            return view('disbursements.bank.view_all', compact('amountCheck', 'disbursements', 'user_batch_no', 'batch_no', 'operation', 'batch'));

        } catch (\Exception $de) {

            Log::error($de);

            //            dd($de);
            return redirect()->route('bank-disbursement.payments');
        }

    }

    /* process batch for payment */

    /*public function  paymentRequest(Request $request){

         $organizationId =  Auth::user()->organization_id;



         try{
             $batch_no =  decrypt($request->batch_no);

         } catch (DecryptException $exception){
             Log::error($exception->getMessage()."\n".$exception->getTraceAsString());
             Session::flash('alert-danger','Unsuccessful try again');
             return  back();
         }



         $userBatchNo  = BankBatchPayment::query()->select('user_batch_no')->where(['batch_no'=>$batch_no])->first();


         $amountCheck =  AmountMissMatch::compareAmount($batch_no,'bank');

         if ($amountCheck['status']==true){

             Session::flash('alert-danger','There is amount miss match, you can\'t approve this batch, please click view button for more info');

             return redirect()->route('bank-disbursement.payments');

         }

         if (self::hasApprovedBatch($batch_no)){

             Session::flash('alert-danger','This batch has been already approved at your level');
             Audit::saveActivityLogDb(Auth::user()->username,'Batch number '.$batch_no,"This batch has been already approved at your level","modify",'success');

             return redirect()->route('bank-disbursement.payments');

         }

         self::approve($batch_no,'bank');

         $numberOrg =  self::getNumberOfApprovalPerOrganization($organizationId);

         $countForBatch  =  self::getNumberApprovedTheBatch($organizationId,$batch_no,'bank');

         if ($countForBatch!==$numberOrg){

             Organization::updateHandler($batch_no,'bank');

             Session::flash('alert-success','Waiting Further Approval');

             Audit::saveActivityLogDb(Auth::user()->username,'Batch number '.$batch_no,"Batch Approved","modify",'success');

             return  redirect()->route('disbursement.payments');

         }

         $batch = BankBatchPayment::query()->where(['batch_no'=>$batch_no])->first();

         $queueing_status =  BankDisbursementApiHelper::queueBatchPayment($batch->id);

         if ($queueing_status==BankDisbursementApiHelper::ERROR_COULD_NOT_QUEUE){
             Session::flash('alert-danger','Your batch could not be queued. Please ensure it has entries and it has the right status');
         }elseif ($queueing_status==BankDisbursementApiHelper::ERROR_HAS_ITEMS_ON_QUEUE){
             Session::flash('alert-danger','Your batch could not be queued. You have another Batch ON disbursement queue. You can only queue one batch at a time');
         }elseif ($queueing_status==BankDisbursementApiHelper::ERROR_FAILED_TO_CHECK_BALANCE){
             Session::flash('alert-danger','Balance check failed!');
         }else if ($queueing_status==BankDisbursementApiHelper::QUEUED){

             Session::flash('alert-success','Your batch payment is Queued!');

             Organization::updateHandler($batch_no,'bank');

         }else if ($queueing_status==BankDisbursementApiHelper::SCHEDULED){

             Session::flash('alert-success','Your batch payment has been scheduled!');

             Organization::updateHandler($batch_no,'bank');

         }else{
             Session::flash('alert-danger','Failed to Queue!');
         }

         Audit::saveActivityLogDb(Auth::user()->username,'Batch number '.$batch_no,"Approve payment","modify",'success');

         return redirect('bank-disbursement/payments');

     }*/

    public function paymentRequest(Request $request)
    {

        $organizationId = Auth::user()->organization_id;

        try {
            $batch_no = $request->batch_no;

        } catch (DecryptException $exception) {
            Log::error($exception->getMessage() . "\n" . $exception->getTraceAsString());
            Session::flash('alert-danger', 'Unsuccessful try again');
            return back();
        }

        DB::beginTransaction();
        try {
            $userBatchNo = BankBatchPayment::query()->select('user_batch_no')->where(['batch_no' => $batch_no])->first();

            $amountCheck = AmountMissMatch::compareAmount($batch_no, 'bank');


            if ($amountCheck['status'] == true) {

                Session::flash('alert-danger', 'There is amount miss match, you can\'t approve this batch, please click view button for more info');

                return redirect()->route('bank-disbursement.payments');

            }

            $organization = Organization::select('minimum_approver', 'short_code', 'approval_type')->where('id', $organizationId)->first();
            if ($organization->approval_type == ConstantList::NON_SEQUENCE_APPROVAL_TYPE) {
                $minimum_approval = $organization->minimum_approver;
                if ($minimum_approval >= 1) {
                    $level = OrganizationApproval::query()->select('approval_level')->where(['user_id' => Auth::user()->id])->first();
                    $save_batch_payment = self::saveBatchPaymentApproval($organizationId, $batch_no, $level->approval_level);
                    if ($save_batch_payment) {
                        $numberApproval = self::getNumberApprovedTheBatch($organizationId, $batch_no, 'bank');
                        $numberApproval = $numberApproval + 1;
                        $batch_to_update = BankBatchPayment::query()->where(['batch_no' => $batch_no])->first();
                        $update_batch_payment = $batch_to_update->update(['number_of_approve' => $numberApproval]);

                        Organization::updateHandler($batch_no, 'bank');
                        Audit::saveActivityLogDb(Auth::user()->username, 'Batch number ' . $batch_no, "Batch Approved", "modify", 'success');

                        if ($minimum_approval > $numberApproval && $update_batch_payment) {
                            Session::flash('alert-success', 'Waiting Further Approval');
                            Log::info("Batch Approved waiting further approvals!, [$batch_no]");
                            DB::commit();
                            return redirect()->route('disbursement.payments');
                        }
                    } else {
                        DB::rollback();
                        Log::error("Failed to update batch records!, [$batch_no]");
                        Session::flash('alert-danger', 'Failed to update batch records');
                        return redirect()->route('disbursement.payments');
                    }
                } else {
                    Log::info("Minimum Approval number is not set for this organization, [$organization->short_code]");
                    Session::flash('alert-danger', 'Minimum Approval number is not set for this organization, Contact Administrator!');
                    return redirect()->route('disbursement.payments');
                }
            } else {
                if (self::hasApprovedBatch($batch_no)) {

                    Session::flash('alert-danger', 'This batch has been already approved at your level');
                    Audit::saveActivityLogDb(Auth::user()->username, 'Batch number ' . $batch_no, "This batch has been already approved at your level", "modify", 'success');
                    DB::commit();
                    return redirect()->route('bank-disbursement.payments');

                }

                self::approve($batch_no, 'bank');

                $numberOrg = self::getNumberOfApprovalPerOrganization($organizationId);

                $countForBatch = self::getNumberApprovedTheBatch($organizationId, $batch_no, 'bank');

                if ($countForBatch !== $numberOrg) {

                    Organization::updateHandler($batch_no, 'bank');

                    Session::flash('alert-success', 'Waiting Further Approval');

                    Audit::saveActivityLogDb(Auth::user()->username, 'Batch number ' . $batch_no, "Batch Approved", "modify", 'success');
                    DB::commit();
                    return redirect()->route('disbursement.payments');

                }
            }
            Log::info("Adding batch to Queue, [$batch_no]");

            $batch = BankBatchPayment::query()->where(['batch_no' => $batch_no])->first();

            $queueing_status = BankDisbursementApiHelper::queueBatchPayment($batch->id);

            if ($queueing_status == BankDisbursementApiHelper::ERROR_COULD_NOT_QUEUE) {
                DB::rollback();
                Session::flash('alert-danger', 'Your batch could not be queued. Please ensure it has entries and it has the right status');
            } elseif ($queueing_status == BankDisbursementApiHelper::ERROR_HAS_ITEMS_ON_QUEUE) {
                DB::rollback();
                Session::flash('alert-danger', 'Your batch could not be queued. You have another Batch ON disbursement queue. You can only queue one batch at a time');
            } elseif ($queueing_status == BankDisbursementApiHelper::ERROR_FAILED_TO_CHECK_BALANCE) {
                DB::rollback();
                Session::flash('alert-danger', 'Balance check failed!');
            } else if ($queueing_status == BankDisbursementApiHelper::QUEUED) {

                DB::commit();
                Session::flash('alert-success', 'Your batch payment is Queued!');

                Organization::updateHandler($batch_no, 'bank');

            } else if ($queueing_status == BankDisbursementApiHelper::SCHEDULED) {
                DB::commit();
                Session::flash('alert-success', 'Your batch payment has been scheduled!');

                Organization::updateHandler($batch_no, 'bank');

            } else {
                DB::rollback();
                Session::flash('alert-danger', 'Failed to Queue!');
            }

            Audit::saveActivityLogDb(Auth::user()->username, 'Batch number ' . $batch_no, "Approve payment", "modify", 'success');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('BANK-PAYMENT: ', ['message' => $e->getMessage()]);
        }
        return redirect('bank-disbursement/payments');

    }

    public function paymentRetry($batch_no)
    {

        $batch = BankBatchPayment::query()->where(['batch_no' => $batch_no])
            ->first();
        $queueing_status = BankDisbursementApiHelper::queueBatchPayment($batch->id);


        if ($queueing_status == BankDisbursementApiHelper::ERROR_COULD_NOT_QUEUE) {
            Session::flash('alert-danger', 'Your batch could not be queued. Please ensure it has entries and it is in Pending status');
        } elseif ($queueing_status == BankDisbursementApiHelper::ERROR_HAS_ITEMS_ON_QUEUE) {
            Session::flash('alert-danger', 'Your batch could not be queued. You have another Batch ON disbursement queue. You can only queue one batch at a time');
        } elseif ($queueing_status == BankDisbursementApiHelper::ERROR_FAILED_TO_CHECK_BALANCE) {
            Session::flash('alert-danger', 'Balance check failed!');
        } else if ($queueing_status == BankDisbursementApiHelper::QUEUED) {
            Session::flash('alert-success', 'Your batch payment is Queued!');
        } else {
            Session::flash('alert-danger', 'Failed to Queued!');
        }

        Audit::saveActivityLogDb(Auth::user()->username, 'Batch number ' . $batch_no, "Batch Retry", "modify", 'success');

        return redirect('bank-disbursement/payments');

    }

    /*return status for payment*/

    public function disbursementStatus($batch_no)
    {

        $batch = BankBatchPayment::query()->where(['batch_no' => $batch_no, 'organization_id' => Auth::user()->organization_id])->first();

        if (empty($batch)) {
            return response()->json([
                'response' => 'failed'
            ]);
        }

        return response()->json(
            array_merge(
                ['response' => 'success'],
                BankDisbursementApiHelper::getDisbursementStatus($batch_no, false, true)
            )
        );

    }

    public function queryOrgBalance(Request $request)
    {

        if ($request->ajax()) {
            $response = BankDisbursementApiHelper::doBalanceCheck(Auth::user()->organization_id);
            return response()->json(
                $response
            );
        }

        Audit::saveActivityLogDb(Auth::user()->username, '', "Query balance from mpesa", "modify", 'success');

        return response()->json(
            ['status' => 'failed', 'message' => 'Invalid request!']
        );
    }

    public function checkBalanceAvailability($tx_id)
    {

        $tx = TxCheckBalance::query()->find($tx_id);
        if ($tx->status == 'SUCCESS') {
            $balance = OrganizationAccountBalance::query()->where(['organization_id' => $tx->organization_id])->orderByDesc('created_at')->first();
            return response()->json(['status' => 'success', 'balance' => $balance, 'rq_status' => 'success']);
        }
        return response()->json(['status' => 'success', 'rq_status' => strtolower($tx->status)]);
    }

    /* check if total number of approval reached.*/
    public static function approve($batchId, $transactionType = null)
    {

        $organizationId = Auth::user()->organization_id;
        $level = OrganizationApproval::query()->select('approval_level')->first();
        $save_batch_payment = self::saveBatchPaymentApproval($organizationId, $batchId, $level->approval_level);

        if ($save_batch_payment) {

            //        if ($transactionType=='bank'){
            $number = BankBatchPayment::query()->select('number_of_approve')->where(['batch_no' => $batchId])->first();

            //        }

            $numberApproval = $number->number_of_approve + 1;


            if (self::getNumberOfApprovalPerOrganization($organizationId) < $numberApproval) {

                return 0;
            }

            //        if ($transactionType=='bank'){
            BankBatchPayment::query()->where(['batch_no' => $batchId])->update(['number_of_approve' => $numberApproval]);

            //        }
        }

        return self::getNumberApprovedTheBatch($organizationId, $batchId, 'bank');

    }

    /* Save Batch Payment approve*/

    public static function saveBatchPaymentApproval($organizationId, $batchId, $level)
    {
        $batchPaymentApp = new BatchPaymentApproval();
        $batchPaymentApp->organization_id = $organizationId;
        $batchPaymentApp->batch_id = $batchId;
        $batchPaymentApp->created_by = Auth::id();
        $batchPaymentApp->level = $level;
        if ($batchPaymentApp->save()) {
            return true;
        } else {
            return false;
        }
    }

    /* get the maximum number of set approve per organization*/

    public static function getNumberOfApprovalPerOrganization($organizationId)
    {

        $data = Organization::query()->where(['id' => $organizationId])->first();

        return $data->number_approval;

    }

    /* get the number of taotal users that approved the batch
     *@param $organizationId
     */

    public static function getNumberApprovedTheBatch($organizationId, $batchNumber, $transactionType = null)
    {

        if ($transactionType == 'bank') {
            $data = BankBatchPayment::query()->select('number_of_approve')->
                where(['organization_id' => $organizationId, 'batch_no' => $batchNumber])->first();

        } else {
            $data = BatchPayment::query()->select('number_of_approve')->
                where(['organization_id' => $organizationId, 'batch_no' => $batchNumber])->first();

        }
        return $data->number_of_approve;

    }

    public function paymentView($batch_no)
    {

        try {

            $batch_no = decrypt($batch_no);

        } catch (DecryptException $de) {

            return redirect()->route('bank-disbursement.payments');

        }

        $disbursements = BankPaymentDisbursement::query()->where(['batch_no' => $batch_no])->latest()->get();

        $batch = BankPaymentDisbursement::query()->where(['batch_no' => $batch_no])->first();

        $user_batch_no = $batch->user_batch_no;

        return view('disbursements.bank.view_all_payment', compact('user_batch_no', 'disbursements', 'batch_no'));

    }

    public static function hasApprovedBatch($batch)
    {

        $organizationId = Auth::user()->organization_id;

        $level = OrganizationApproval::query()->select('approval_level')
            ->where(['user_id' => Auth::user()->id])
            ->first();

        $check = BatchPaymentApproval::query()->where([
            'organization_id' => $organizationId,
            'batch_id' => $batch,
            'level' => $level->approval_level
        ])->first();

        if ($check) {

            return 1;

        }

        return 0;

    }

}

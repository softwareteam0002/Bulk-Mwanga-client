<?php

namespace App\Http\Controllers\Verification;

use App\Audit\Audit;
use App\Helper\BankDisbursementApiHelper;
use App\Helper\Util;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\HelperController;
use App\Imports\ImportBankDisbursement;
use App\Imports\ImportBankDisbursementPayment;
use App\Models\BankBatchPayment;
use App\Models\BankDisbursementVerification;
use App\Models\BankVerificationBatch;
use App\Models\Batch;
use App\Models\BatchPayment;
use App\Models\Organization;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

//use App\Helper\DisbursementApiHelper;

class BankVerificationController extends Controller
{

    public   function  index(){

        try{

            $verification = self::getVerificationPerOrganization();

//            return response()->json($verification);

            return view('disbursements.bank.index',compact('verification'));

        } catch (\Exception $exception){

            Session::flash('alert-danger','Try again '.$exception->getMessage());
            return back();
        }


    }

    public  static  function  getVerificationPerOrganization(){

        $organizationId =  Auth::user()->organization_id;

        return DB::table('bank_verification_batches')->where('organization_id',$organizationId)->latest()->paginate(300);

    }



    public  function  create() {
		
        $organization = Organization::query()->find(Auth::user()->getOrganizationId());

        $transactionType =   DB::table('transaction_types')->get();
        return view('disbursements.bank.create',
            ['withdrawal_fee_policy'=>$organization->withdraw,'transactionType'=>$transactionType]);
    }

    /* return view for disbursement*/

    public  function  view(){

        return view('disbursements.view_records_per_organization');

    }

    /* save disbursement for mnp*/

    public  function  store(Request $request){
		
        $validator = Validator::make(
            [
                'file'=> $request->file,
                'uploadType'=>$request->uploadType,
            ],
            [
                'file'=>'required|mimes:xlsx,xls',
                'uploadType'=>'required|max:32',
            ]
        );

        if ($validator->fails()){
            Session::flash('alert-warning',' Please Upload a valid Excel file '.$validator->errors());
            return redirect('bank-disbursement/create');
        }
		
		

        $uploadType =  $request->post('uploadType');
        $organization = Organization::query()->find(Auth::user()->getOrganizationId());
        if ($organization->withdraw!='SPECIFY PER BATCH'){
            $include_withdrawal_fee = $organization->withdraw=='YES';
        }else{
            $include_withdrawal_fee = $request->post('with_withdrawal_fee',1)==2; //1=NO, 2=YES
        }
		
		
        $url  =  "bank-disbursement/verifications";

        if ($uploadType==VerificationController::VERIFICATION_UPLOAD){

            DB::beginTransaction();

            try {


                $batch_number = HelperController::generateBatchNumber(Organization::shortCode(),'bank');

                $batch_number = 'B'.$batch_number;


                $user_batch_number = $request->post('batchNo',null)??$batch_number;


             if ($request->transaction_type==Util::TO_BANK){


                    Excel::import(new ImportBankDisbursement($batch_number), request()->file('file'));

					//dd($request);

                 self::saveToBankBatchVerification($batch_number,$user_batch_number);

//                 dd($user_batch_number);
                }

                Audit::saveActivityLogDb(Auth::user()->username,'Batch number '.$batch_number,"verification Batch uploaded successful","modify",'success');

                Session::flash('alert-success','Imported Successfully');
//                dd($user_batch_number);
                DB::commit();
            }catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $failures = $e->failures();
                foreach ($failures as $failure) {
                    $failure->row(); // row that went wrong
                    $failure->attribute(); // either heading key (if using heading row concern) or column index
                    $failure->errors(); // Actual error messages from Laravel validator
                    $failure->values(); // The values of the row that has failed.
                }

                Audit::saveActivityLogDb(Auth::user()->username,'Batch number '.$batch_number,"Failed to create batch  ".$e->getMessage()."\n".$e->getTraceAsString(),"modify",'success');

                Session::flash('alert-danger','The value of '.$failure->attribute().' on row '.$failure->row().' is not valid');
                DB::rollBack();
                return  redirect('bank-disbursement/create');
            } catch (Exception $e){
                Session::flash('alert-danger','Error Message: Please fill all necessary fields in the excel file');
                DB::rollBack();
                Log::error($e->getMessage()."\n".$e->getTraceAsString());
                Audit::saveActivityLogDb(Auth::user()->username,'Batch number '.$batch_number,"Failed to create batch  ".$e->getMessage()."\n".$e->getTraceAsString(),"modify",'success');

                return  redirect('bank-disbursement/create');
            }

        }

        else if ($uploadType==VerificationController::PAYMENT_UPLOAD) {

            DB::beginTransaction();
            try {

                $should_schedule = $request->post('payment_time',0);
                $scheduled_at = $request->post('scheduled_at',null);
                $description = $request->post('description',null);

                if (empty($should_schedule)){
                    Session::flash('alert-danger','Please select when should payment be processed');
                    return  redirect('bank-disbursement/create');
                }

                elseif ($should_schedule==2){
                    if (strtotime($scheduled_at)===false || strtotime($scheduled_at)<time()){
                        Session::flash('alert-danger','Please pick a valid date (should be at least a day from today)');
                        return  redirect('bank-disbursement/create');
                    }
                }

                elseif (empty($description)){
                    Session::flash('alert-danger','Please provide description for this disbursement!');
                    return  redirect('bank-disbursement/create');
                }

                  if ($request->transaction_type==Util::TO_BANK){

                    $url = "bank-disbursement/payments";
                    $importer = new ImportBankDisbursementPayment();
                    Excel::import($importer, request()->file('file'));

                    self::saveToBankBatchPayment($importer->getBatchNumbers(), $should_schedule == 2 ? $scheduled_at : null, $description,$include_withdrawal_fee);

                }

                Session::flash('alert-success','imported successfully');
                Audit::saveActivityLogDb(Auth::user()->username,'',"Payment Batch uploaded successful","modify",'success');

                DB::commit();

            }catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $failures = $e->failures();
                foreach ($failures as $failure) {
                    $failure->row(); // row that went wrong
                    $failure->attribute(); // either heading key (if using heading row concern) or column index
                    $failure->errors(); // Actual error messages from Laravel validator
                    $failure->values(); // The values of the row that has failed.
                }

                Session::flash('alert-danger','Error Message: '.$failure->attribute().' Row Failed: '.$failure->row());
                Audit::saveActivityLogDb(Auth::user()->username,'',"Failed to create batch  ".$e->getMessage()."\n","modify",'success');
                Log::error($e->getMessage()."\n".$e->getTraceAsString());
                DB::rollBack();
                return  redirect('bank-disbursement/create');
            }

            catch (Exception $e){

                Session::flash('alert-danger','Failed to create batch');
                Audit::saveActivityLogDb(Auth::user()->username,'',"Failed to create batch  ".$e->getMessage()."\n","modify",'success');
                Log::error($e->getMessage()."\n".$e->getTraceAsString());
                DB::rollBack();
                return  redirect('bank-disbursement/create');
            }
        }

        return redirect($url);

    }


    /* save the batch to disbursement table*/

    public static  function saveToBatchVerification($batch_no,$user_batch_number){

        $batchVerification  =  new Batch();
        $organizationId = Auth::user()->organization_id;

        $amount  = floatval(DB::table('disbursements')->where('batch_no','=',$batch_no)->sum('amount'));

        $batchVerification->batch_no = $batch_no;
        $batchVerification->user_batch_no = $user_batch_number;
        $batchVerification->organization_id = $organizationId;
        $batchVerification->total_amount  =  $amount;

        $batchVerification->save();
    }
    /* save the batch to disbursement table*/

    public static  function saveToBankBatchVerification($batch_no,$user_batch_number){

        $batchVerification  =  new BankVerificationBatch();

        $organizationId = Auth::user()->organization_id;

        $amount  = floatval(DB::table('bank_disbursement_verifications')->where('batch_no','=',$batch_no)->sum('amount'));

        $batchVerification->batch_no = $batch_no;
        $batchVerification->user_batch_no = $user_batch_number;
        $batchVerification->organization_id = $organizationId;
        $batchVerification->total_amount  =  $amount;

        $batchVerification->save();

    }


    /** save the batch to disbursement table
     **
     * @param $batchnos
     * @param $schedule_at
     * @param $description
     * @param bool $include_withdrawal_fee
     * @return bool
     * @throws Exception
     */
    public static  function saveToBatchPayment($batchnos, $schedule_at, $description,$include_withdrawal_fee){
        $batchPayment  =  new BatchPayment();

        $organizationId= Auth::user()->organization_id;

        $amount  =  floatval(DB::table('disbursement_payments')->where('batch_no','=',$batchnos['batch_number'])->sum('amount'));
        $amount  += floatval(DB::table('disbursement_payments')->where('batch_no','=',$batchnos['batch_number'])->sum('withdrawal_fee'));

        $batchPayment->batch_no = $batchnos['batch_number'];
        $batchPayment->user_batch_no = $batchnos['user_batch_number'];
        $batchPayment->organization_id = $organizationId;
        $batchPayment->total_amount  =  $amount;
        $batchPayment->with_withdrawal_fee  =  $include_withdrawal_fee?'YES':'NO';

        $initiator_names = Organization::getInitialHandler();

        if (empty($initiator_names)){
            throw new Exception('You do not have a permission to upload batch for disbursement');
        }
//        $batchPayment->handler  = $initiator_names;
//        $batchPayment->handler_level  = self::INITIAL_HANDLER_LEVEL;
        $batchPayment->schedule_at  = $schedule_at;
        $batchPayment->batch_description  = $description;
        $batchPayment->operator  = Auth::user()->first_name.' '.Auth::user()->last_name;
        return $batchPayment->save();
    }

    /** save the batch to disbursement table
     **
     * @param $batchnos
     * @param $schedule_at
     * @param $description
     * @param bool $include_withdrawal_fee
     * @return bool
     * @throws Exception
     */
    public static  function saveToBankBatchPayment($batchnos, $schedule_at, $description,$include_withdrawal_fee){

        $batchPayment  =  new BankBatchPayment();

        $organizationId= Auth::user()->organization_id;

        $amount  =  floatval(DB::table('bank_payment_disbursements')->where('batch_no','=',$batchnos['batch_number'])->sum('amount'));
        $amount  += floatval(DB::table('bank_payment_disbursements')->where('batch_no','=',$batchnos['batch_number'])->sum('withdrawal_fee'));

        $batchPayment->batch_no = $batchnos['batch_number'];
        $batchPayment->user_batch_no = $batchnos['user_batch_number'];
        $batchPayment->organization_id = $organizationId;
        $batchPayment->total_amount  =  $amount;
        $batchPayment->with_withdrawal_fee  =  $include_withdrawal_fee?'YES':'NO';

        $initiator_names = Organization::getInitialHandler();

        if (empty($initiator_names)){

            throw new Exception('You do not have a permission to upload batch for disbursement');

        }

//        $batchPayment->handler  = $initiator_names;
//        $batchPayment->handler_level  = self::INITIAL_HANDLER_LEVEL;

        $batchPayment->schedule_at  = $schedule_at;
        $batchPayment->batch_description  = $description;
        $batchPayment->operator  = Auth::user()->first_name.' '.Auth::user()->last_name;
        return $batchPayment->save();
    }
    public  function  progress(){
        return view('disbursements.progress');
    }

    public function  verificationView($batch_no){

        try {
            $batch_no = decrypt($batch_no);
            $batch = BankVerificationBatch::query()->where(['batch_no'=>$batch_no])->first();
        } catch (DecryptException $de){
            return redirect()->route('bank-disbursement.verifications');
        }

        $disbursements  =  BankDisbursementVerification::where('batch_no',$batch_no)->get();
        $operation = "verificarion";

//        return response()->json($batch);

        return view('disbursements.bank.view_all',compact('disbursements','batch_no','operation','batch'));
    }

    public function  bankNameSearch($batch_no)  {

        $batch = BankVerificationBatch::query()->where(['batch_no'=>$batch_no])->first();

        $queueing_status =  BankDisbursementApiHelper::queueVerifyBatch($batch->id);

        if ($queueing_status==BankDisbursementApiHelper::ERROR_COULD_NOT_QUEUE){

            Session::flash('alert-danger','Your batch could not be queued. Please ensure it has entries and its status is PENDING?');

        }

        else{

            Session::flash('alert-success','Your batch is being verified!');

        }

        return redirect('bank-disbursement/verifications');

    }

    public function  mnpSearchStatus($batch_no){

        $batch = BankVerificationBatch::query()->where(['batch_no'=>$batch_no,'organization_id'=>Auth::user()->organization_id])->first();
        if (empty($batch)){
            return response()->json([
                'response'=>'failed'
            ]);
        }
        return response()->json(
            array_merge(
                ['response'=>'success'],
                BankDisbursementApiHelper::getVerificationStatus($batch_no,false,true)
            )
        );
    }


}

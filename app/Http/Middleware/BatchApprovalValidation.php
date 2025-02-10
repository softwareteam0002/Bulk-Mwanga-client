<?php

namespace App\Http\Middleware;

use App\Helper\ConstantList;
use App\Models\BatchPaymentApproval;
use App\Models\Organization;
use App\Models\OrganizationApproval;
use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class BatchApprovalValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try{
            $batch_no =  decrypt($request->batch_no);
            Log::info("Batch Decrypted successfully, [$batch_no]");
        } catch (DecryptException $exception){
            Log::info("Batch Decrypted un successfully, [". $request->batch_no ."] Error: ".$exception->getMessage());
            return  back();
        }
        $request['batch_no']=$batch_no;
        $organizationId =  Auth::user()->organization_id;
		$organization = Organization::select('approval_type')->where('id', $organizationId)->first();
		if($organization->approval_type != ConstantList::NON_SEQUENCE_APPROVAL_TYPE){
			$request['organizationId ']=$organizationId ;
			$user_approval = OrganizationApproval::where(['organization_id'=>$organizationId,'user_id'=>Auth::user()->id])->first();
			$user_approval_level = $user_approval->approval_level;
			if($user_approval_level != 1){
				$level = $user_approval_level - 1;
				$is_approved_at_lower_level = BatchPaymentApproval::query()->select('level')->where(['level' => $level,'batch_id'=>$batch_no])->first();
				
				if($is_approved_at_lower_level){
					return $next($request);
				}else{
					Session::flash('alert-danger','You can\'t approve this batch, please ensure that it\'s first approved at lower level');
					return redirect()->route('disbursement.payments');
				}
			}

			Log::info("User Level is [$user_approval_level] and batch no is, [$batch_no]");
				 return $next($request);
			Log::info("Performing Next Action");
		}else{
			$batch_approver =  BatchPaymentApproval::where('batch_id',$batch_no)->where('created_by',Auth::user()->id)->first();
			Log::info("APPROVER: ".json_encode($batch_approver));
			if($batch_approver){
				Session::flash('alert-danger','You are not allowed to approve the same batch twice');
				return redirect()->route('disbursement.payments');
			}
			return $next($request);
		}
        




        //Get Org approval level
        /**
         * if approval level >1 then
         * check if approval level one has approved the batch if user is not  level one
         * loop the entire level to check if user has done any action
         */
        
    }
}

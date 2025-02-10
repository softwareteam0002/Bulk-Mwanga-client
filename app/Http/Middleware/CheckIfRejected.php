<?php

namespace App\Http\Middleware;

use App\Models\BatchPayment;
use App\Models\ConstantHelper;
use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Session;

class CheckIfRejected
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

        $batchId  =  $request->batch_no;

        try{

            $batchId =  decrypt($batchId);

        }
        catch (DecryptException $exception){

            return  back();
        }


        $batchPayment  =  BatchPayment::query()->where(['batch_no'=>$batchId,'is_rejected'=>ConstantHelper::BATCH_REJECTED])->first();
        if ($batchPayment){

            Session::flash('alert-danger','This Batch Has Been Rejected, You Cannot Process It.');

            return back();
        }
        return $next($request);
    }
}

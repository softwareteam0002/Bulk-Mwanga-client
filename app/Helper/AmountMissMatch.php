<?php


namespace App\Helper;


use App\Models\BankBatchPayment;
use App\Models\BankVerificationBatch;
use App\Models\Batch;
use App\Models\BatchPayment;
use Illuminate\Support\Facades\Auth;

class AmountMissMatch
{

    public  static  function  compareAmount($batchNo,$transactionType=null){

        if ($transactionType=='bank'){

            $batchV  =  BankVerificationBatch::query()->select('total_amount')
                ->where(['batch_no'=>$batchNo,'organization_id'=>Auth::user()->organization_id])->first();

//            dd(Auth::user()->organization_id);

            $batchP  =  BankBatchPayment::query()->select('total_amount')
                ->where(['batch_no'=>$batchNo,'organization_id'=>Auth::user()->organization_id])->first();

//            dd($batchV);

        }

        else {

            $batchV  =  Batch::query()->select('total_amount')
                ->where(['batch_no'=>$batchNo,'organization_id'=>Auth::user()->organization_id])->first();

            $batchP  =  BatchPayment::query()->select('total_amount')->where(['batch_no'=>$batchNo,'organization_id'=>Auth::user()->organization_id])->first();

        }
	
        if ($batchP->total_amount>$batchV->total_amount){

            return ['status'=>true,'vAmount'=>$batchV->total_amount,'pAmount'=>$batchP->total_amount];

        }

        return ['status'=>false,'vAmount'=>$batchV->total_amount,'pAmount'=>$batchP->total_amount];

    }

}

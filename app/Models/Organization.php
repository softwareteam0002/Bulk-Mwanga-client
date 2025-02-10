<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class  Organization extends Model
{
    //

    public function district(){
        return $this->belongsTo('App\Models\District')->with('region');
    }

    public  function contact_person(){

        return $this->hasOne('App\ContactPerson', 'organization_id');
    }


    public  static function shortCode(){

        $id  =   Auth::user()->organization_id;

    $data  =  Organization::query()->select('short_code')->where('id',$id)->first();


    if ($data){

        return $data->short_code;

    }

     Session::flash('alert-warning','An Error Occured , Contact Administrator');

    return redirect()->back();
}


    public  static  function  getInitialHandler(){

        $data  = OrganizationApproval::query()
            ->select('users.first_name','last_name')
            ->join('users','users.id','=','organization_approval.user_id')
            ->where(['organization_approval.organization_id'=>Auth::user()->organization_id,'organization_approval.approval_level'=>1])->first();

        return !empty($data)? $data->first_name.'  '.$data->last_name : null ;

    }

    public  static  function  updateHandler($batch_no,$transactionType=null){


        $userId  =  Auth::id();
        $data =  User::query()->where(['id'=>$userId])->first();

        $name  =  $data->first_name.'  '.$data->last_name;

        if ($transactionType=='bank'){
            $batch =  BankBatchPayment::query()->select('handler_level')->where(['batch_no'=>$batch_no])->first();

            $level  =  $batch->handler_level;

            $bpayment  = BankBatchPayment::query()->where(['batch_no'=>$batch_no])->first();

            $bpayment->handler=$name;
            $bpayment->handler_level = ($level+1);
            $bpayment->approved_date  =  Carbon::now('Africa/Dar_es_Salaam');
            $bpayment->save();
        }

        else {
            $batch =  BatchPayment::query()->select('handler_level')->where(['batch_no'=>$batch_no])->first();

            $level  =  $batch->handler_level;

            $bpayment  = BatchPayment::query()->where(['batch_no'=>$batch_no])->first();

            $bpayment->handler=$name;
            $bpayment->handler_level = ($level+1);
            $bpayment->approved_date  =  Carbon::now('Africa/Nairobi'); 
            $bpayment->save();
        }

    }


}

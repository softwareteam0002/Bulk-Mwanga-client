<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankPaymentDisbursement extends Model
{

    const STATUS_NOT_PAID = 0;
    const STATUS_PAID = 1;
    const STATUS_ERROR = 2;
    const STATUS_SENT = 10; //Payment has been sent, we are waiting for callback

    protected $table = 'bank_payment_disbursements';
    protected $guarded = [];


    public  function  organization(){
        return $this->belongsTo('App\Models\Organization','organization_id');
    }

    public function batch(){
        return $this->belongsTo('App\Models\BankBatchPayment','batch_no','batch_no');
    }


    public  static  function  paymentStatus($statusId){

        if ($statusId==0){

            return "Not processed";
        }
        if ($statusId==1){

            return "Paid";
        }

        if ($statusId==2){

            return "Failed";
        }

        if ($statusId==10){

            return "Sent";
        }


    }
}

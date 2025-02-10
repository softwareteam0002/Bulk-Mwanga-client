<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankBatchPayment extends Model
{

    protected $table = 'bank_batch_payments';
    protected $guarded = [];

    public  function  organization(){

        return $this->belongsTo('App\Models\Organization','organization_id');
    }

    public  function  disbursements(){

        return $this->hasMany('App\Models\BankPaymentDisbursement','batch_no','batch_no');
    }
}

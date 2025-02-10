<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/* class that manage all batches payments*/
class BatchPayment extends Model
{

    protected $table = 'batch_payments';
    protected $guarded = [];


    public  function  organization(){

        return $this->belongsTo('App\Models\Organization','organization_id');
    }

    public  function  disbursements(){

        return $this->hasMany('App\Models\DisbursementPayment','batch_no','batch_no');
    }
}

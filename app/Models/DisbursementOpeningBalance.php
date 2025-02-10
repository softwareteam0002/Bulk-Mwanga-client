<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/* class that handle balances*/
class DisbursementOpeningBalance extends Model
{
    protected $table ='disbursement_opening_balances';
    protected $guarded = [];


    public function organizationAccountBalance(){

        return $this->belongsTo('App\Models\OrganizationAccountBalance','balance_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TxCheckBalance extends Model
{
    const STATUS_NOT_PAID = 0;
    const STATUS_PAID = 1;
    const STATUS_ERROR = 2;

    protected $table = 'tx_check_balance';
    protected $guarded =[];
}

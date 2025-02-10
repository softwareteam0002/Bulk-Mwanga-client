<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\Models\BatchPayment;
use App\Models\Organization;

class DashboardHelper extends Controller
{
    //

    const  SUCCESS_PAYMENT = 1;
    const  FAILED_PAYMENT = 2;

    public  static function getAllOrganizations(){

        return Organization::query()->count("id");

    }


    public  static function getAllSuccessPaymentBatches(){

        return BatchPayment::query()->where(['batch_status_id'=>self::SUCCESS_PAYMENT])->count("id");

    }

    public  static function getAllFailedPaymentBatches(){

        return BatchPayment::query()->where(['batch_status_id'=>self::FAILED_PAYMENT])->count("id");

    }
}

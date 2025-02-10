<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class Notifications extends Controller
{


    const  nType   = "organizationActivation" ;
    /*
     * notification for  user who is responsible for activation

     */
    public static function  orgActivation(){

        $activationNoficiation  =  Notification::where(['notification_type'=>self::nType])->latest()->get();

        return $activationNoficiation;

    }

}

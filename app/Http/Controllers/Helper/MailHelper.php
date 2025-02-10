<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Mail\MailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MailHelper extends Controller
{
    //

    public  static function sendMail($type,$token,$userEmail=null){

        $mail  = new MailController();


        if ($type==1){

            $userEmail  =  Auth::user()->email;

        }

        $mail->sendMail($userEmail,$type,$token);

    }
}

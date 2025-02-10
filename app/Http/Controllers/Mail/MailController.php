<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use App\Jobs\SendMailJob;

class MailController extends Controller
{
    //

    public static function sendMail($email, $mailType, $token)
    {

        SendMailJob::dispatch($email, $mailType, $token)->delay(now()->addRealSeconds(1));

    }


    public static function send($email, $mailType, $token)
    {

        SendMailJob::dispatch($email, $mailType, $token)->delay(now()->addRealSeconds(1));

    }

    public static function sendMany(array $email, $mailType, $token)
    {

        SendMailJob::dispatch($email, $mailType, $token)->delay(now()->addRealSeconds(1));

    }

    public static function sendApprovalEmail($email, $mailType, $name)
    {

        SendMailJob::dispatch($email, $mailType, $name)->delay(now()->addRealSeconds(1));

    }

}

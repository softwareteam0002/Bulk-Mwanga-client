<?php

namespace App\Http\Controllers\Helper;

use App\Helper\SMSHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Mail\MailController;
use App\Jobs\SendSmsJob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginHelper extends Controller
{

    public const MAIL_TYPE = 1;

    public static function resendCode()
    {
        $token = HelperController::token();

        DB::table('users')->where('id', Auth::user()->id)->update(['token' => $token]);

        $sendBy = Auth::user()->send_by;
        $phoneNumber = Auth::user()->phone_number;
        $userEmail = Auth::user()->email;
        if ($sendBy == 1) {
            $message = "Your login token for Mwanga Hakika Disbursement Portal is: {$token}";
            SendSmsJob::dispatch($message, $phoneNumber);
        } else {
            MailController::sendMail($userEmail, self::MAIL_TYPE, $token);
        }

        return true;

    }

    public static function resendPasswordToken($userId, $phoneNumber)
    {

        $password = HelperController::generatePasswod();

        DB::table('users')->where('id', $userId)->update(['password' => Hash::make((string)$password)]);

        $user = User::query()->where(['id' => $userId])->first();

        // SMSHelper::sendSingle($phoneNumber,"Your Password for Vodacom Disbursement System has been reset successful, open your email ({$user->email}) to access the credentials");


        MailHelper::sendMail('2', $password, $user->email);

        SMSHelper::sendSingle($phoneNumber, "Your Password for Vodacom Disbursement System has been reset successful,  access the credential is  {$password}");

    }
}

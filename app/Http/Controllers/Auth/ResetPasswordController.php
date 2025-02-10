<?php

namespace App\Http\Controllers\Auth;

use App\Audit\Audit;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\HelperController;
use App\Http\Controllers\Mail\MailController;
use App\Jobs\SendSmsJob;
use App\Models\PasswordHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{


    public function reset(Request $request)
    {
        $username = $request->username;
        $oldPassword = $request->oldPassword;
        $newPassword = $request->password;
        $newPassword2 = $request->password_repeated;


        $validator = self::passwordValidator($request, Auth::id());

        if ($newPassword != $newPassword2) {
            Session::flash('alert-warning', 'Password and repeated password does not match');
            return redirect()->back();
        } else if ($validator->fails()) {
            Session::flash('alert-warning', $validator->errors()->all()[0]);
            return redirect()->back();
        }


        $user = User::where(['username' => $username])->first();


        if ($user) {

            $password = Hash::make($newPassword);


            if (Hash::check($oldPassword, $user->password)) {

                $token = HelperController::token();
                $success = DB::table('users')->where('username', $username)
                    ->update(['password' => $password, 'is_first_login' => 0, 'token' => $token]);

                if ($success) {
                    PasswordHistory::query()->where(['user_id' => $user->id])->update(['status' => 'INACTIVE']);
                    PasswordHistory::query()->create([
                        'user_id' => $user->id,
                        'password' => $password,
                        'status' => 'ACTIVE',
                    ]);

                    Audit::saveActivityLogDb(Auth::user()->username, $user->first_name . ' ' . $user->last_name, "Password reset ", "password-change-self", 'success');
                    $sendBy = Auth::user()->send_by;
                    $phoneNumber = Auth::user()->phone_number;
                    $userEmail = Auth::user()->email;

                    if ($sendBy == 1) {
                        $message = "Your login token for Mwanga Hakika Disbursement Portal is: {$token}";
                        SendSmsJob::dispatch($message, $phoneNumber);
                    } else {
                        MailController::sendMail($userEmail, 1, $token);
                    }

                    return redirect('/');

                }

                Session::flash('alert-danger', 'An Error Occurred, Try Again');

                return back();

            }

            Session::flash('alert-danger', 'Username or password is incorrect');

            return back();

        } else {


            Session::flash('alert-danger', 'Username or password is incorrect');

            return back();

        }


    }


    public static function passwordValidator($request, $user_id)
    {

        $email = $request->post('username');
        if (empty($email)) {
            $user = User::query()->where(['id' => $user_id])->first();
            $email = $user->username;
        }

        //Minimum password age must be set to 1 day
        $min_age = function ($attribute, $value, $fail) use ($user_id) {
            $active_password = PasswordHistory::query()->where(['user_id' => $user_id, 'status' => 'ACTIVE'])->first();
            $count = PasswordHistory::query()->where(['user_id' => $user_id])->count();
            if (!empty($active_password) && time() - strtotime($active_password->created_at) < 24 * 60 * 60 && $count > 1) { //should not be the first password
                $fail('New password cannot be changed within a day!');
            }
        };

        //Password reuse period must be greater than 12 months
        $password_reuse_period = function ($attribute, $value, $fail) use ($user_id) {
            $latest_passwords = PasswordHistory::query()
                ->where(['user_id' => $user_id])
                ->whereRaw('created_at BETWEEN ? AND ?', [date('YmdHis', strtotime('-1 year')), date('YmdHis')])
                ->orderBy('created_at')
                ->get();

            var_dump($latest_passwords->toArray());

            foreach ($latest_passwords as $password) {
                if (Hash::check($value, $password->password)) {
                    $fail('Password reuse period must be greater than 12 months');
                    return;
                }
            }
        };


        //Password must be different from the last 12 passwords used by the user
        $password_reuse_count = function ($attribute, $value, $fail) use ($user_id) {
            $latest_passwords = PasswordHistory::query()
                ->where(['user_id' => $user_id])
                ->orderBy('created_at')
                ->limit(12)
                ->get();
            foreach ($latest_passwords as $password) {
                if (Hash::check($value, $password->password)) {
                    $fail('Password must be different from the last 12 passwords');
                    return;
                }
            }
        };

        $similarity_with_username = function ($attribute, $value, $fail) use ($email) {
            if (strtolower($email) == strtolower($value)) {
                $fail('Username and password must not be identical!');
            }
        };

        return Validator::make($request->all(),
            [
                'password' => [
                    'required',
                    'safe:50',
                    'reject_dict_words', //reject dictionary words
                    'min:10',             // must be at least 10 characters in length
                    'regex:/[a-z]/',      // must contain at least one lowercase letter
                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                    'regex:/[0-9]/',      // must contain at least one digit
                    'regex:/[@$!%*#?&]/', // must contain a special character
                    'not_regex:/(\w)\1{2,}/', //must not contain more than 2 successive identical characters
                    $similarity_with_username,//Username and password must not be identical
                    $min_age,
                    $password_reuse_count,
                    $password_reuse_period,
                ]
            ],

            [ //BSR requires not to explicitly say the passwords weakness
                'min' => 'Password is not strong enough',
                'regex' => 'Password is not strong enough',
                'not_regex' => 'Password is not strong enough',
                'reject_dict_words' => 'Password is not strong enough!!'
            ]
        );
    }

}

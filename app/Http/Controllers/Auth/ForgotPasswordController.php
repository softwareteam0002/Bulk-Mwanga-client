<?php

namespace App\Http\Controllers\Auth;

use App\Audit\Audit;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\MailHelper;
use App\Models\PasswordHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function index()
    {
        return view('auth.forgot_password');
    }

    public function sendResetLink(Request $request)
    {
        try {
            Log::info('----FORGOT-PASSWORD----');
            Log::info('Email: ' . $request->input('email'));

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Check if the email exists in the database
            $user = User::select('email', 'is_active', 'user_type')->where('email', $request->input('email'))->first();

            // Create a password reset token
            $token = Str::random(90);

            // Encrypt the token before storing it
            $encryptedToken = encrypt($token);

            if ($user) {
                //Check status and type user
                if ($user->is_active != 1 || $user->user_type == 1) {
                    Log::info('---INACTIVE OR INVALID USER TYPE----');
                    Log::info($user->email);
                    Session::flash('alert-danger', 'Failed to recover your password. Try again later!');
                    return redirect('forgot_password');
                }
                // Store the token in the database
                $success = DB::table('password_resets')->updateOrInsert(
                    ['email' => trim($request->input('email'))], // Check for an existing record with this email
                    [
                        'token' => $token,
                        'created_at' => Carbon::now()
                    ] // If a record exists, update it. Otherwise, insert a new record.
                );

                if ($success) {
                    $resetUrl = URL::temporarySignedRoute('set_credentials', now()->addMinutes(30), ['token' => $encryptedToken]);
                    MailHelper::sendMail(8, $resetUrl, $request->input('email'));
                }
            }

            Session::flash('alert-success', 'We have emailed your password reset link!');
            return redirect('forgot_password');
        } catch (\Exception $e) {
            Log::info('---SEND LINK EXCEPTION----');
            Log::info(json_encode($e->getMessage()));
            Log::info(json_encode($e));
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect('forgot_password');
        }
    }

    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        return view('auth.set_password', compact('token'));
    }

    public function updatePassword(Request $request)
    {
        Log::info('----UPDATE-PASSWORD----');
        try {
            //check if the token has expired
            $decryptedToken = decrypt($request->input('token'));
            Log::info("Decypted Token: " . json_encode($decryptedToken));
            $token = DB::table('password_resets')->select('created_at', 'email')
                ->where('token', $decryptedToken)
                ->first();
            Log::info("Token: " . json_encode($token));
            if ($token->created_at < now()->subMinutes(30)) {
                //link has expired
                Session::flash('alert-danger', 'Password reset link has expired!, Please request new one');
                return redirect()->route('forgot_password');
            }

            //get user id
            $user = User::where('email', $token->email)->first();

            $reset = new ResetPasswordController();
            $validator = $reset->passwordValidator($request, $user->id);

            $newPassword = $request->input('password');
            $repeatedPassword = $request->input('password_repeated');

            if ($newPassword != $repeatedPassword) {
                Session::flash('alert-warning', 'Password and repeated password does not match');
                return redirect('set_credentials');
            } else if ($validator->fails()) {
                Session::flash('alert-warning', $validator->errors()->all()[0]);
                return redirect('set_credentials');
            }

            //update password
            $password = Hash::make($newPassword);
            DB::beginTransaction();
            $success = $user->update(['password' => $password, 'is_first_login' => 0]);

            if ($success) {
                PasswordHistory::query()->where(['user_id' => $user->id])->update(['status' => 'INACTIVE']);
                PasswordHistory::query()->create([
                    'user_id' => $user->id,
                    'password' => $password,
                    'status' => 'ACTIVE',
                ]);
                Audit::saveActivityLogDb($user->username, $user->first_name . ' ' . $user->last_name, "Password reset ",
                    "forgot-password", 'success');
                DB::commit();
                Session::flash('alert-warning', 'Password has been changed successfully!');
                return redirect('set_credentials');
            }
            Session::flash('alert-danger', 'An Error Occurred, Try Again');
            return redirect('set_credentials');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Update Password Exception:" . $e->getMessage());
            Log::error("Update Password Exception:" . $e);
            Session::flash('alert-danger', 'Failed to recover your password. Try again later!');
            return redirect('set_credentials');
        }
    }
}

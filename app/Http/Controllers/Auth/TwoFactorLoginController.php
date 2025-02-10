<?php


namespace App\Http\Controllers\Auth;

use App\Audit\Audit;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\LoginHelper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class TwoFactorLoginController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth')->except('firstLogin');
    }

    public function firstLogin()
    {

        $user = Auth::user();

        if (empty($user->username)) {

            // Auth::logout();

            return redirect('/');

        }

        $username = $user->username;

        return view('auth.change_password', compact('username'));


    }

    public function changePassword()
    {

        if (empty(Auth::user()->username)) {

            // Auth::logout();
            return redirect('/');

        }


        $username = Auth::user()->username;

        return view('auth.change_password', compact('username'));

    }

    public function tokenVerify()
    {

        $user = Auth::user();

        if (LoginController::checkFirstLogin($user)) {

            return redirect()->route('login.first');

        }
        if (!$user->username) {

            return redirect('/');

        }
        $username = $user->username;

        return view('auth.two_factor_auth_login', compact('username'));

    }


    public function verified(Request $request)
    {
        // Combine all the OTP values into a single string
        $token = implode('', $request->token);

        // Trim any spaces
        $token = trim($token);

        if (empty($token)) {
            Session::flash('alert-danger', 'Verification Code Is Required');
            return redirect()->back();
        }

        $id = Auth::user()->id;

        // Check if the token matches the one stored in the database for the user
        $tokenFromUserDb = User::query()->where(['id' => $id, 'token' => $token])->first();

        if (!$tokenFromUserDb) {
            Session::flash('alert-danger', 'Invalid Token');
            return redirect()->back();
        }

        // Mark the token as verified in the database and update the last login time
        DB::table('users')->where(['id' => $id])->update([
            'token_verified' => 1,
            'last_login' => Carbon::now('Africa/Nairobi')
        ]);

        // Log the successful login activity
        Audit::saveActivityLogDb(Auth::user()->username, Auth::user()->username, 'Login Successful', 'success', 'login');

        // Log the login success event
        $jsonLog = json_encode([
            'username' => Auth::user()->username,
            'ip_address' => Audit::getUserIP(),
            'event' => "Login Success",
            'is_success' => '1'
        ]);

        Log::channel('auth-success')->info($jsonLog);

        // Redirect to the dashboard
        return redirect('/dashboard');
    }


    public function resendToken()
    {
        LoginHelper::resendCode();
        Session::flash('alert-success', 'Code is sent again, please check your email or sms!');
        return redirect()->back();
    }
}

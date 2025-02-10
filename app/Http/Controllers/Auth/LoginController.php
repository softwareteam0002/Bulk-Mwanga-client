<?php

namespace App\Http\Controllers\Auth;

use App\Audit\Audit;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\HelperController;
use App\Http\Controllers\Mail\MailController;
use App\Http\Middleware\CheckSessionTimeout;
use App\Jobs\SendSmsJob;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;


    protected $maxAttempts = 5; //login attempts
    protected $decayMinutes = 60;  //lockout time

    public function username()
    {
        return 'username';
    }

    public function __construct()
    {
        $auth_methods = ['logout', 'lock', 'unlock', 'locked', 'hasMultipleSessions', 'logoutOtherSessions', 'logoutCurrentSession'];
        $this->middleware('guest')->except($auth_methods);
        $this->middleware('auth')->only($auth_methods);
    }

    public function redirectPath()
    {
        return redirect('dashboard');
    }

    public function showLoginForm()
    {
        return view('auth.login');

    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        //        $seconds  = $seconds
        throw ValidationException::withMessages([
            $this->username() => [
                Lang::get('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ])
            ],
        ])->status(Response::HTTP_TOO_MANY_REQUESTS);
    }

    //main function for login
    public function WebLogin(Request $request)
    {
        Log::error("Request Received!" . json_encode($request->all()));
        $validator = Validator::make($request->all(), [
            'username' => 'required|safe',
            'password' => 'required|safe',

        ]);
        Log::error("Debug: Validator!");
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $username = $request->username;
        $password = $request->password;

        Log::error("Debug: hasTooManyLoginAttempts!");

        //Handle: resetting account lockout counter to at least 30 minutes
        Log::error("Debug: reset_key!");
        $reset_key = $this->throttleKey($request) . ":reset_key";
        if ($this->limiter()->attempts($reset_key) == 0) {
            $this->limiter()->hit($reset_key, 60 * 30);
        } elseif ($this->limiter()->availableIn($reset_key) < 0) {
            $this->limiter()->clear($this->throttleKey($request));
            $this->limiter()->clear($reset_key);
        }

        Log::error("Debug: Select User!");
        $user = User::query()->where(['username' => $username, 'user_type' => 2])->first();
        Log::error("USER INFO REQUEST", ['MESSAGE' => $request->all(), 'USER' => $user]);


        if ($user) {
            Log::error("Debug: Check Password!");
            if (Hash::check($password, $user->password)) {
                Log::error("Debug: Check Status!");
                if ($user->is_active == 0) {

                    Session::flash('alert-warning', 'Invalid Access To The System. Contact Administrator');

                    Audit::saveActivityLogDb($username, $username, 'Login to the system', 'login_fail', 'login');
                    return redirect('/');
                }
                Log::error("Debug: Add session!");
                Session::put('username', $username);

                if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {

                    if (self::checkFirstLogin($user)) {

                        return redirect()->route('login.first');

                    }

                    if (Auth::user()->user_type == 2) {
                        if (HelperController::checkOrganizationStatus() == 0) {

                            Session::flash('alert-warning', 'Your Organization Is Not Activated, Contact Administrator');

                            Audit::saveActivityLogDb(Auth::user()->username, Auth::user()->username, 'Login while Organization Not Activated', 'login_fail', 'login');

                            $jsonLog = json_encode(['username' => $username, 'ip_address' => Audit::getUserIP(), 'event' => "Login Fail", 'is_success' => '0']);

                            Log::channel('auth-fail')->info($jsonLog);
                            Auth::logout();
                            return redirect()->back();
                        }
                    }
                    $token = HelperController::token();
                    Log::error("Debug: Generate token");
                    DB::table('users')->where('id', Auth::user()->id)->update(['token' => $token, 'token_verified' => 0]);

                    $userEmail = Auth::user()->email;
                    $sendBy = Auth::user()->send_by;
                    $phoneNumber = Auth::user()->phone_number;

                    if ($sendBy == 1) {
                        $message = "Your login token for Mwanga Hakika Disbursement Portal is: {$token}";
                        SendSmsJob::dispatch($message, $phoneNumber);
                    } else {
                        MailController::sendMail($userEmail, 1, $token);
                    }

                    Log::error("Debug: Redirect to verification!");
                    return redirect('verify-code-login');
                }

            }
        }

        Session::flash('alert-danger', 'Username or password is incorrect');
        $this->incrementLoginAttempts($request);

        Audit::saveActivityLogDb($username, $username, 'Login Fail', 'login_fail', 'login');

        $jsonLog = json_encode(['username' => $username, 'ip_address' => Audit::getUserIP(), 'event' => "Login Fail", 'is_success' => '0']);

        Log::channel('auth-fail')->info($jsonLog);

        return back();
    }

    //lock user
    public function lock()
    {
        session(['locked' => true, 'last-url' => url()->previous()]);
        return redirect('locked');
    }


    public function locked()
    {
        if (\session('locked', false) != true) {
            return $this->redirectPath();
        }
        return view('auth.locked')->with('flash', 'Account Locked!');
    }

    //unlock user
    public function unlock(Request $request)
    {
        $password = $request->post('password');
        if (Hash::check($password, Auth::user()->getAuthPassword())) {
            \session()->forget('locked');
            \session()->forget("last-url");
            return \session("last-url", false) ? redirect(\session("last-url")) : $this->redirectPath();
        } else {
            Audit::saveActivityLogDb(Auth::user()->username, Auth::user()->username, 'Login Fail', 'login_fail', 'login');

            $jsonLog = json_encode(['username' => Auth::user()->username, 'ip_address' => Audit::getUserIP(), 'event' => "Login Fail", 'is_success' => '0']);

            Log::channel('auth-fail')->info($jsonLog);

            Session::flash('alert-danger', 'Username or Password is incorrect');
            return redirect()->back();
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function hasMultipleSessions()
    {

        return view('auth.multiple_sessions');

    }


    /**
     * Logout other sessions, keeping the current one active
     * @return RedirectResponse|Redirector
     */
    public function logoutOtherSessions()
    {
        if (Cache::has(CheckSessionTimeout::getCacheSessionKey())) {
            Session::getHandler()->destroy(Cache::get(CheckSessionTimeout::getCacheSessionKey()));
            CheckSessionTimeout::setCurrentSessionAsActive();
        }
        return \session("last-url", false) ? redirect(\session("last-url")) : $this->redirectPath();
    }


    /**
     * Logout current session but do not reset token verification
     * @return RedirectResponse|Redirector
     */
    public function logoutCurrentSession()
    {
        Cache::forget(CheckSessionTimeout::getCacheSessionKey());
        Auth::logout();
        return redirect('/');
    }

    /**
     * Logout
     * @return RedirectResponse|Redirector
     */
    public function logout()
    {

        $username = Auth::user()->username;

        DB::table('users')->where('id', Auth::user()->id)->update(['token' => null, 'token_verified' => 0]);

        Cache::forget(CheckSessionTimeout::getCacheSessionKey());

        Audit::saveActivityLogDb($username, $username, 'Account Log off', 'success', 'logout');
        $jsonLog = json_encode(['username' => $username, 'ip_address' => Audit::getUserIP(), 'event' => "Logout", 'is_success' => '1']);

        Log::channel('auth-logoff')->info($jsonLog);

        Auth::logout();

        return redirect('/');
    }


    /**
     * @param $user
     * @return bool
     */
    public static function checkFirstLogin($user)
    {

        if ($user->is_first_login == 1) {

            return true;

        }

        return false;
    }
}

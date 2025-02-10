<?php

namespace App\Http\Controllers\User;

use App\Helper\SMSHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\HelperController;
use App\Http\Controllers\Helper\LoginHelper;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //

    public const VODACOM_USER = 1;

    public const SEND_BY_PHONE_NUMBER = 1;

    public function __construct()
    {
        $this->middleware(['auth', 'ctoken', 'orgapproved']);
    }

    public function index()
    {


        $users = DB::table('users')->where('user_type', '!=', 2)->get();

        return view('users.index', compact('users'));

    }

    /*return view to create user for vodacom side*/
    public function create()
    {

        $roles = Role::where('status', 1)->get();

        return view('users.create', compact('roles'));
    }
    /*save  user for vodacom side*/

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:2|safe:50',
            'lastName' => 'required|min:2|safe:50',
            'phoneNumber' => 'required|min:10|safe:50',
            'role' => 'required',
            'username' => 'required|safe:50',

        ]);

        if ($validator->fails()) {

            Session::flash('alert-warning', ' Please Fill All The Field(s)');

            return redirect()->back()->withInput();
        }

        $firstName = $request->firstName;
        $lastName = $request->lastName;
        $email = $request->email;
        $phoneNumber = $request->phoneNumber;
        $username = $request->username;

        $otp = $request->get('otp', self::SEND_BY_PHONE_NUMBER);

        $checkEmailExist = User::query()->where(['email' => $email])->first();

        //TODO LIST ENABLE ON PRODUCTION.
        if ($checkEmailExist) {
            Session::flash('alert-warning', 'Email Already Exists');
            return redirect()->back()->exceptInput();

        }
        $checkUsernameExist = User::query()->where(['username' => $username])->first();

        if ($checkUsernameExist) {
            Session::flash('alert-warning', 'Account With This Username Already Exists');
            return redirect()->back()->exceptInput();

        }
        //TODO LIST ENABLE ON PRODUCTION.

        $checkPhoneExist = User::query()->where(['phone_number' => $phoneNumber])->first();

        if ($checkPhoneExist) {

            Session::flash('alert-warning', 'Phone Number Already Exists');
            return redirect()->back()->withInput();

        }


        $role = $request->role;
        $password = HelperController::generatePasswod();
        $user = new User();

        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        $user->phone_number = $phoneNumber;
        $user->user_type = self::VODACOM_USER;
        $user->created_by = Auth::id();
        $user->send_by = $otp;
        $user->password = Hash::make((string) $password);

        $user->username = $username;

        DB::beginTransaction();

        $success = $user->save();
        if ($success) {
            foreach ($role as $key => $value) {
                $rolePermission = new UserRole();
                $rolePermission->role_id = $value;
                $rolePermission->user_id = $user->id;
                $rolePermission->save();
            }
            DB::commit();
            //if ($otp==self::SEND_BY_PHONE_NUMBER)

            //                SMSHelper::sendSingle($phoneNumber,"Your username for Vodacom Disbursement System is {$username}. Please use this username with the Password that we have sent to your email ({$email}) to access the system");

            SMSHelper::sendSingle($phoneNumber, "Your username for Vodacom Disbursement System is {$username}.  to access the system use this credential   {$password}");

            //MailHelper::sendMail('2',$password,$email);

            Session::flash('alert-success', $firstName . ' ' . $lastName . ' Successful Created');
        } else {
            DB::rollBack();
            Session::flash('alert-danger', 'Failed To Create  User');
        }

        return redirect('users');
    }


    //edit user

    public function edit($userId)
    {

        $user = User::where('id', $userId)->first();

        $userRoles = UserRole::where('user_id', $userId)->get();

        $roles = Role::where('status', 1)->get();

        return view('users.edit', compact('user', 'userId', 'userRoles', 'roles'));

    }

    //update user records
    public function update(Request $request, $userId)
    {

        $firstName = $request->firstName;
        $lastName = $request->lastName;
        $email = $request->email;
        $phoneNumber = $request->phoneNumber;
        $role = $request->role;
        $username = $request->username;

        $user = User::where('id', $userId)->first();

        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        $user->phone_number = $phoneNumber;
        $user->username = $username;

        $success = $user->save();

        if ($success) {

            UserRole::where('user_id', $userId)->delete();

            foreach ($role as $key => $value) {

                $rolePermission = new UserRole();

                $rolePermission->role_id = $value;
                $rolePermission->user_id = $user->id;

                $rolePermission->save();

            }

            Session::flash('alert-success', 'Successful Updated');

        } else {

            Session::flash('alert-danger', 'Failed To Update  User');

        }

        return redirect('users');

    }

    public function view($userId)
    {

        try {
            $userId = decrypt($userId);

        } catch (DecryptException $ed) {

            return back();
        }
        $user = User::where('id', $userId)->first();

        $userRoles = UserRole::query()->join('roles', 'roles.id', '=', 'user_roles.role_id')->where('user_id', $userId)->get();

        return view('users.view', compact('user', 'userId', 'userRoles'));

    }


    // user deactivation
    public function deactivate(Request $request)
    {

        $userId = $request->userId;

        $success = DB::table('users')->where('id', '=', $userId)->update(['is_active' => 0]);

        if ($success) {

            Session::flash('alert-success', 'User Deactivated');

        } else {

            Session::flash('alert-danger', 'Failed To Deactivated User');

        }

        return redirect()->back();


    }


    //activate user
    public function activate(Request $request)
    {

        $userId = $request->userId;

        $success = DB::table('users')->where('id', '=', $userId)->update(['is_active' => 1]);

        if ($success) {

            Session::flash('alert-success', 'User Activated');

        } else {

            Session::flash('alert-danger', 'Failed To Activat User');

        }

        return redirect()->back();

    }


    //function to resend code token
    public function resendPasswordToken(Request $request)
    {

        $userId = $request->userId;
        $phoneNumber = $request->phoneNumber;

        LoginHelper::resendPasswordToken($userId, $phoneNumber);

        Session::flash('alert-warning', 'Token Sent To ' . $phoneNumber);
        return redirect()->back();

    }
}

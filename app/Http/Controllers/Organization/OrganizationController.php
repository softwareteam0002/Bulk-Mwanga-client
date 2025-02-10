<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\HelperController;
use App\Http\Controllers\Helper\LoginHelper;
use App\Http\Controllers\Helper\MailHelper;
use App\Http\Controllers\Payment\PaymentController;
use App\Models\ConstantHelper;
use App\Models\Organization;
use App\Models\OrganizationApproval;
use App\Models\Region;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware(['auth', 'ctoken', 'orgapproved']);
    }


    public function create()
    {

        $regions = Region::all()->toArray();
        return view('organizations.create', compact('regions'));
    }


    public function view($organizationId)
    {

        $organization = Organization::with('district')->where('id', $organizationId)->first();
        return view("organizations.view", compact('organizationId', 'organization'));
    }

    public function users($organizationId = null)
    {
        $organizationId = Auth::user()->organization_id;
        $users = DB::table('users')->
            where([
                ['user_type', '=', 2],
                ['organization_id', '=', $organizationId]
            ])->latest()->get();
        $name = Organization::query()->where('id', $organizationId)->first();
        if (!$name) {

            $name = "";
        } else {
            $name = $name->name;

        }

        return view('organizations.users.index', compact('name', 'users', 'organizationId'));

    }

    public function createUsers($organizationId = null)
    {
        $organizationId = Auth::user()->organization_id;
        $users = DB::table('users')->
            where([
                ['user_type', '=', 2],
                ['organization_id', '=', $organizationId]
            ])->get();

        $roles = DB::table('roles')->
            where([
                ['status', '=', 1],
                ['role_type_id', '=', 2]  //2 for organization
            ])->get();

        $numberApproval = Organization::where('id', $organizationId)->first();

        if (!$numberApproval) {


            Session::flash('alert-warning', 'Complete Setup For Organization  eg Number Of Approval');

            return redirect()->back();
        }

        $numberApproval = $numberApproval->number_approval;

        return view('organizations.users.create', compact('numberApproval', 'roles', 'users', 'organizationId'));

    }

    public function storeUser(Request $request, $organizationId)
    {

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:2|safe',
            'lastName' => 'required|min:2|safe',
            'username' => 'required|safe',

            'phoneNumber' => 'required|min:10|safe',
            'role' => 'required',

        ]);

        if ($validator->fails()) {

            Session::flash('alert-warning', ' Please Fill All The Field(s)');

            return redirect()->back()->withInput();
        }


        $firstName = $request->firstName;
        $lastName = $request->lastName;
        $email = $request->email;
        $phoneNumber = $request->phoneNumber;
        $approvalNumber = $request->approvalNumber;

        $username = $request->username;

        $role = $request->role;

        if (empty($role)) {

            Session::flash('alert-warning', ' Please Fill All The Field(s)');

            return back()->withInput();
        }

        $password = HelperController::generatePasswod();

        $checkApprovalCount = OrganizationApproval::query()->where(['organization_id' => $organizationId])->count();

        $organizationCount = Organization::query()->where(['id' => $organizationId])->first();

        if ($approvalNumber != ConstantHelper::NOT_APPROVAL) {
            if (self::checkIfApprovalExceeds($organizationId)) {
                Session::flash('alert-danger', 'Number Of Maximum For Approval Reached.');


                return back()->withInput();
            }



        }

        $userCheck = User::query()->where(['username' => $username])->first();

        if ($userCheck) {

            Session::flash('alert-warning', 'Username Already Taken.');
            return redirect()->back()->withInput();

        }
        $userCheckEmail = User::query()->where(['email' => $email])->first();

        if ($userCheckEmail) {

            Session::flash('alert-warning', 'Email Already Taken.');
            return redirect()->back()->withInput();

        }

        $user = new User();
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        $user->phone_number = $phoneNumber;
        $user->user_type = 2;
        $user->created_by = Auth::id();
        $user->organization_id = $organizationId;
        $user->username = $username;
        $user->password = Hash::make($password);

        $success = $user->save();

        $userId = $user->id;
        if ($success) {

            foreach ($role as $key => $value) {

                $rolePermission = new UserRole();

                $rolePermission->role_id = $value;
                $rolePermission->user_id = $user->id;

                $rolePermission->save();

            }

            $orgApp = new OrganizationApproval();

            $orgApp->user_id = $userId;
            $orgApp->created_by = Auth::user()->id;
            $orgApp->organization_id = $organizationId;
            $orgApp->approval_level = $approvalNumber;
            $orgApp->save();

            if ($user->send_by == 2) {

                MailHelper::sendMail('2', $password, $email);

            } else {

                LoginHelper::resendPasswordToken($userId, $password);

            }

            Session::flash('alert-success', $firstName . ' ' . $lastName . ' Successful Created');

        } else {

            Session::flash('alert-danger', 'Failed To Create  User');

        }


        if (Auth::user()->user_type == 2) {

            return redirect('organization/users-all/');

        }

        return redirect('organization/users-all/' . $organizationId);

    }

    //TODO LIST ... INCLUDE ORGANIZATION ID

    public function userEdit($userId, $organizationId)
    {

        $user = User::where('id', $userId)->first();

        $userRoles = UserRole::where('user_id', $userId)->get();

        $roles = DB::table('roles')->
            where([
                ['status', '=', 1],
                ['role_type_id', '=', 2]  //2 for organization
            ])->get();

        return view('organizations.users.edit', compact('organizationId', 'user', 'userId', 'userRoles', 'roles'));

    }

    public function userUpdate(Request $request, $userId, $organizationId)
    {

        $firstName = $request->firstName;
        $lastName = $request->lastName;
        $email = $request->email;
        $phoneNumber = $request->phoneNumber;
        $username = $request->username;

        $role = $request->role;

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:2',
            'lastName' => 'required|min:2',
            'username' => 'required',

            'phoneNumber' => 'required|min:10',
            'role' => 'required',

        ]);

        if ($validator->fails()) {

            Session::flash('alert-warning', ' Please Fill All The Field(s)');

            return redirect()->back()->withInput();
        }


        if (empty($role)) {

            Session::flash('alert-warning', ' Please Fill All The Field(s)');

            return back()->withInput();
        }

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


        return redirect('organization/users-all/' . $organizationId);
    }


    //view organization user
    public function userView($userId)
    {

        try {
            $userId = decrypt($userId);

        } catch (DecryptException $ed) {

            return back();
        }
        $user = User::where('id', $userId)->first();

        $userRoles = UserRole::query()->join('roles', 'roles.id', '=', 'user_roles.role_id')->where('user_id', $userId)->get();

        return view('organizations.users.view', compact('user', 'userId', 'userRoles'));

    }

    //set number of approvals that will excute batch payments
    public function numberApprovals(Request $request)
    {

        $id = $request->id;
        $number = $request->number;

        $success = DB::table('organizations')->where('id', '=', $id)
            ->update(['number_approval' => $number]);

        if ($success) {

            return response()->json(['message' => "successful Updated", 'status' => 1]);
        } else {

            return response()->json(['message' => "Failed To Update", 'status' => 0]);

        }

        // return response()->json(['message'=>"General Failure",'status'=>1]);

    }

    public function detailsManagement()
    {


        if (HelperController::checkOrganizationStatus() === 0) {

            Session::flash('alert-warning', 'Your Organization Is Not Activated, Contact Administrator');

            return redirect('dashboard');
        }

        $organizationId = Auth::user()->organization_id;

        $organization = Organization::with('district')->where('id', $organizationId)->first();

        return view("organizations.view", compact('organizationId', 'organization'));

    }



    public static function resendPasswordToken($id, $email = null)
    {

        $password = HelperController::generatePasswod();

        User::query()->where(['id' => $id])->update(['password' => Hash::make($password)]);

        MailHelper::sendMail('2', $password, $email);

    }

    public static function checkIfApprovalExceeds($organizationId)
    {

        $data = OrganizationApproval::query()->where(['organization_id' => $organizationId])->get();

        if (count($data) === PaymentController::getNumberOfApprovalPerOrganization($organizationId)) {

            return true;

        }

        return false;

    }

}

<?php

namespace App\Http\Controllers\Helper;

use App\Audit\Audit;
use App\Http\Controllers\Controller;
use App\Models\ConstantHelper;
use App\Models\Organization;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DelegateController extends Controller
{

    /* 0 means that role is not active for a particular @user_id*/
    public  const ROLE_NOT_ACTIVE  =0;

    public  function index()
    {


        $users = null;

        if (Auth::user()->user_type == 1)

        {
            $users =User::query()->get();

        }


        else if(Auth::user()->user_type == 2) {

            $users =User::where(['user_type'=>2,'organization_id'=>Auth::user()->organization_id])->get();

            $withdrawStatus =   Organization::query()->where(['id'=>Auth::user()->organization_id])->first();


        }

        return view('delegate.index',compact('users','withdrawStatus'));
    }

    /* get roles to array*/
    public static function  getRoleValueArrayById($to){

        $dataArray  = array();
        $rolesTo = UserRole::query()->where(['user_id' => $to,'is_delegated'=>0])->get();

        foreach ($rolesTo as $role) {

            array_push($dataArray,$role->role_id);

        }

        return  $dataArray;
    }

    /* save roles */
    public  function  storeDelegate(Request $request)
    {

        $from = $request->from;
        $to = $request->to;

        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $rolesByUserId = UserRole::where(['user_id' => $from])->get();

        $availableRole =  self::getRoleValueArrayById($to);

        DB::beginTransaction();

        try{
            foreach ($rolesByUserId as $role) {
                $userRole = new UserRole();

                $roleId  = $role->role_id;

                if (!in_array($role->role_id,$availableRole)){

                    $userRole->user_id = $to;
                    $userRole->role_id = $roleId;
                    $userRole->is_delegated = 1;
                    $userRole->from = date('Y-m-d', strtotime($startDate));
                    $userRole->end = date('Y-m-d', strtotime($endDate));

                    $userRole->save();

                    //if they decide to disable then uncomment this.
//                    DB::table('user_roles')->where(['user_id'=>$from,'role_id'=>$roleId])
//                        ->update(['is_role_active'=>self::ROLE_NOT_ACTIVE]);

                }

            }

            DB::commit();

            $username  =  User::query()->where(['id'=>$from])->first();

            Audit::saveActivityLogDb(Auth::id(),$username->username,ConstantHelper::DELEGATION_DESCRIPOTION,ConstantHelper::UPDATING);
            Session::flash('alert-success','Successful Delegated');

        }

            //TODO LIST log database error.

        catch (\Exception $e) {
            DB::rollback();

            Session::flash('alert-warning','Failed To Delegate');

        }

        return redirect()->back();


    }


}

<?php

namespace App\Models;

use App\Helper\PermissionList;
use App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Permission extends Model
{

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role','role_permissions')->with('users');
    }

    /*check if user has permission to create payment of verification.*/
    public  static function checkCreatePayment()
    {

        return self::getPermission(PermissionList::CREATE_PAYMENT);

    }

    /*check if user has permission of maker.*/

    public  static function checkIfMaker(){

        return self::getPermission(PermissionList::USER_MANAGEMENT);
    }


    public  static function organizationReportOnly(){

        return self::getPermission( PermissionList::ORGANIZATION_REPORT_ONLY);

    }

    public  static function organizationALLReport(){

        return self::getPermission( PermissionList::VIEW_ALL_REPORT);

    }

    public  static function canCreateRole(){

        return self::getPermission( PermissionList::CREATE_ROLE);

    }

    public  static function canCreateWithdrawalFees(){

        return self::getPermission( PermissionList::CREATE_WITHDRAWAL_FEE);

    }

    public  static function canCreateUser(){

        return self::getPermission( PermissionList::CREATE_USER);

    }

    public  static function checkIfIsChecker(){

        return self::getPermission( PermissionList::IS_CHECKER);

    }
    public  static function canSetup(){

        return self::getPermission( PermissionList::DELEGATE);

    }

    public  static function createInitiator(){

        return self::getPermission( PermissionList::CREATE_INITIATOR);

    }


    public  static function canCreateOrganizationUser(){

        return self::getPermission( PermissionList::CREATE_ORGANIZATION_USER);

    }

    public  static function organizationChecker(){

        return self::getPermission( PermissionList::ORGANIZATION_CHECKER);

    }

    /*get permission by code.*/

    public  static  function getPermission($permissionCode){

        $user  = Models\User::with('roles')->where('id','=',Auth::user()->id)->first();

        $found  =  0;
        foreach ($user['roles'] as $perm){
            foreach ($perm['permissions'] as $permission){

                if ($permission->id ==$permissionCode){

                    $found = 1;
                }

            }
        }

        if ($found===1){

            return true;

        }

        return false;
    }

}

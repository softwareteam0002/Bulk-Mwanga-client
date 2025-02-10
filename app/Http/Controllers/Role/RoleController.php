<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth', 'ctoken', 'orgapproved']);
    }
    public function index()
    {
        $roles = DB::table('roles')->where(['status' => 1, 'role_type_id' => 2])->latest()->get();
        return view('roles.index', compact('roles'));

    }


    public function create()
    {


        $permissions = DB::table('permissions')->where(['type' => 2])->get();
        return view('roles.create', compact('permissions'));

    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|safe',
            'permission' => 'required'

        ]);


        if ($validator->fails()) {
            abort(404, 'Unauthorised Access');
            Session::flash('alert-warning', ' Please Fill All The Field(s)');

            return redirect()->back()->withInput();
        }


        $name = $request->name;

        $permission = $request->permission;

        $roleType = $request->get('roleType', 2);


        $role = new Role();

        $role->name = $name;
        $role->role_type_id = $roleType;  // 1 for vodacom , 2 for organization

        $success = $role->save();


        if ($success) {

            foreach ($permission as $key => $value) {

                $rolePermission = new RolePermission();

                $rolePermission->permission_id = $value;
                $rolePermission->role_id = $role->id;

                $rolePermission->save();

            }

            Session::flash('alert-success', ' Successful Created');

        } else {

            Session::flash('alert-danger', 'Failed to Create Role');

        }


        return redirect('roles');

    }

    public function edit($roleId)
    {

        $permissions = DB::table('permissions')->where(['type' => 2])->get();
        $rolePermissions = RolePermission::where('role_id', $roleId)->get();

        $role = Role::where('id', $roleId)->first();

        return view('roles.edit', compact('roleId', 'permissions', 'rolePermissions', 'role'));

    }

    public function update(Request $request, $roleId)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|safe',
            'permission' => 'required'

        ]);


        if ($validator->fails()) {

            Session::flash('alert-warning', ' Please Fill All The Field(s)');

            return redirect()->back()->withInput();
        }


        $name = $request->name;

        $permission = $request->permission;

        $role = Role::where('id', $roleId)->first();

        $role->name = $name;

        $success = $role->update();


        if ($success) {

            RolePermission::where('role_id', $roleId)->delete();

            foreach ($permission as $key => $value) {

                $rolePermission = new RolePermission();

                $rolePermission->permission_id = $value;
                $rolePermission->role_id = $roleId;

                $rolePermission->save();

            }

            Session::flash('alert-success', ' Successful Updated');

        } else {

            Session::flash('alert-danger', 'Failed To Update Role');

        }


        return redirect('roles');

    }

    public function view($roleId)
    {


        $role = Role::where('id', $roleId)->first();

        $permissions = DB::table('role_permissions as rp')
            ->join('permissions as p', 'p.id', '=', 'rp.permission_id')
            ->where('role_id', $roleId)->get();

        return view('roles.show', compact('roleId', 'role', 'permissions'));

    }


    public function delete(Request $request)
    {

        $roleId = $request->roleId;

        $success = DB::table('roles')->where('id', '=', $roleId)->update(['status' => 0]);

        if ($success) {

            Session::flash('alert-success', 'Role Deleted');

        } else {

            Session::flash('alert-danger', 'Failed to Create Role');

        }

        return redirect('roles');

    }
}

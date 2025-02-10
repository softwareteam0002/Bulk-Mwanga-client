<?php

namespace App\Http\Controllers\Initiator;

use App\Audit\Audit;
use App\Http\Controllers\Controller;
use App\Models\Initiator;
use App\Models\Organization;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class InitiatorController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'ctoken', 'orgapproved']);
    }

    public function index(Request $request)
    {

        $params = $request->all();

        if ($params['q'] == 'adin') {

            try {

                $id = decrypt($params['orid']);

                $initiator = DB::table('initiators')->where(['organization_id' => $id])->first();


                Audit::saveActivityLogDb(Auth::user()->username, '', "View Initiator ", "modify", 'success');

                return view('initiators.index', compact('initiator', 'id'));

            } catch (DecryptException $exception) {
                return back();

            }

        }

        return back();

    }

    /*return view to create user for vodacom side*/
    public function create(Request $request)
    {

        $params = $request->all();

        if ($params['q'] == 'adin') {

            try {

                $organizationId = decrypt($params['orid']);

                return view('initiators.create', compact('organizationId'));

            } catch (DecryptException $exception) {
                return back();

            }

        }

        return back();
    }

    public function edit(Request $request)
    {

        $params = $request->all();

        if ($params['q'] == 'upin') {

            try {

                $id = decrypt($params['inid']);



                $initiator = Initiator::query()->where(['id' => $id])->first();

                //return response()->json($initiator);

                return view('initiators.edit', compact('initiator'));

            } catch (DecryptException $exception) {
                return back();

            }

        }

        return back();
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'username' => 'required|min:2|safe:50',
            'password' => 'required|min:2|safe:50',

        ]);

        if ($validator->fails()) {

            Session::flash('alert-warning', ' Please Fill All The Field(s)');


            return redirect()->back()->withInput();
        }


        try {

            $organizationId = decrypt($request->organizationId);

            $username = $request->username;

            $password = encrypt($request->password);

            $initiator = new Initiator();

            $initiator->username = $username;
            $initiator->password = $password;
            $initiator->organization_id = $organizationId;
            $initiator->created_by = Auth::user()->username;

            $success = $initiator->save();

            if ($success) {


                $organization = Organization::query()->where(['id' => $organizationId])->first();

                Audit::saveActivityLogDb(Auth::user()->username, $organization->name, "Creating Initiator For Organization", "modify", 'success');

                Session::flash('alert-success', 'Successful Created');

                return redirect()->route('organization-initiator', ['q' => 'adin', 'orid' => encrypt($organizationId)]);

            } else {

                Session::flash('alert-danger', 'Failed To Create Initiator');

                return redirect()->back();

            }

        } catch (DecryptException $exception) {
            return back();

        }
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'username' => 'required|min:2|safe:50',
            'password' => 'required|min:2|safe:50',

        ]);

        if ($validator->fails()) {

            Session::flash('alert-warning', ' Please Fill All The Field(s)');

            return redirect()->back()->withInput();
        }

        try {

            $username = $request->username;
            $password = $request->password;

            $initiator = Initiator::query()->where(['id' => $id])->first();

            $organizationId = decrypt($request->organizationId);

            $initiator->username = $username;
            $initiator->password = encrypt($password);

            $initiator->updated_by = Auth::user()->username;

            $success = $initiator->save();

            if ($success) {

                $organization = Organization::query()->where(['id' => $organizationId])->first();

                Audit::saveActivityLogDb(Auth::user()->username, $organization->name, "Update Initiator For Organization", "modify", 'success');

                Session::flash('alert-success', 'Successful Updated');

                return redirect()->route('organization-initiator', ['q' => 'adin', 'orid' => encrypt($organizationId)]);

            } else {

                Session::flash('alert-danger', 'Failed To Create Initiator');

                return redirect()->back();

            }

        } catch (DecryptException $exception) {
            return back();

        }

    }

}

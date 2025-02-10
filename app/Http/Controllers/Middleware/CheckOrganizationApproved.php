<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckOrganizationApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $id  =  Auth::user()->organization_id;

        $check  =  Organization::where(['id'=>$id])->first();

        if ($check){

            if ($check->status===0){

                Auth::logout();
                Session::flash('alert-warning','Your Organization Is Not Activated, Contact Administrator');
                return redirect('/');

            }


            return $next($request);

        }

        return $next($request);


    }
}

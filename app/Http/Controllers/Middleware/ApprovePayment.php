<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ApprovePayment
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

        $user  = \App\Models\User::with('roles')->where('id','=',Auth::user()->id)->first();

        $found  =  0;
        foreach ($user['roles'] as $perm){
            foreach ($perm['permissions'] as $permission){
                if ($permission->id =='1100'){

                 $found = 1;
                }

            }
        }

        if ($found===1){

            return $next($request);

        }

        Session::flash('alert-danger','You Are Not Allowed To Handle This Request, Contact The Administrator.');
        return redirect()->back();
    }
}

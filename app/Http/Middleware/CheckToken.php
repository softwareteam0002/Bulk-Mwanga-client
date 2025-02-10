<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckToken
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

        $id  =  Auth::user()->id;

       $check  =  User::where(['id'=>$id])->first();

       if ($check){

           if ($check->token_verified===0){


               return redirect('/verify-code-login');

           }


           return $next($request);

       }

        DB::table('users')->where('id',Auth::user()->id)->update(['token'=>null,'token_verified'=>0]);

        Auth::logout();
        return redirect('/');

    }
}

<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckApproval
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
        $org = Organization::query()->select('number_approval')->where(['id' => Auth::user()->organization_id])->first();
        if ($org->number_approval>=1){
            return $next($request);
        }
        Session::flash('alert-danger','Your organization does not have approval level,please contact administrator ');
        return  back();

    }
}

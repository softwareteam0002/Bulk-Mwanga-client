<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Helper\HelperController;
use Closure;
use Illuminate\Support\Facades\Session;

class HasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$permissionId)
    {

        if (HelperController::checkIfHasPermission($permissionId)=='true'){

            return $next($request);

        }

        Session::flash('alert-danger','You Are Not Allowed To Handle This Request, Contact The Administrator.');
        return back();
    }

}

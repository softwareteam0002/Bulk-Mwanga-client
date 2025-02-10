<?php

namespace App\Http\Middleware;

use App\Models\PasswordHistory;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class VerifyPasswordExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $history = PasswordHistory::query()
            ->where(['user_id'=> Auth::id(),'status'=>'ACTIVE'])
            ->first();

        if (empty($history)){
            Session::flash('alert-warning', 'You are required to change your password!');
            return redirect('/change-password');
        }else if (time() - strtotime($history->created_at)>(90*24*60*60)){
            Session::flash('alert-warning', 'Your Password has Expired, Please Change It!');
            return redirect('/change-password');
        } else if (time() - strtotime($history->created_at)>(85*24*60*60)){
            $days = 90 - ((time() - strtotime($history->created_at))/(24*60*60));
            Session::put(['pw-remaining-days'=>floor($days), 'pw-show-reminder'=>true]);
        }
        return $next($request);
    }
}

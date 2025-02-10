<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CheckSessionTimeout
{

    const CACHE_SESSION_KEY_PREFIX = 'session_id-';
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        header('Referrer-Policy: no-referrer');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('X-Frame-Options: DENY');
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');

        if(session('locked')) {
            return redirect('locked');
        }

        //check this user has other active session IDs
        $session_id = Cache::get(self::getCacheSessionKey(),null);
        if (!empty($session_id)){
            if ($session_id != session()->getId()){
                session(['last-url' => url()->current()]);
                return redirect('multiple-sessions');
            }
        }else{
            self::setCurrentSessionAsActive();
        }

        return $next($request);
    }


    public static function setCurrentSessionAsActive(){
        Cache::put(self::getCacheSessionKey(),session()->getId(),Carbon::now()->addMinutes(20));
    }

    public static function getCacheSessionKey(){
       return self::CACHE_SESSION_KEY_PREFIX.\Illuminate\Support\Facades\Auth::user()->id;
    }
}

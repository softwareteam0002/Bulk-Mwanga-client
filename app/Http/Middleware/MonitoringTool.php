<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class MonitoringTool
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
        if ($request->has('username') && $request->has('password')) {

            if ($request->username != "MonitoringTool" || $request->password != "AoPCbmlU1YS4pLdqmp90DFum05tm9Xa7HbYD5hFDSlN6rRVlyJAvnLnAQF55Nx9m") {
                Log::error('------------Failure to Authenticate Request From Monitoring Tool---------');
                Log::error('IP: ' . $request->ip());
                Log::error('Request: ' . json_encode($request->all()));

                return response()->json(['code' => 401, 'status' => 'failed', 'message' => 'Unauthenticated API Request']);
            }

            return $next($request);
        }else{
            return response()->json(['code' => 401, 'status' => 'failed', 'message' => 'Unauthenticated API Request']);
        }
    }
}
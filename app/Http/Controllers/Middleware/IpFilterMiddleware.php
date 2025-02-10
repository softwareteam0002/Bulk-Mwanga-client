<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class IpFilterMiddleware
{

    private $allowed_ips = [
        '::1',
        '127.0.0.1',
        '10.10.65.45',
        '10.10.65.46',
        '10.8.19.50',
        '10.8.19.51',
        '172.18.65.0/24'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!IpUtils::checkIp($request->ip(),$this->allowed_ips)) {
            return response("Forbidden",403);
        }
        return $next($request);
    }
}

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
        '172.18.65.0/24',
		'10.154.0.0/0',
		'172.18.65.237',
		'172.18.65.148',
		'172.18.65.106',
		'172.18.65.235',
		'10.206.141.6',
		'10.154.11.61',
        '10.154.11.62',
        '10.154.11.63',
        '10.154.11.64',
        '172.18.69.61',
        '172.18.69.62',
        '172.18.69.63',
        '172.18.69.64',
        '10.152.0.15',
        '172.18.65.113',
        //simulated ip
        '172.24.30.252'
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

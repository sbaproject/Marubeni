<?php

namespace App\Http\Middleware;

use App\Libs\Common;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CheckIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // SmartPhone do not need check network
        if (Common::detectMobile()){
            return $next($request);
        }

        $serverIp = $request->server('SERVER_ADDR');
        $remoteIp = $request->server('REMOTE_ADDR');

        // src located in same network LAN
        if ($serverIp == $remoteIp) {
            return $next($request);
        }

        if ($serverIp == "::1" || $serverIp == "127.0.0.1") {
            return $next($request);
        }

        // check same [CLASS network]
        if (substr($serverIp, 0, strripos($serverIp, '.')) == substr($remoteIp, 0, strripos($remoteIp, '.'))) {
            return $next($request);
        } else {
            foreach (config('const.available_class_network') as $value) {
                if (substr($remoteIp, 0, strripos($remoteIp, '.')) == $value){
                    return $next($request);
                }
            }
        }

        // src located in server (server & client ip are different)
        // need to assign [IP_INTERNAL] env to determine available IP (temp called is LAN)
        if ($remoteIp == env('IP_INTERNAL')) {
            return $next($request);
        }

        // exception ip to access
        if (in_array($remoteIp, config('const.available_ip'))) {
            return $next($request);
        }

        // external network
        abort(404);
    }
}

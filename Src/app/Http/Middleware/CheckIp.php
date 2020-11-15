<?php

namespace App\Http\Middleware;

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
        //return $next($request);

        if ($request->ip() == env('IP_CHECK_EXTERNAL')) {

            //Network -> INTERNAL
            return $next($request);
        } else {

            //Network -> EXTERNAL
            //Get cookie
            $confirm = $request->cookie('confirm');

            //Check
            if (Hash::check(Auth::user()->otp_token, $confirm)) {
                return $next($request);
            } else {
                return redirect()->route('checkip');
            }            
        }
    }
}

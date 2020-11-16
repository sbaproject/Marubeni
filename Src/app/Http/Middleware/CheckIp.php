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
        if ($request->ip() == env('IP_INTERNAL')) {

            //Network -> INTERNAL
            return $next($request);
        } else {

            //Network -> EXTERNAL
            //Check
            if (Hash::check($request->cookie('confirm'), Auth::user()->otp_token)) {
                return $next($request);
            } else {
                return redirect()->route('checkip');
            }
        }
    }
}

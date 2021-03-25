<?php

namespace App\Http\Middleware;

use Closure;
use App\Libs\Common;
use Illuminate\Http\Request;

class TrustDesktop
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
        // allows only desktop client
        if(Common::detectMobile()){
            abort(403, __('msg.page_error.only_desktop'));
        }
        return $next($request);
    }
}

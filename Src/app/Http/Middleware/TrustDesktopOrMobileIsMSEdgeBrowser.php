<?php

namespace App\Http\Middleware;

use Closure;
use App\Libs\Common;
use Illuminate\Http\Request;

class TrustDesktopOrMobileIsMSEdgeBrowser
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
        // allow desktop or mobile with Edge browser.
        if(Common::detectMobile() && !Common::detectEdgeBrowser()){
            abort(404);
        }
        return $next($request);
    }
}

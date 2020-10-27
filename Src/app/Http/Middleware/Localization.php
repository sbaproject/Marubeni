<?php

namespace App\Http\Middleware;

use Closure;
use App\Libs\Common;
use Illuminate\Http\Request;

class Localization
{
    /**
     * Set locale by selected locale of user
     */
    public function handle(Request $request, Closure $next)
    {
        Common::setLocale();

        return $next($request);
    }
}

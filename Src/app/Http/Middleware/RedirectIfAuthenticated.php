<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Providers\RouteServiceProvider;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // check authenticated
        if (Auth::check()) {
            // for admin
            if(Gate::allows('admin-gate')){
                return redirect()->route('admin.dashboard');
            }
            // for user
            return redirect()->route('user.dashboard');
        }

        return $next($request);
    }
}

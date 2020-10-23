<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // not logged yet
        if(!Auth::check()){
            return redirect()->route('login');
        }

        // get logged user
        $user = Auth::user();
        
        // only admin user able to access to admin page
        if ($role == ROLE['Admin'] && $user->role === ROLE['Admin']) {
            return $next($request);
        } else {
            return redirect()->route('user.dashboard');
        }

        return redirect()->route('login');
    }
}

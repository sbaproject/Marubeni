<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**---------------------------------------------/
         * Define Gates
         *---------------------------------------------*/
        // Just only Admin role to access to ADMIN routes
        Gate::define('admin-gate',function($user){
            if(!Auth::check()){
                return false;
            }
            return $user->role === config('const.role.Admin');
        });
        // Just only NOT Admin role to access to USER routes
        Gate::define('user-gate', function ($user) {
            if (!Auth::check()) {
                return false;
            }
            return $user->role !== config('const.role.Admin');
        });
    }
}

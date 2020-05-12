<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerRole();
    }

    public function registerRole()
    {
        Gate::define('is-admin', function($user){
            return $user->Role && $user->Role->name == "Administrator";
        });

        Gate::define('is-user', function($user){
            return $user->Role && $user->Role->name != "Administrator";
        });
    }
}

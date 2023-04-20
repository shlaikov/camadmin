<?php

namespace App\Providers;

use App\Http\SSO\Keycloak\WebGuard;
use App\Models\Team;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string,class-string>
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (config('keycloak-web.client_secret')) {
            $callback = fn ($user, $roles, $resource = '') => $user->hasRole($roles, $resource) ?: null;
            Gate::define('keycloak-web', $callback);
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if (config('keycloak-web.client_secret')) {
            Auth::extend('keycloak-web', function ($app, $name, array $config) {
                $provider = Auth::createUserProvider($config['provider']);

                return new WebGuard($provider, $app->request);
            });
        }
    }
}

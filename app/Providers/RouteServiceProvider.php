<?php

namespace App\Providers;

use App\Http\SSO\Keycloak\AuthController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            if (!config('keycloak-web.client_secret')) {
                return;
            }

            $defaults = [
                'login' => 'login',
                'logout' => 'logout',
                'register' => 'register',
                'callback' => 'callback',
            ];

            $routes = config('keycloak-web.routes', []);
            $routes = array_merge($defaults, $routes);
            $router = $this->app->make('router');

            if (!empty($routes['login'])) {
                $router->middleware('web')->get($routes['login'], [AuthController::class, 'login'])->name('keycloak.login');
            }

            if (!empty($routes['logout'])) {
                $router->middleware('web')->get($routes['logout'], [AuthController::class, 'logout'])->name('keycloak.logout');
            }

            if (!empty($routes['register'])) {
                $router->middleware('web')->get($routes['register'], [AuthController::class, 'register'])->name('keycloak.register');
            }

            if (!empty($routes['callback'])) {
                $router->middleware('web')->get($routes['callback'], [AuthController::class, 'callback'])->name('keycloak.callback');
            }
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}

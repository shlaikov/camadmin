<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     * 
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (config('keycloak-web.client_secret')) {
            return route('keycloak.login');
        }

        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}

<?php

namespace App\Http\SSO\Keycloak;

use App\Repository\Services\Keycloak\KeycloakService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        protected KeycloakService $keycloack
    ) {
    }

    public function login(): RedirectResponse
    {
        $url = $this->keycloack->getLoginUrl();
        $this->keycloack->saveState();

        return redirect($url);
    }

    public function logout(): RedirectResponse
    {
        $url = $this->keycloack->getLogoutUrl();
        $this->keycloack->forgetToken();

        return redirect($url);
    }

    public function register(): RedirectResponse
    {
        return redirect($this->keycloack->getRegisterUrl());
    }

    public function callback(Request $request): RedirectResponse
    {
        if (!empty($request->input('error'))) {
            $error = $request->input('error_description');
            $error = ($error) ?: $request->input('error');

            throw new \RuntimeException($error);
        }

        $state = $request->input('state');
        if (empty($state) || !$this->keycloack->validateState($state)) {
            $this->keycloack->forgetState();

            throw new \RuntimeException('Invalid state');
        }

        if (!empty($code = $request->input('code'))) {
            $token = $this->keycloack->getAccessToken($code);

            if (Auth::validate($token)) {
                $url = config('keycloak-web.redirect_url');

                return redirect()->intended($url);
            }
        }

        return redirect(route('keycloak.login'));
    }
}

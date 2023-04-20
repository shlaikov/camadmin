<?php

namespace App\Http\SSO\Keycloak;

use App\Models\User;
use App\Repository\Services\Keycloak\KeycloakAccessToken;
use App\Repository\Services\Keycloak\KeycloakService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WebGuard implements StatefulGuard
{
    /**
     * @var Authenticatable|null
     */
    protected ?Authenticatable $user;
    /**
     * @var KeycloakService
     */
    protected KeycloakService $keycloakService;

    public function __construct(
        protected UserProvider $provider,
        protected Request $request
    ) {
        $this->keycloakService = new KeycloakService;
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array  $credentials
     * @param  bool  $remember
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false)
    {
        return false;
    }

    public function authenticate(): bool
    {
        if (empty($credentials = $this->keycloakService->retrieveToken())) {
            return false;
        }

        if (empty($user = $this->keycloakService->getUserProfile($credentials))) {
            $this->keycloakService->forgetToken();

            if (config('app.debug', false)) {
                throw new \Exception('User cannot be authenticated.');
            }

            return false;
        }


        $this->setUser($user);

        return true;
    }

    /**
     * Log a user into the application without sessions or cookies.
     *
     * @param  array $credentials
     * @return bool
     */
    public function once(array $credentials = []): bool
    {
        return false;
    }

    /**
     * Log the given user ID into the application without sessions or cookies.
     *
     * @param mixed $id
     * @return Authenticatable|bool
     */
    public function onceUsingId(mixed $id): Authenticatable|bool
    {
        return false;
    }

    /**
     * Log a user into the application.
     *
     * @param Authenticatable $user
     * @param bool $remember
     * @return RedirectResponse
     */
    public function login(Authenticatable $user, $remember = false): RedirectResponse
    {
        $url = $this->keycloakService->getLoginUrl();
        $this->keycloakService->saveState();

        return redirect($url);
    }

    /**
     * Validate a user's credentials.
     *
     * @param array $credentials
     * @return bool
     */
    public function validate(array $credentials = []): bool
    {
        if (empty($credentials['access_token']) || empty($credentials['id_token'])) {
            return false;
        }

        $credentials['refresh_token'] = $credentials['refresh_token'] ?? '';
        $this->keycloakService->saveToken($credentials);

        return $this->authenticate();
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|string|null
     */
    public function id(): int|string|null
    {
        $user = $this->user();

        return $user->id ?? null;
    }

    public function check(): bool
    {
        return (bool) $this->user();
    }

    public function hasUser(): bool
    {
        return (bool) $this->user();
    }

    /**
     * Set the current user.
     *
     * @param Authenticatable $user
     * 
     * @return void
     */
    public function setUser(?Authenticatable $user): void
    {
        $this->user = $user;
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest(): bool
    {
        return !$this->check();
    }

    /**
     * Get the currently authenticated user.
     *
     * @return Authenticatable|null
     */
    public function user(): ?Authenticatable
    {
        if (empty($this->user) && !$this->authenticate()) {
            return null;
        }

        return $this->user;
    }

    /**
     * Check user is authenticated and return his resource roles
     *
     * @param string $resource Default is empty: point to client_id
     * @return array|bool
     */
    public function roles($resource = ''): array|bool
    {
        if (empty($resource)) {
            $resource = config('keycloak-web.client_id');
        }

        if (!$this->check()) {
            return false;
        }

        $token = $this->keycloakService->retrieveToken();

        if (empty($token) || empty($token['access_token'])) {
            return false;
        }

        $token = new KeycloakAccessToken($token);
        $token = $token->parseAccessToken();

        $resourceRoles = $token['resource_access'] ?? [];
        $resourceRoles = $resourceRoles[$resource] ?? [];
        $resourceRoles = $resourceRoles['roles'] ?? [];

        return $resourceRoles;
    }

    /**
     * Log the given user ID into the application.
     *
     * @param mixed $id
     * @param bool $remember
     * @return bool
     */
    public function loginUsingId($id, $remember = false): bool
    {
        return false;
    }

    /**
     * Determine if the user was authenticated via "remember me" cookie.
     *
     * @return bool
     */
    public function viaRemember(): bool
    {
        return false;
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout(): void
    {
        $this->keycloakService->forgetState();
        $this->keycloakService->forgetToken();
    }
}

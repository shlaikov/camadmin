<?php

namespace App\Repository\Services\Keycloak;

use App\Models\User;
use App\Models\Team;
use App\Repository\Services\Keycloak\KeycloakAccessToken;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Auth\Authenticatable;
use Exception;

class KeycloakService
{
    const KEYCLOAK_SESSION = '_keycloak_token';
    const KEYCLOAK_SESSION_STATE = '_keycloak_state';

    /**
     * Keycloak URL
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Keycloak Realm
     *
     * @var string
     */
    protected $realm;

    /**
     * Keycloak Client ID
     *
     * @var string
     */
    protected $clientId;

    /**
     * Keycloak Client Secret
     *
     * @var string
     */
    protected $clientSecret;

    /**
     * Keycloak OpenId Configuration
     *
     * @var array
     */
    protected $openid;

    /**
     * Keycloak OpenId Cache Configuration
     *
     * @var array
     */
    protected $cacheOpenid;

    /**
     * CallbackUrl
     *
     * @var string
     */
    protected $callbackUrl;

    /**
     * RedirectLogout
     *
     * @var string
     */
    protected $redirectLogout;

    /**
     * The state for authorization request
     *
     * @var string
     */
    protected $state;

    /**
     * The HTTP Client
     *
     * @var Client
     */
    protected $httpClient;

    public function __construct()
    {
        if (empty($this->baseUrl)) {
            $this->baseUrl = trim(config('keycloak-web.base_url'), '/');
        }

        if (empty($this->realm)) {
            $this->realm = config('keycloak-web.realm');
        }

        if (empty($this->clientId)) {
            $this->clientId = config('keycloak-web.client_id');
        }

        if (empty($this->clientSecret)) {
            $this->clientSecret = config('keycloak-web.client_secret');
        }

        if (empty($this->cacheOpenid)) {
            $this->cacheOpenid = config('keycloak-web.cache_openid', false);
        }

        if (empty($this->callbackUrl)) {
            $this->callbackUrl = route('keycloak.callback');
        }

        if (empty($this->redirectLogout)) {
            $this->redirectLogout = config('keycloak-web.redirect_logout');
        }

        $this->state = $this->generateRandomState();
        $this->httpClient = new Client();
    }

    public function getLoginUrl(): string
    {
        $url = $this->getOpenIdValue('authorization_endpoint');
        $params = [
            'scope' => 'openid',
            'response_type' => 'code',
            'client_id' => $this->getClientId(),
            'redirect_uri' => $this->callbackUrl,
            'state' => $this->getState(),
        ];

        return $this->buildUrl($url, $params);
    }

    public function getLogoutUrl(): string
    {
        $url = $this->getOpenIdValue('end_session_endpoint');

        if (empty($this->redirectLogout)) {
            $this->redirectLogout = url('/');
        }

        $params = [
            'client_id' => $this->getClientId()
        ];
        $token = $this->retrieveToken();
        if (!empty($token['id_token'])) {
            $params['post_logout_redirect_uri'] = $this->redirectLogout;
            $params['id_token_hint'] = $token['id_token'];
        }

        return $this->buildUrl($url, $params);
    }

    public function getRegisterUrl(): string
    {
        return str_replace('/auth?', '/registrations?', $this->getLoginUrl());
    }

    public function getAccessToken(string $code): array
    {
        $url = $this->getOpenIdValue('token_endpoint');

        $params = [
            'code' => $code,
            'client_id' => $this->getClientId(),
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->callbackUrl,
        ];

        if (!empty($this->clientSecret)) {
            $params['client_secret'] = $this->clientSecret;
        }

        $token = [];

        try {
            $response = $this->httpClient->request('POST', $url, ['form_params' => $params]);

            if ($response->getStatusCode() === 200) {
                $token = $response->getBody()->getContents();
                $token = json_decode($token, true);
            }
        } catch (GuzzleException $e) {
            $this->logException($e);
        }

        return $token;
    }

    public function refreshAccessToken(array $credentials): array
    {
        if (empty($credentials['refresh_token'])) {
            return [];
        }

        $url = $this->getOpenIdValue('token_endpoint');
        $params = [
            'client_id' => $this->getClientId(),
            'grant_type' => 'refresh_token',
            'refresh_token' => $credentials['refresh_token'],
            'redirect_uri' => $this->callbackUrl,
        ];

        if (!empty($this->clientSecret)) {
            $params['client_secret'] = $this->clientSecret;
        }

        $token = [];

        try {
            $response = $this->httpClient->request('POST', $url, ['form_params' => $params]);
            if ($response->getStatusCode() === 200) {
                $token = $response->getBody()->getContents();
                $token = json_decode($token, true);
            }
        } catch (GuzzleException $e) {
            $this->logException($e);
        }

        return $token;
    }

    public function invalidateRefreshToken(string $refreshToken): bool
    {
        $url = $this->getOpenIdValue('end_session_endpoint');
        $params = [
            'client_id' => $this->getClientId(),
            'refresh_token' => $refreshToken,
        ];

        if (!empty($this->clientSecret)) {
            $params['client_secret'] = $this->clientSecret;
        }

        try {
            $response = $this->httpClient->request('POST', $url, ['form_params' => $params]);
            return $response->getStatusCode() === 204;
        } catch (GuzzleException $e) {
            $this->logException($e);
        }

        return false;
    }

    public function getUserProfile(array $credentials): ?Authenticatable
    {
        $credentials = $this->refreshTokenIfNeeded($credentials);

        $user = [];

        try {
            $token = new KeycloakAccessToken($credentials);
            if (empty($token->getAccessToken())) {
                throw new Exception('Access Token is invalid.');
            }

            $claims = array(
                'aud' => $this->getClientId(),
                'iss' => $this->getOpenIdValue('issuer'),
            );

            $token->validateIdToken($claims);
            $url = $this->getOpenIdValue('userinfo_endpoint');
            $headers = [
                'Authorization' => 'Bearer ' . $token->getAccessToken(),
                'Accept' => 'application/json',
            ];

            $response = $this->httpClient->request('GET', $url, ['headers' => $headers]);

            if ($response->getStatusCode() !== 200) {
                throw new Exception('Was not able to get userinfo (not 200)');
            }

            $user = $response->getBody()->getContents();
            $user = json_decode($user, true);

            $token->validateSub($user['sub'] ?? '');
        } catch (GuzzleException $e) {
            $this->logException($e);
        } catch (Exception $e) {
            Log::error('[Keycloak Service] ' . print_r($e->getMessage(), true));
        }

        return $this->getOrCreateProfile($user);
    }

    protected function getOrCreateProfile(array $credentials): Authenticatable
    {
        return \DB::transaction(function () use ($credentials) {
            $user = User::firstOrCreate([
                'email' => $credentials['email'],
                'external_uuid' => $credentials['sub']
            ]);

            if ($user->wasRecentlyCreated) {
                $user->ownedTeams()->save(Team::forceCreate([
                    'user_id' => $user->id,
                    'name' => env('KEYCLOAK_REALM', 'KEYCLOAK') . "'s Team",
                    'personal_team' => true,
                ]));
            }

            if ($credentials['preferred_username'] !== $user->name) {
                $user->name = $credentials['preferred_username'];
                $user->save();
            }

            return $user;
        });
    }

    public function retrieveToken(): ?array
    {
        return session()->get(self::KEYCLOAK_SESSION);
    }

    public function saveToken(array $credentials): void
    {
        session()->put(self::KEYCLOAK_SESSION, $credentials);
        session()->save();
    }

    public function forgetToken(): void
    {
        session()->forget(self::KEYCLOAK_SESSION);
        session()->save();
    }

    public function validateState(mixed $state): bool
    {
        $challenge = session()->get(self::KEYCLOAK_SESSION_STATE);

        return (!empty($state) && !empty($challenge) && $challenge === $state);
    }

    public function saveState(): void
    {
        session()->put(self::KEYCLOAK_SESSION_STATE, $this->state);
        session()->save();
    }

    public function forgetState(): void
    {
        session()->forget(self::KEYCLOAK_SESSION_STATE);
        session()->save();
    }

    public function buildUrl(string $url, array $params): string
    {
        $parsedUrl = parse_url($url);
        if (empty($parsedUrl['host'])) {
            return trim($url, '?') . '?' . Arr::query($params);
        }

        if (!empty($parsedUrl['port'])) {
            $parsedUrl['host'] .= ':' . $parsedUrl['port'];
        }

        $parsedUrl['scheme'] = (empty($parsedUrl['scheme'])) ? 'https' : $parsedUrl['scheme'];
        $parsedUrl['path'] = (empty($parsedUrl['path'])) ? '' : $parsedUrl['path'];

        $url = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'];
        $query = [];

        if (!empty($parsedUrl['query'])) {
            $parsedUrl['query'] = explode('&', $parsedUrl['query']);

            foreach ($parsedUrl['query'] as $value) {
                $value = explode('=', $value);

                if (count($value) < 2) {
                    continue;
                }

                $key = array_shift($value);
                $value = implode('=', $value);

                $query[$key] = urldecode($value);
            }
        }

        $query = array_merge($query, $params);

        return $url . '?' . Arr::query($query);
    }

    protected function getClientId(): ?string
    {
        return $this->clientId;
    }

    protected function getState(): ?string
    {
        return $this->state;
    }

    protected function getOpenIdValue(string $key): mixed
    {
        if (!$this->openid) {
            $this->openid = $this->getOpenIdConfiguration();
        }

        return Arr::get($this->openid, $key);
    }

    protected function getOpenIdConfiguration(): array
    {
        $cacheKey = 'keycloak_web_guard_openid-' . $this->realm . '-' . md5($this->baseUrl);

        if ($this->cacheOpenid) {
            $configuration = Cache::get($cacheKey, null);

            if (!empty($configuration)) {
                return $configuration;
            }
        }

        $url = $this->baseUrl . '/realms/' . $this->realm;
        $url = $url . '/.well-known/openid-configuration';

        $configuration = [];

        try {
            $response = $this->httpClient->request('GET', $url);

            if ($response->getStatusCode() === 200) {
                $configuration = $response->getBody()->getContents();
                $configuration = json_decode($configuration, true);
            }
        } catch (GuzzleException $e) {
            $this->logException($e);

            throw new Exception('[Keycloak Error] It was not possible to load OpenId configuration: ' . $e->getMessage());
        }

        if ($this->cacheOpenid) {
            Cache::put($cacheKey, $configuration);
        }

        return $configuration;
    }

    protected function refreshTokenIfNeeded(array $credentials): array
    {
        if (!is_array($credentials) || empty($credentials['access_token']) || empty($credentials['refresh_token'])) {
            return $credentials;
        }

        $token = new KeycloakAccessToken($credentials);
        if (!$token->hasExpired()) {
            return $credentials;
        }

        $credentials = $this->refreshAccessToken($credentials);

        if (empty($credentials['access_token'])) {
            $this->forgetToken();
            return [];
        }

        $this->saveToken($credentials);
        return $credentials;
    }

    protected function logException(GuzzleException $e): void
    {
        // Guzzle 7
        if (!method_exists($e, 'getResponse')) {
            Log::error('[Keycloak Service] ' . $e->getMessage());
            return;
        }

        $error = [
            'request' => method_exists($e, 'getRequest') ? $e : '',
            'message' => $e->getMessage(),
        ];

        Log::error('[Keycloak Service] ' . print_r($error, true));
    }

    protected function generateRandomState(): string
    {
        return bin2hex(random_bytes(16));
    }
}

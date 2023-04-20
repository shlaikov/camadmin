<?php

namespace App\Http\SSO;

use App\Exceptions\MissingConfigurationException;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use GuzzleHttp;
use RuntimeException;

class SSOBrokerController extends SSOBroker
{
    /**
     * Generate request url.
     *
     * @param string $command
     * @param array $parameters
     *
     * @return string
     */
    protected function generateCommandUrl(string $command, array $parameters = []): string
    {
        $query = '';

        if (!empty($parameters)) {
            $query = '?' . http_build_query($parameters);
        }


        return $this->ssoServerUrl . '/api/sso/' . $command . $query;
    }

    /**
     * Set base class options (sso server url, broker name and secret, etc).
     *
     * @return void
     *
     * @throws MissingConfigurationException
     */
    protected function setOptions(): void
    {
        $this->ssoServerUrl = config('auth.server_url', null);
        $this->brokerName = config('auth.broker_name', null);
        $this->brokerSecret = config('auth.broker_secret', null);

        if (!$this->ssoServerUrl || !$this->brokerName || !$this->brokerSecret) {
            throw new MissingConfigurationException('Missing configuration values.');
        }
    }

    /**
     * Save unique client token to cookie.
     *
     * @return void
     */
    protected function saveToken(): void
    {
        if (isset($this->token) && $this->token) {
            return;
        }

        if ($this->token = strval(Cookie::get($this->getCookieName(), null))) {
            return;
        }

        $this->token = Str::random(40);
        Cookie::queue(Cookie::make($this->getCookieName(), $this->token, 60));

        $this->attach();
    }

    /**
     * Delete saved unique client token.
     *
     * @return void
     */
    protected function deleteToken(): void
    {
        $this->token = null;
        Cookie::forget($this->getCookieName());
    }

    /**
     * Make request to SSO server.
     *
     * @param string $method Request method 'post' or 'get'.
     * @param string $command Request command name.
     * @param array $parameters Parameters for URL query string if GET request and form parameters if it's POST request.
     *
     * @return array
     */
    protected function makeRequest(string $method, string $command, array $parameters = [])
    {
        $commandUrl = $this->generateCommandUrl($command);

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getSessionId(),
        ];

        switch ($method) {
            case 'POST':
                $body = ['form_params' => $parameters];
                break;
            case 'GET':
                $body = ['query' => $parameters];
                break;
            default:
                $body = [];
                break;
        }

        $client = new GuzzleHttp\Client;
        $response = $client->request($method, $commandUrl, $body + ['headers' => $headers]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Redirect client to specified url.
     *
     * @param string $url URL to be redirected.
     * @param array $parameters HTTP query string.
     * @param int $httpResponseCode HTTP response code for redirection.
     *
     * @return void
     */
    protected function redirect(string $url, array $parameters = [], int $httpResponseCode = 307)
    {
        $query = '';

        if (!empty($parameters)) {
            $query = '?';

            if (parse_url($url, PHP_URL_QUERY)) {
                $query = '&';
            }

            $query .= http_build_query($parameters);
        }

        app()->abort($httpResponseCode, '', ['Location' => $url . $query]);
    }

    /**
     * Getting current url which can be used as return to url.
     *
     * @return string
     */
    protected function getCurrentUrl(): string
    {
        return url()->full();
    }

    /**
     * Cookie name in which we save unique client token.
     *
     * @return string
     */
    protected function getCookieName(): string
    {
        return 'sso_token_' . preg_replace('/[_\W]+/', '_', strtolower($this->brokerName));
    }
}

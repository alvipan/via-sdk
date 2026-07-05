<?php

declare(strict_types=1);

namespace Ashvia\Sdk\Resources;

use Ashvia\Sdk\Http\Response;

final class Auth extends Resource
{
    /**
     * Generate the OAuth authorization URL.
     */
    public function authorizationUrl(?string $state = null): string
    {
        $query = [
            'client_id' => $this->config()->clientId,
            'redirect_uri' => $this->config()->redirectUri,
            'response_type' => 'code',
            'scope' => $this->config()->defaultScopes(),
        ];

        if ($state !== null && $state !== '') {
            $query['state'] = $state;
        }

        return rtrim($this->config()->baseUrl, '/')
            . $this->config()->authorizationEndpoint()
            . '?'
            . http_build_query($query);
    }

    /**
     * Exchange an authorization code for an access token.
     */
    public function exchange(string $code): Response
    {
        return $this->request()->postForm(
            $this->config()->tokenEndpoint(),
            [
                'grant_type' => 'authorization_code',
                'client_id' => $this->config()->clientId,
                'client_secret' => $this->config()->clientSecret,
                'redirect_uri' => $this->config()->redirectUri,
                'code' => $code,
            ],
        );
    }

    /**
     * Refresh an access token.
     */
    public function refresh(string $refreshToken): Response
    {
        return $this->request()->postForm(
            $this->config()->tokenEndpoint(),
            [
                'grant_type' => 'refresh_token',
                'client_id' => $this->config()->clientId,
                'client_secret' => $this->config()->clientSecret,
                'refresh_token' => $refreshToken,
            ],
        );
    }

    /**
     * Revoke an access token.
     */
    public function revoke(string $accessToken): Response
    {
        return $this->request()->postForm(
            $this->config()->revokeEndpoint(),
            [
                'token' => $accessToken,
                'token_type_hint' => 'access_token',
            ],
        );
    }

    /**
     * Retrieve the authenticated user.
     */
    public function user(string $accessToken): Response
    {
        return $this->request()
            ->withToken($accessToken)
            ->get($this->config()->userEndpoint());
    }

    /**
     * Retrieve the OpenID Connect userinfo.
     */
    public function userinfo(string $accessToken): Response
    {
        return $this->request()
            ->withToken($accessToken)
            ->get($this->config()->userinfoEndpoint());
    }
}
<?php

declare(strict_types=1);

namespace Ashvia\Sdk;

final readonly class Config
{
    public function __construct(
        public string $baseUrl,
        public string $clientId,
        public string $clientSecret,
        public string $redirectUri,
        public int $timeout = 30,
        public bool $verifySsl = true,
        public string $userAgent = 'ASHVIA PHP SDK/1.0.3',
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    public function loginEndpoint(): string
    {
        return '/login';
    }

    public function registerEndpoint(): string
    {
        return '/register';
    }

    public function forgotPasswordEndpoint(): string
    {
        return '/forgot-password';
    }

    /*
    |--------------------------------------------------------------------------
    | OAuth
    |--------------------------------------------------------------------------
    */

    public function authorizationEndpoint(): string
    {
        return '/oauth/authorize';
    }

    public function tokenEndpoint(): string
    {
        return '/oauth/token';
    }

    public function revokeEndpoint(): string
    {
        return '/oauth/tokens/revoke';
    }

    /*
    |--------------------------------------------------------------------------
    | API
    |--------------------------------------------------------------------------
    */

    public function userEndpoint(): string
    {
        return '/api/user';
    }

    public function userinfoEndpoint(): string
    {
        return '/api/userinfo';
    }

    /*
    |--------------------------------------------------------------------------
    | Defaults
    |--------------------------------------------------------------------------
    */

    public function defaultScopes(): string
    {
        return 'profile email';
    }
}
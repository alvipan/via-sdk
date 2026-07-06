<?php

declare(strict_types=1);

namespace Ashvia\Sdk\Resources;

use Ashvia\Sdk\Objects\AccessToken;
use Ashvia\Sdk\Exceptions\AshviaException;
use Ashvia\Sdk\Http\Response;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

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
            . '?' . http_build_query($query);
    }

    /**
     * Exchange an authorization code for an access token.
     */
    public function token(string $code): AccessToken
    {
        return $this->exchangeToken(
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
    public function refresh(string $refreshToken): AccessToken
    {
        return $this->exchangeToken(
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
     * @param array<string, string> $data
     */
    private function exchangeToken(array $data): AccessToken
    {
        try {
            $response = $this->request()->postForm(
                $this->config()->tokenEndpoint(),
                $data,
            );
        } catch (GuzzleException $exception) {
            throw AshviaException::network(
                'Unable to complete OAuth token request: ' . $exception->getMessage(),
                0,
                $exception,
            );
        }

        if ($response->failed()) {
            $message = $response->body();

            try {
                $payload = $response->json();

                if (isset($payload['error_description'])) {
                    $message = (string) $payload['error_description'];
                } elseif (isset($payload['error'])) {
                    $message = (string) $payload['error'];
                }
            } catch (JsonException) {
            }

            throw AshviaException::authentication(
                'OAuth token request failed: ' . $message,
                $response->status(),
            );
        }

        try {
            $payload = $response->json();
        } catch (JsonException $exception) {
            throw AshviaException::request(
                'Invalid OAuth token response payload.',
                $response->status(),
                $exception,
            );
        }

        return new AccessToken(
            accessToken: (string) ($payload['access_token'] ?? ''),
            refreshToken: isset($payload['refresh_token']) ? (string) $payload['refresh_token'] : null,
            tokenType: (string) ($payload['token_type'] ?? 'Bearer'),
            expiresIn: (int) ($payload['expires_in'] ?? 0),
            scope: isset($payload['scope']) ? (string) $payload['scope'] : null,
        );
    }
}

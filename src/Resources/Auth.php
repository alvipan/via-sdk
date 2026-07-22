<?php

declare(strict_types=1);

namespace Ashvia\Sdk\Resources;

use Ashvia\Sdk\Exceptions\AshviaException;
use Ashvia\Sdk\Http\Response;
use Ashvia\Sdk\Objects\AccessToken;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

final class Auth extends Resource
{
    /**
     * Generate the OAuth login URL.
     */
    public function loginUrl(?string $state = null): string
    {
        return $this->buildAuthenticationUrl(
            $this->config()->loginEndpoint(),
            $state,
        );
    }

    /**
     * Exchange an authorization code for an access token.
     */
    public function token(string $code): AccessToken
    {
        return $this->exchangeToken([
            ...$this->clientCredentials(),
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->config()->redirectUri,
            'code' => $code,
        ]);
    }

    /**
     * Refresh an access token.
     */
    public function refresh(string $refreshToken): AccessToken
    {
        return $this->exchangeToken([
            ...$this->clientCredentials(),
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ]);
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
     * OAuth client credentials.
     *
     * @return array<string, string>
     */
    private function clientCredentials(): array
    {
        return [
            'client_id' => $this->config()->clientId,
            'client_secret' => $this->config()->clientSecret,
        ];
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
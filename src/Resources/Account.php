<?php

declare(strict_types=1);

namespace Ashvia\Sdk\Resources;

final class Account extends Resource
{
    /**
     * Generate the account registration URL.
     */
    public function registerUrl(?string $state = null): string
    {
        return $this->buildUrl(
            $this->config()->registerEndpoint(),
            $state,
        );
    }

    /**
     * Generate the forgot password URL.
     */
    public function forgotPasswordUrl(?string $state = null): string
    {
        return $this->buildUrl(
            $this->config()->forgotPasswordEndpoint(),
            $state,
        );
    }

    /**
     * Build an authentication URL.
     */
    private function buildUrl(string $endpoint, ?string $state = null): string
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
            . $endpoint
            . '?' . http_build_query($query);
    }
}
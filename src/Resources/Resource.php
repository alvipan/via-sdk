<?php

declare(strict_types=1);

namespace Ashvia\Sdk\Resources;

use Ashvia\Sdk\Config;
use Ashvia\Sdk\Context;
use Ashvia\Sdk\Http\Request;

abstract class Resource
{
    public function __construct(
        protected readonly Context $context,
    ) {
    }

    protected function config(): Config
    {
        return $this->context->config();
    }

    protected function request(): Request
    {
        return $this->context->request();
    }

    /**
     * Build an authentication URL.
     */
    protected function buildAuthenticationUrl(
        string $endpoint,
        ?string $state = null,
    ): string {
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
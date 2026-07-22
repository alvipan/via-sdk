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
        return $this->buildAuthenticationUrl(
            $this->config()->registerEndpoint(),
            $state,
        );
    }

    /**
     * Generate the forgot password URL.
     */
    public function forgotPasswordUrl(?string $state = null): string
    {
        return $this->buildAuthenticationUrl(
            $this->config()->forgotPasswordEndpoint(),
            $state,
        );
    }
}
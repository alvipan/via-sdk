<?php

declare(strict_types=1);

namespace Ashvia\Sdk;

use Ashvia\Sdk\Ashvia;
use Ashvia\Sdk\Config;
use InvalidArgumentException;

final class Builder
{
    private ?string $baseUrl = null;

    private ?string $clientId = null;

    private ?string $clientSecret = null;

    private ?string $redirectUri = null;

    private int $timeout = 30;

    private bool $verifySsl = true;

    private string $userAgent = 'ASHVIA PHP SDK/1.0.0';

    private function __construct()
    {
    }

    public static function make(): self
    {
        return new self();
    }

    public function baseUrl(string $baseUrl): self
    {
        $this->baseUrl = rtrim(trim($baseUrl), '/');

        return $this;
    }

    public function clientId(string $clientId): self
    {
        $this->clientId = trim($clientId);

        return $this;
    }

    public function clientSecret(string $clientSecret): self
    {
        $this->clientSecret = trim($clientSecret);

        return $this;
    }

    public function redirectUri(string $redirectUri): self
    {
        $this->redirectUri = trim($redirectUri);

        return $this;
    }

    public function timeout(int $timeout): self
    {
        if ($timeout <= 0) {
            throw new InvalidArgumentException('Timeout must be greater than zero.');
        }

        $this->timeout = $timeout;

        return $this;
    }

    public function verifySsl(bool $verifySsl = true): self
    {
        $this->verifySsl = $verifySsl;

        return $this;
    }

    public function userAgent(string $userAgent): self
    {
        $this->userAgent = trim($userAgent);

        return $this;
    }

    public function build(): Ashvia
    {
        return new Ashvia(
            new Config(
                baseUrl: $this->require($this->baseUrl, 'Base URL'),
                clientId: $this->require($this->clientId, 'Client ID'),
                clientSecret: $this->require($this->clientSecret, 'Client Secret'),
                redirectUri: $this->require($this->redirectUri, 'Redirect URI'),
                timeout: $this->timeout,
                verifySsl: $this->verifySsl,
                userAgent: $this->userAgent,
            ),
        );
    }

    private function require(?string $value, string $name): string
    {
        if ($value === null || $value === '') {
            throw new InvalidArgumentException("{$name} is required.");
        }

        return $value;
    }
}
<?php

declare(strict_types=1);

namespace Ashvia\Sdk\Objects;

use DateTimeImmutable;

final readonly class AccessToken
{
    /**
     * @param array<string, mixed> $extra
     */
    public function __construct(
        private string $accessToken,
        private ?string $refreshToken,
        private string $tokenType,
        private int $expiresIn,
        private ?string $scope = null,
    ) {
    }

    public function accessToken(): string
    {
        return $this->accessToken;
    }

    public function refreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function tokenType(): string
    {
        return $this->tokenType;
    }

    public function expiresIn(): int
    {
        return $this->expiresIn;
    }

    public function expiresAt(): DateTimeImmutable
    {
        return (new DateTimeImmutable('now'))->modify('+' . $this->expiresIn . ' seconds');
    }

    public function scope(): ?string
    {
        return $this->scope;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'token_type' => $this->tokenType,
            'expires_in' => $this->expiresIn,
            'scope' => $this->scope,
        ];
    }
}

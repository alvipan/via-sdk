<?php

declare(strict_types=1);

namespace Ashvia\Sdk\Http;

use JsonException;
use Psr\Http\Message\ResponseInterface;

final readonly class Response
{
    public function __construct(
        private ResponseInterface $response,
    ) {
    }

    public function status(): int
    {
        return $this->response->getStatusCode();
    }

    public function successful(): bool
    {
        return $this->status() >= 200 && $this->status() < 300;
    }

    public function failed(): bool
    {
        return ! $this->successful();
    }

    public function headers(): array
    {
        return $this->response->getHeaders();
    }

    public function header(string $name): ?string
    {
        $value = $this->response->getHeader($name);

        return $value[0] ?? null;
    }

    public function body(): string
    {
        return (string) $this->response->getBody();
    }

    /**
     * @throws JsonException
     */
    public function json(bool $associative = true): array
    {
        $body = $this->body();

        if ($body === '') {
            return [];
        }

        /** @var array */
        return json_decode(
            $body,
            $associative,
            512,
            JSON_THROW_ON_ERROR,
        );
    }

    public function raw(): ResponseInterface
    {
        return $this->response;
    }
}
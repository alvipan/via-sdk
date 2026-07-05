<?php

declare(strict_types=1);

namespace Ashvia\Sdk\Exceptions;

use Throwable;

class AshviaException extends \RuntimeException
{
    public static function authentication(
        string $message = 'Authentication failed.',
        int $code = 401,
        ?Throwable $previous = null,
    ): self {
        return new self($message, $code, $previous);
    }

    public static function validation(
        string $message = 'Validation failed.',
        int $code = 422,
        ?Throwable $previous = null,
    ): self {
        return new self($message, $code, $previous);
    }

    public static function network(
        string $message = 'Network error.',
        int $code = 0,
        ?Throwable $previous = null,
    ): self {
        return new self($message, $code, $previous);
    }

    public static function server(
        string $message = 'Internal server error.',
        int $code = 500,
        ?Throwable $previous = null,
    ): self {
        return new self($message, $code, $previous);
    }

    public static function request(
        string $message,
        int $code = 0,
        ?Throwable $previous = null,
    ): self {
        return new self($message, $code, $previous);
    }
}
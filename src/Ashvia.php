<?php

declare(strict_types=1);

namespace Ashvia\Sdk;

use Ashvia\Sdk\Builder;
use Ashvia\Sdk\Config;
use Ashvia\Sdk\Context;
use Ashvia\Sdk\Http\HttpClient;
use Ashvia\Sdk\Http\Request;
use Ashvia\Sdk\Resources\Auth;
use Ashvia\Sdk\Resources\Resource;
use InvalidArgumentException;

final class Ashvia
{
    private readonly Context $context;

    /**
     * @var array<class-string<Resource>, Resource>
     */
    private array $resources = [];

    public function __construct(
        ?Config $config = null,
        ?string $baseUrl = null,
        ?string $clientId = null,
        ?string $clientSecret = null,
        ?string $redirectUri = null,
        int $timeout = 30,
        bool $verifySsl = true,
        string $userAgent = 'ASHVIA PHP SDK/1.0.0',
    ) {
        $resolvedConfig = $config ?? new Config(
            baseUrl: $this->requireString($baseUrl, 'Base URL'),
            clientId: $this->requireString($clientId, 'Client ID'),
            clientSecret: $this->requireString($clientSecret, 'Client Secret'),
            redirectUri: $this->requireString($redirectUri, 'Redirect URI'),
            timeout: $timeout,
            verifySsl: $verifySsl,
            userAgent: $userAgent,
        );

        $request = new Request($client);

        $this->context = new Context(
            config: $resolvedConfig,
            request: $request,
        );
    }

    private function requireString(?string $value, string $name): string
    {
        if ($value === null || trim($value) === '') {
            throw new InvalidArgumentException("{$name} is required.");
        }

        return trim($value);
    }

    public static function builder(): Builder
    {
        return Builder::make();
    }

    public function config(): Config
    {
        return $this->context->config();
    }

    public function request(): Request
    {
        return $this->context->request();
    }

    public function auth(): Auth
    {
        /** @var Auth */
        return $this->resource(Auth::class);
    }

    /**
     * @template T of Resource
     *
     * @param class-string<T> $resource
     *
     * @return T
     */
    private function resource(string $resource): Resource
    {
        return $this->resources[$resource]
            ??= new $resource($this->context);
    }
}
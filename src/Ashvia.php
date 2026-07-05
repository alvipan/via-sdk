<?php

declare(strict_types=1);

namespace Ashvia\Sdk;

use Ashvia\Sdk\Builder\Builder;
use Ashvia\Sdk\Config\Config;
use Ashvia\Sdk\Context\SdkContext;
use Ashvia\Sdk\Http\HttpClient;
use Ashvia\Sdk\Http\Request;
use Ashvia\Sdk\Resources\Auth;
use Ashvia\Sdk\Resources\Resource;

final class Ashvia
{
    private readonly SdkContext $context;

    /**
     * @var array<class-string<Resource>, Resource>
     */
    private array $resources = [];

    public function __construct(
        Config $config,
    ) {
        $client = new HttpClient($config);

        $request = new Request($client);

        $this->context = new SdkContext(
            config: $config,
            client: $client,
            request: $request,
        );
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
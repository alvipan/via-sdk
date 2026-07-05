<?php

declare(strict_types=1);

namespace Ashvia\Sdk\Context;

use Ashvia\Sdk\Config\Config;
use Ashvia\Sdk\Http\HttpClient;
use Ashvia\Sdk\Http\Request;

final readonly class SdkContext
{
    public function __construct(
        private Config $config,
        private HttpClient $client,
        private Request $request,
    ) {
    }

    public function config(): Config
    {
        return $this->config;
    }

    public function client(): HttpClient
    {
        return $this->client;
    }

    public function request(): Request
    {
        return $this->request;
    }
}
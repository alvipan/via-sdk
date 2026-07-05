<?php

declare(strict_types=1);

namespace Ashvia\Sdk;

use Ashvia\Sdk\Config;
use Ashvia\Sdk\Http\Request;

final readonly class Context
{
    public function __construct(
        private Config $config,
        private Request $request,
    ) {
    }

    public function config(): Config
    {
        return $this->config;
    }

    public function request(): Request
    {
        return $this->request;
    }
}
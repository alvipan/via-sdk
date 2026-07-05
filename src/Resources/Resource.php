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
}
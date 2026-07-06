<?php

namespace Ashvia\Sdk\Resources;

use Ashvia\Sdk\Http\Response;

final class User extends Resource
{
    /**
     * Retrieve authenticated user.
     */
    public function current(string $accessToken): Response
    {
        return $this->request()
            ->withToken($accessToken)
            ->get($this->config()->userEndpoint());
    }
}
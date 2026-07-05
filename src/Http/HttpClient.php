<?php

declare(strict_types=1);

namespace Ashvia\Sdk\Http;

use Ashvia\Sdk\Config\Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

final class HttpClient
{
    private Client $client;

    public function __construct(
        private readonly Config $config,
    ) {
        $this->client = new Client([
            'base_uri' => rtrim($config->baseUrl, '/') . '/',
            'timeout' => $config->timeout,
            'verify' => $config->verifySsl,
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'User-Agent' => $config->userAgent,
                'X-Client-Id' => $config->clientId,
                'X-Client-Secret' => $config->clientSecret,
            ],
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function send(
        string $method,
        string $uri,
        array $options = [],
    ): ResponseInterface {
        return $this->client->request(
            strtoupper($method),
            ltrim($uri, '/'),
            $options,
        );
    }

    public function config(): Config
    {
        return $this->config;
    }
}
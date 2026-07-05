<?php

declare(strict_types=1);

namespace Ashvia\Sdk\Http;

use GuzzleHttp\Exception\GuzzleException;

final readonly class Request
{
    /**
     * @param array<string, string> $headers
     */
    public function __construct(
        private HttpClient $client,
        private array $headers = [],
    ) {
    }

    /**
     * @param array<string, string> $headers
     *
     * @return self
     */
    public function withHeaders(array $headers): self
    {
        return new self($this->client, array_merge($this->headers, $headers));
    }

    public function withToken(string $accessToken): self
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ]);
    }

    /**
     * @throws GuzzleException
     *
     * @param array<string, string> $query
     * @param array<string, string> $headers
     */
    public function get(
        string $uri,
        array $query = [],
        array $headers = [],
    ): Response {
        return $this->send(
            'GET',
            $uri,
            [
                'query' => $query,
                'headers' => $this->mergeHeaders($headers),
            ],
        );
    }

    /**
     * @throws GuzzleException
     *
     * @param array<string, mixed> $data
     * @param array<string, string> $headers
     */
    public function post(
        string $uri,
        array $data = [],
        array $headers = [],
    ): Response {
        return $this->send(
            'POST',
            $uri,
            [
                'json' => $data,
                'headers' => $this->mergeHeaders($headers),
            ],
        );
    }

    /**
     * @throws GuzzleException
     *
     * @param array<string, mixed> $data
     * @param array<string, string> $headers
     */
    public function postForm(
        string $uri,
        array $data = [],
        array $headers = [],
    ): Response {
        return $this->send(
            'POST',
            $uri,
            [
                'form_params' => $data,
                'headers' => $this->mergeHeaders($headers),
            ],
        );
    }

    /**
     * @throws GuzzleException
     *
     * @param array<string, mixed> $data
     * @param array<string, string> $headers
     */
    public function put(
        string $uri,
        array $data = [],
        array $headers = [],
    ): Response {
        return $this->send(
            'PUT',
            $uri,
            [
                'json' => $data,
                'headers' => $this->mergeHeaders($headers),
            ],
        );
    }

    /**
     * @throws GuzzleException
     *
     * @param array<string, mixed> $data
     * @param array<string, string> $headers
     */
    public function patch(
        string $uri,
        array $data = [],
        array $headers = [],
    ): Response {
        return $this->send(
            'PATCH',
            $uri,
            [
                'json' => $data,
                'headers' => $this->mergeHeaders($headers),
            ],
        );
    }

    /**
     * @throws GuzzleException
     *
     * @param array<string, mixed> $data
     * @param array<string, string> $headers
     */
    public function delete(
        string $uri,
        array $data = [],
        array $headers = [],
    ): Response {
        return $this->send(
            'DELETE',
            $uri,
            [
                'json' => $data,
                'headers' => $this->mergeHeaders($headers),
            ],
        );
    }

    /**
     * @throws GuzzleException
     *
     * @param array<string, mixed> $options
     */
    private function send(
        string $method,
        string $uri,
        array $options = [],
    ): Response {
        return new Response(
            $this->client->send(
                $method,
                $uri,
                $options,
            ),
        );
    }

    /**
     * @param array<string, string> $headers
     *
     * @return array<string, string>
     */
    private function mergeHeaders(array $headers): array
    {
        return array_merge($this->headers, $headers);
    }
}
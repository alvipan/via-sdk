<?php

declare(strict_types=1);

use Ashvia\Sdk\Objects\AccessToken;
use Ashvia\Sdk\Config\Config;
use Ashvia\Sdk\Context;
use Ashvia\Sdk\Http\HttpClient;
use Ashvia\Sdk\Http\Request;
use Ashvia\Sdk\Resources\Auth;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response as Psr7Response;
use PHPUnit\Framework\TestCase;

final class AuthTest extends TestCase
{
    public function testAuthorizationUrlIncludesRequiredParameters(): void
    {
        $auth = $this->createAuth();

        $url = $auth->authorizationUrl('state-123');

        $this->assertStringContainsString('/oauth/authorize', $url);
        $this->assertStringContainsString('client_id=client-id', $url);
        $this->assertStringContainsString('redirect_uri=https%3A%2F%2Fapp.example.com%2Fcallback', $url);
        $this->assertStringContainsString('response_type=code', $url);
        $this->assertStringContainsString('state=state-123', $url);
    }

    public function testTokenExchangesCodeForAccessToken(): void
    {
        $handler = new MockHandler([
            new Psr7Response(200, [], json_encode([
                'token_type' => 'Bearer',
                'expires_in' => 3600,
                'access_token' => 'access-token',
                'refresh_token' => 'refresh-token',
                'scope' => 'profile email',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $auth = $this->createAuth($handler);

        $token = $auth->token('auth-code');

        $this->assertInstanceOf(AccessToken::class, $token);
        $this->assertSame('access-token', $token->accessToken());
        $this->assertSame('refresh-token', $token->refreshToken());
        $this->assertSame('Bearer', $token->tokenType());
        $this->assertSame(3600, $token->expiresIn());
        $this->assertSame('profile email', $token->scope());
    }

    public function testRefreshExchangesRefreshTokenForAccessToken(): void
    {
        $handler = new MockHandler([
            new Psr7Response(200, [], json_encode([
                'token_type' => 'Bearer',
                'expires_in' => 7200,
                'access_token' => 'new-access-token',
                'refresh_token' => 'new-refresh-token',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $auth = $this->createAuth($handler);

        $token = $auth->refresh('existing-refresh-token');

        $this->assertInstanceOf(AccessToken::class, $token);
        $this->assertSame('new-access-token', $token->accessToken());
        $this->assertSame('new-refresh-token', $token->refreshToken());
        $this->assertSame(7200, $token->expiresIn());
    }

    private function createAuth(?MockHandler $handler = null): Auth
    {
        $config = new Config(
            baseUrl: 'https://passport.example.test',
            clientId: 'client-id',
            clientSecret: 'client-secret',
            redirectUri: 'https://app.example.com/callback',
        );

        $httpClient = new HttpClient($config);

        if ($handler !== null) {
            $property = new ReflectionProperty(HttpClient::class, 'client');
            $property->setAccessible(true);
            $property->setValue($httpClient, new GuzzleClient(['handler' => $handler]));
        }

        $request = new Request($httpClient);
        $context = new Context($config, $httpClient, $request);

        return new Auth($context);
    }
}

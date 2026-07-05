<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Ashvia\Sdk\Ashvia;
use Ashvia\Sdk\Http\Response;

$sdk = Ashvia::builder()
    ->baseUrl('https://passport.example.test')
    ->clientId('client-id')
    ->clientSecret('client-secret')
    ->redirectUri('https://app.example.test/callback')
    ->build();

$response = $sdk->auth()->authorizationUrl('state-123');

if (!$response instanceof Response) {
    fwrite(STDERR, "authorizationUrl() must return an Ashvia\\Sdk\\Http\\Response instance.\n");
    exit(1);
}

if ($response->failed()) {
    fwrite(STDERR, "authorizationUrl() should be successful.\n");
    exit(1);
}

if (!str_contains($response->body(), 'https://passport.example.test/oauth/authorize')) {
    fwrite(STDERR, "authorizationUrl() did not return the expected authorization URL.\n");
    exit(1);
}

if (!str_contains($response->body(), 'client_id=client-id')) {
    fwrite(STDERR, "authorizationUrl() did not include the client ID.\n");
    exit(1);
}

echo "Auth flow regression check passed.\n";

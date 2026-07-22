# ASHVIA PHP SDK

Official PHP SDK for the ASHVIA ecosystem.

## Installation

```bash
composer require ashvia/sdk
```

For local development:

```bash
composer install
```

## Configuration

You can configure the SDK using the builder or by creating a `Config` instance.

### Builder

```php
use Ashvia\Sdk\Ashvia;

$sdk = Ashvia::builder()
    ->baseUrl('https://passport.example.test')
    ->clientId('client-id')
    ->clientSecret('client-secret')
    ->redirectUri('https://app.example.test/callback')
    ->build();
```

### Config

```php
use Ashvia\Sdk\Ashvia;
use Ashvia\Sdk\Config;

$config = new Config(
    baseUrl: 'https://passport.example.test',
    clientId: 'client-id',
    clientSecret: 'client-secret',
    redirectUri: 'https://app.example.test/callback',
);

$sdk = new Ashvia(config: $config);
```

---

# Authentication

## Login

Generate the login URL and redirect the user.

```php
return redirect(
    $sdk->auth()->loginUrl('state-123')
);
```

> `authorizationUrl()` is still available for backward compatibility.

```php
return redirect(
    $sdk->auth()->authorizationUrl('state-123')
);
```

## Register

Generate the registration URL.

```php
return redirect(
    $sdk->account()->registerUrl('state-123')
);
```

## Forgot Password

Generate the forgot password URL.

```php
return redirect(
    $sdk->account()->forgotPasswordUrl('state-123')
);
```

---

# OAuth

## Exchange Authorization Code

```php
$token = $sdk->auth()->token($code);

echo $token->accessToken();
```

## Refresh Token

```php
$token = $sdk->auth()->refresh($refreshToken);
```

## Revoke Token

```php
$sdk->auth()->revoke($token->accessToken());
```

---

# User

Retrieve the authenticated user.

```php
$response = $sdk->user()->current(
    $token->accessToken()
);

$user = $response->json();

print_r($user);
```

---

# Complete Example

```php
use Ashvia\Sdk\Ashvia;

$sdk = Ashvia::builder()
    ->baseUrl('https://passport.example.test')
    ->clientId('client-id')
    ->clientSecret('client-secret')
    ->redirectUri('https://app.example.test/callback')
    ->build();

// Redirect user to login
return redirect(
    $sdk->auth()->loginUrl()
);

// Callback
$token = $sdk->auth()->token(request('code'));

$user = $sdk->user()->current(
    $token->accessToken()
);

print_r($user->json());
```

---

# License

Released under the MIT License.
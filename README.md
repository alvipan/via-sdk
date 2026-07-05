# ASHVIA PHP SDK

Official PHP SDK for the ASHVIA ecosystem.

## Instalasi

```bash
composer require ashvia/sdk
```

Jika sedang mengerjakan dari repositori lokal, jalankan:

```bash
composer install
```

## Konfigurasi

Konfigurasi dapat dilakukan dengan builder atau langsung membuat objek `Config`.

### Menggunakan builder

```php
use Ashvia\Sdk\Ashvia;

$sdk = Ashvia::builder()
    ->baseUrl('https://passport.example.test')
    ->clientId('client-id')
    ->clientSecret('client-secret')
    ->redirectUri('https://app.example.test/callback')
    ->build();
```

### Menggunakan `Config`

```php
use Ashvia\Sdk\Ashvia;
use Ashvia\Sdk\Config\Config;

$config = new Config(
    baseUrl: 'https://passport.example.test',
    clientId: 'client-id',
    clientSecret: 'client-secret',
    redirectUri: 'https://app.example.com/callback',
);

$sdk = new Ashvia(config: $config);
```

## Login OAuth

### 1. Mendapatkan URL otorisasi

Gunakan method `authorizationUrl()` pada resource `auth()` untuk mengarahkan pengguna ke halaman login.

```php
$authUrl = $sdk->auth()->authorizationUrl('state-123');

echo $authUrl;
```

### 2. Menukar authorization code dengan access token

Setelah pengguna kembali ke aplikasi dengan `code`, panggil `token()`.

```php
$accessToken = $sdk->auth()->token('authorization-code');

echo $accessToken->accessToken();
```

### 3. Refresh token

Untuk memperbarui token akses:

```php
$newToken = $sdk->auth()->refresh('refresh-token');
```

## Contoh penggunaan

```php
use Ashvia\Sdk\Ashvia;

$sdk = Ashvia::builder()
    ->baseUrl('https://passport.example.test')
    ->clientId('client-id')
    ->clientSecret('client-secret')
    ->redirectUri('https://app.example.test/callback')
    ->build();

// Dapatkan URL otorisasi
$authorizationUrl = $sdk->auth()->authorizationUrl('request-state');

// Setelah mendapatkan authorization code:
$token = $sdk->auth()->token('authorization-code');

// Ambil data user
$response = $sdk->auth()->userinfo($token->accessToken());

if ($response->successful()) {
    $user = $response->json();
    print_r($user);
}
```

## License

This library is released under the MIT License.

# ASHVIA SDK v1.0.0 Architecture

## Vision

ASHVIA SDK is the official PHP SDK for the entire ASHVIA ecosystem.

The SDK provides a single, consistent API for interacting with ASHVIA services such as authentication, wallet, rewards, notifications, storage, AI, billing, analytics, and future services.

Applications should never communicate directly with ASHVIA services. All communication must go through this SDK.

---

# Design Goals

The SDK is designed with the following goals:

* Simple public API
* Strong internal architecture
* Framework independent
* PSR compliant
* Testable
* Extensible
* Stable

---

# Principles

## Single Responsibility Principle

Every class has exactly one responsibility.

Examples:

* Config stores configuration.
* Builder creates configuration.
* HttpClient sends HTTP requests.
* Request builds SDK requests.
* Response wraps HTTP responses.
* Resource implements business endpoints.

---

## Dependency Direction

Dependencies must always point inward.

```
Application
      │
      ▼
   Ashvia
      │
      ▼
 SdkContext
      │
      ├─────────────┐
      ▼             ▼
 HttpClient      Request
                      │
                      ▼
                 Resources
                      │
                      ▼
                 HTTP Response
```

Resources never communicate directly with Guzzle.

Resources never create HTTP clients.

Resources never instantiate Context.

---

## Framework Independence

The SDK must never depend on Laravel.

Framework integrations belong in dedicated bridge packages.

Example:

```
ashvia/sdk
ashvia/sdk-laravel
```

---

# Public API

The public API should remain stable throughout the entire v1.x lifecycle.

Example:

```php
$ashvia = Ashvia::builder()
    ->baseUrl(...)
    ->clientId(...)
    ->clientSecret(...)
    ->build();

$user = $ashvia
    ->auth()
    ->login(...);
```

Breaking changes are only allowed in v2.

---

# Package Structure

```
src/
│
├── Ashvia.php
│
├── Builder/
├── Config/
├── Context/
├── Contracts/
├── Exceptions/
├── Http/
├── Resources/
└── Support/
```

Every directory has exactly one responsibility.

---

# HTTP Layer

The HTTP layer consists of:

```
HttpClient
Request
Response
```

Responsibilities:

HttpClient

* owns Guzzle
* sends HTTP requests
* manages transport configuration

Request

* builds SDK requests
* exposes GET, POST, PUT, PATCH, DELETE

Response

* wraps PSR responses
* provides helper methods
* hides implementation details

No other class may directly use Guzzle.

---

# Resource Layer

Every endpoint belongs to a Resource.

Examples:

```
Auth
Wallet
Reward
Storage
Notification
```

Every Resource extends the abstract Resource base class.

Resources receive dependencies exclusively through SdkContext.

Resources never instantiate services.

---

# Context

SdkContext acts as the dependency container for the SDK.

It owns:

* Config
* HttpClient
* Request

Future versions may include:

* TokenStore
* Logger
* Cache
* Event Dispatcher

Resources only know SdkContext.

---

# Configuration

Configuration is immutable.

Config is implemented as a readonly value object.

Configuration is created using Builder.

---

# Builder

Builder is responsible only for constructing Config.

Builder must never perform HTTP requests.

Builder must never instantiate Resources.

---

# Exceptions

All SDK exceptions inherit from a single base exception.

Example:

```
AshviaException
 ├── AuthenticationException
 ├── ValidationException
 ├── RateLimitException
 ├── NetworkException
 └── ServerException
```

Applications should only catch SDK exceptions.

---

# Testing

Every class should be unit testable.

Dependencies must be injected.

No hidden global state.

No singleton pattern.

No static mutable state.

---

# Coding Standards

* PHP 8.3+
* declare(strict_types=1);
* PSR-12
* Typed properties
* Typed returns
* Constructor property promotion
* readonly where applicable
* Enums over constants
* SOLID principles

---

# Semantic Versioning

The SDK follows Semantic Versioning.

```
1.0.0
```

Initial stable release.

```
1.0.x
```

Bug fixes only.

```
1.1.0
```

New features without breaking changes.

```
2.0.0
```

Breaking changes.

---

# Development Philosophy

Public APIs should be small.

Internal architecture may grow.

Adding a new service should never require changing the SDK core.

The SDK core is considered stable once version 1.0.0 is released.

Future development should focus on extending Resources rather than modifying the foundation.

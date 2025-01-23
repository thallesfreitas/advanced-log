# README.md

# Advanced Logger for Laravel

[Leia em Português](README-pt-BR.md)

Advanced logging system for Laravel applications with integrated support for Slack, Sentry, and DataDog.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tfo/advanced-log.svg?style=flat-square)](https://packagist.org/packages/tfo/advanced-log)
[![Total Downloads](https://img.shields.io/packagist/dt/tfo/advanced-log.svg?style=flat-square)](https://packagist.org/packages/tfo/advanced-log)
[![License](https://img.shields.io/packagist/l/tfo/advanced-log.svg?style=flat-square)](LICENSE.md)

## Features

- 🚀 Simple and intuitive API
- 📱 Real-time Slack notifications
- 🔍 Sentry error tracking integration
- 📊 DataDog metrics support
- 🎨 Customizable message formatting
- ⚡ Multiple notification channels
- 🔒 Secure credentials handling
- 🛠 Simplified configuration

## Requirements

- PHP ^8.1
- Laravel ^10.0

## Installation

You can install the package via composer:

```bash
composer require tfo/advanced-log
```

## Configuration

1. Publish the configuration file:

```bash
php artisan vendor:publish --tag=advanced-logger-config
```

2. Add these variables to your `.env` file:

```env
# Slack Configuration
LOGGER_SLACK_WEBHOOK_URL=your-webhook-url
LOGGER_SLACK_CHANNEL=#your-channel
LOGGER_SLACK_USERNAME=Logger Bot

# Sentry Configuration
LOGGER_SENTRY_DSN=your-sentry-dsn

# DataDog Configuration
LOGGER_DATADOG_API_KEY=your-api-key
LOGGER_DATADOG_APP_KEY=your-app-key
LOGGER_DATADOG_SERVICE=your-service-name

# Enable/Disable Services
LOGGER_ENABLE_SLACK=true
LOGGER_ENABLE_SENTRY=true
LOGGER_ENABLE_DATADOG=true
```

## Usage

### Basic Logging

```php

use Illuminate\Support\Facades\Log;

Log::emergency('Emergency log test', ['context' => 'test']);
Log::alert('Alert log test', ['context' => 'test']);
Log::critical('Critical log test', ['context' => 'test']);
Log::error('Error log test', ['context' => 'test']);
Log::warning('Warning log test', ['context' => 'test']);
Log::notice('Notice log test', ['context' => 'test']);
Log::info('Info log test', ['context' => 'test']);
Log::debug('Debug log test', ['context' => 'test']);

```

### Advanced Logging

```php
use Tfo\AdvancedLog\Support\ALog;
```

### Performance Logging


```php
$startTime = microtime(true);
// Your code here
$duration = (microtime(true) - $startTime) * 1000;
ALog::performance('Process Order', $duration, [
    'order_id' => 123
]);
```

### Audit Logging

```php
ALog::audit('update', 'User', 1, [
    'name' => ['old' => 'John', 'new' => 'Johnny'],
    'email' => ['old' => 'john@example.com', 'new' => 'johnny@example.com']
]);
```

### Security Logging

```php
ALog::security('Login Failed', [
    'email' => 'user@example.com',
    'attempts' => 3
]);
```

### API Logging

```php
$response = response()->json(['status' => 'success']);
ALog::api('/api/users', 'GET', $response, 150.5);
```

### Database Logging

```php
ALog::database('create', 'users', 1, [
    'data' => ['name' => 'John', 'email' => 'john@example.com']
]);
```

### Job Logging

```php
ALog::job('SendWelcomeEmail', 'completed', [
    'user_id' => 1,
    'duration' => 1500
]);
```

### Cache Logging

```php
ALog::cache('hit', 'user:123', [
    'ttl' => 3600
]);
```

### Request Logging

```php
ALog::request('API Request', [
    'endpoint' => '/api/users',
    'params' => ['page' => 1]
]);
```

### Payment Logging

```php
ALog::payment('success', 99.99, 'stripe', [
    'transaction_id' => 'tx_123'
]);
```

### Notification Logging

```php
ALog::notification('email', 'user@example.com', 'welcome', [
    'template' => 'welcome-email'
]);
```

### File Logging

```php
ALog::file('upload', 'images/profile.jpg', [
    'size' => '2.5MB',
    'type' => 'image/jpeg'
]);
```

### Auth Logging

```php
ALog::auth('login_success', [
    'remember' => true,
    'device' => 'iPhone 13'
]);
```

### Export Logging

```php
ALog::export('users', 1000, [
    'format' => 'csv',
    'filters' => ['status' => 'active']
]);
```

## Channels

### Slack

Messages are sent to Slack with:

- Emojis indicating log level
- Color-coded messages
- Structured context fields
- Custom channel support

### Sentry

Errors are tracked in Sentry with:

- Full stack traces
- Environment information
- User context
- Custom tags and breadcrumbs

### DataDog

Metrics are sent to DataDog with:

- Custom metrics
- Tagging
- Event aggregation
- Performance tracking

## Testing

```bash
composer test
```

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for more information about recent changes.

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email thallesfreitas@yahoo.com.br instead of using the issue tracker.

## Credits

- [Thalles Freitas](https://github.com/thallesfreitas)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

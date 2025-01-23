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
use Tfo\AdvancedLog\Facades\Log;

// Simple message
Log::log('info', 'User logged in successfully');

// With context
Log::log('info', 'New order created', [
    'order_id' => $order->id,
    'amount' => $order->amount,
    'customer' => $order->customer->email
]);
```

### Error Logging

```php
try {
    // Your code
} catch (\Exception $e) {
    Log::log('error', 'Payment processing failed', [
        'exception' => $e,
        'order_id' => $order->id
    ]);
}
```

### Custom Formatting

```php
Log::log('warning', 'High server load detected', [
    'cpu_usage' => '90%',
    'memory_usage' => '85%',
    'timestamp' => now()
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

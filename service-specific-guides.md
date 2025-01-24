# Detailed Service Configuration Guides

## Advanced Logger Features

### Basic Usage

```php
use Tfo\AdvancedLog\Support\ALog;

// Simple logging
ALog::performance('Process Order', $duration);
ALog::audit('update', 'User', 1, $changes);
ALog::security('Login Failed', ['attempts' => 3]);
```

## Slack Integration

### Setup Guide

1. Create Slack App:

   - Go to api.slack.com/apps
   - Create New App
   - Enable Incoming Webhooks
   - Add webhook to channel

2. Environment Configuration:

```env
LOGGER_SLACK_WEBHOOK_URL=your-webhook-url
LOGGER_SLACK_CHANNEL=#your-channel
LOGGER_SLACK_USERNAME=Logger Bot
LOGGER_ENABLE_SLACK=true
```

### Logging Examples

```php
// Performance Logging
ALog::performance('Process Order', 1500, [
    'order_id' => 123
]);

// Audit Logging
ALog::audit('update', 'User', 1, [
    'name' => ['old' => 'John', 'new' => 'Johnny']
]);

// Security Events
ALog::security('Login Failed', [
    'email' => 'user@example.com',
    'attempts' => 3
]);
```

## Sentry Integration

### Setup Guide

1. Environment Configuration:

```env
LOGGER_SENTRY_DSN=your-sentry-dsn
LOGGER_ENABLE_SENTRY=true
```

### Logging Examples

```php
// Error Logging
ALog::error('Payment Failed', [
    'exception' => $e,
    'order_id' => $orderId
]);

// Performance Monitoring
ALog::performance('API Call', $duration, [
    'endpoint' => '/api/users',
    'method' => 'GET'
]);
```

## DataDog Integration

### Setup Guide

1. Environment Configuration:

```env
LOGGER_DATADOG_API_KEY=your-api-key
LOGGER_DATADOG_APP_KEY=your-app-key
LOGGER_DATADOG_SERVICE=your-service-name
LOGGER_ENABLE_DATADOG=true
```

### Logging Examples

```php
// API Monitoring
ALog::api('/api/users', 'GET', $response, 150.5);

// Job Monitoring
ALog::job('SendWelcomeEmail', 'completed', [
    'duration' => 1500,
    'user_id' => 1
]);
```

## Additional Features

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

### File Operations

```php
ALog::file('upload', 'images/profile.jpg', [
    'size' => '2.5MB',
    'type' => 'image/jpeg'
]);
```

### Cache Operations

```php
ALog::cache('hit', 'user:123', [
    'ttl' => 3600
]);
```

### Database Operations

```php
ALog::database('create', 'users', 1, [
    'data' => ['name' => 'John', 'email' => 'john@example.com']
]);
```

### Notification Tracking

```php
ALog::notification('email', 'user@example.com', 'welcome', [
    'template' => 'welcome-email'
]);
```

### Export Logging

```php
ALog::export('users', 1000, [
    'format' => 'csv',
    'filters' => ['status' => 'active']
]);
```

## Testing Routes

Test endpoints available (non-production only):

- `/test-logs/performance`
- `/test-logs/audit`
- `/test-logs/security`
- `/test-logs/api`
- `/test-logs/database`
- `/test-logs/job`
- `/test-logs/cache`
- `/test-logs/request`
- `/test-logs/payment`
- `/test-logs/notification`
- `/test-logs/file`
- `/test-logs/auth`
- `/test-logs/export`

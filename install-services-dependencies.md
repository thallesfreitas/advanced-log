# Service Dependencies Installation Guide

## Installation

```bash
# Install package
composer require tfo/advanced-log

# Run installer
php artisan advanced-log:install
```

## Slack Setup

1. Visit [Slack API](https://api.slack.com/apps)
2. Create New App > From scratch
3. Enable Incoming Webhooks:

   - Activate Incoming Webhooks
   - Add New Webhook to Workspace
   - Select channel
   - Copy Webhook URL

4. Configure `.env`:

```env
LOGGER_SLACK_WEBHOOK_URL=your-webhook-url
LOGGER_SLACK_CHANNEL=#your-channel
LOGGER_SLACK_USERNAME=Logger Bot
LOGGER_ENABLE_SLACK=true
```

## Sentry Setup

1. Create account at [Sentry.io](https://sentry.io)
2. Create Laravel project
3. Get DSN from Settings > Projects > [Project] > Client Keys
4. Configure `.env`:

```env
LOGGER_SENTRY_DSN=your-sentry-dsn
LOGGER_ENABLE_SENTRY=true
```

## DataDog Setup

1. Register at [DataDog](https://www.datadoghq.com/)
2. Get API/APP keys:

   - Organization Settings > API Keys
   - Organization Settings > Application Keys

3. Configure `.env`:

```env
LOGGER_DATADOG_API_KEY=your-api-key
LOGGER_DATADOG_APP_KEY=your-app-key
LOGGER_DATADOG_SERVICE=your-service-name
LOGGER_ENABLE_DATADOG=true
```

## Verification

Test your setup using provided routes:

```bash
# Test all loggers
curl http://your-app.test/test-logs/performance
curl http://your-app.test/test-logs/audit
curl http://your-app.test/test-logs/security
# ... etc
```

Verify in respective dashboards:

- Slack: Check configured channel
- Sentry: View Issues section
- DataDog: Check Logs section

## Usage Examples

```php
use App\Support\ALog;

// Performance logging
ALog::performance('Process Order', $duration, [
    'order_id' => 123
]);

// Audit logging
ALog::audit('update', 'User', 1, [
    'name' => ['old' => 'John', 'new' => 'Johnny']
]);

// Security logging
ALog::security('Login Failed', [
    'email' => 'user@example.com',
    'attempts' => 3
]);
```

## Troubleshooting

### Slack Issues

- Verify webhook URL format
- Confirm channel format (include #)
- Check bot permissions
- Test using `/test-logs/performance`

### Sentry Issues

- Validate DSN format
- Confirm project settings
- Test using `/test-logs/error`

### DataDog Issues

- Verify API/APP key format
- Check service name
- Confirm log collection enabled
- Test using `/test-logs/performance`

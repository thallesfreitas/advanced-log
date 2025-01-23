# Service Dependencies Installation Guide

## Slack Setup

1. Go to [Slack API](https://api.slack.com/apps)
2. Click "Create New App"

   - Choose "From scratch"
   - Name your app (e.g., "Logger")
   - Select your workspace

3. Configure Incoming Webhooks:

   - In the left sidebar, click "Incoming Webhooks"
   - Toggle "Activate Incoming Webhooks" to On
   - Click "Add New Webhook to Workspace"
   - Select the channel for notifications
   - Copy the Webhook URL

4. Add to `.env`:

```env
LOGGER_SLACK_WEBHOOK_URL=your-webhook-url
LOGGER_SLACK_CHANNEL=#your-channel
LOGGER_SLACK_USERNAME=Logger Bot
```

## Sentry Setup

1. Create account at [Sentry.io](https://sentry.io)
2. Create new project:

   - Select "Laravel" as platform
   - Follow setup instructions

3. Get DSN:

   - Go to Settings > Projects > [Your Project]
   - Click "Client Keys (DSN)"
   - Copy the DSN

4. Add to `.env`:

```env
LOGGER_SENTRY_DSN=your-sentry-dsn
```

## DataDog Setup

1. Sign up at [DataDog](https://www.datadoghq.com/)
2. Create new application:

   - Go to Organization Settings > Application Keys
   - Click "New Key"
   - Name your application

3. Get API and APP keys:

   - Go to Organization Settings > API Keys
   - Copy API Key
   - Go to Application Keys
   - Copy Application Key

4. Add to `.env`:

```env
LOGGER_DATADOG_API_KEY=your-api-key
LOGGER_DATADOG_APP_KEY=your-app-key
LOGGER_DATADOG_SERVICE=your-service-name
```

## Service Configuration

Enable/disable services in `.env`:

```env
LOGGER_ENABLE_SLACK=true
LOGGER_ENABLE_SENTRY=true
LOGGER_ENABLE_DATADOG=true
```

## Testing Services

Use test routes to verify integration:

```bash
# Test all services
curl http://your-app.test/logs/test-all

# Test individual services
curl http://your-app.test/logs/test-log-levels
curl http://your-app.test/logs/test-exception
```

Check respective dashboards:

- Slack: Messages in configured channel
- Sentry: Issues section
- DataDog: Logs section

## Troubleshooting

### Slack

- Verify webhook URL format
- Check channel name format (must include #)
- Confirm bot permissions

### Sentry

- Verify DSN format
- Check project settings
- Confirm error reporting level

### DataDog

- Verify API/APP key formats
- Check service name
- Confirm log collection enabled

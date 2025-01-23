# Detailed Service Configuration Guides

## Slack Deep Dive

### Custom Bot Setup

1. In Slack App settings:
   ```
   Features > Bot Token Scopes
   - Add: chat:write
   - Add: incoming-webhook
   ```

### Message Formatting

```php
Log::info('Message', [
    'blocks' => [
        [
            'type' => 'section',
            'text' => ['type' => 'mrkdwn', 'text' => '*Bold* _italic_']
        ]
    ]
]);
```

### Channel Configuration

- Public: `#channel-name`
- Private: Use channel ID
- Direct Messages: User ID

## Sentry Advanced Configuration

### Performance Monitoring

```php
\Sentry\startTransaction([
    'op' => 'http.request',
    'name' => 'Process Order'
]);
```

### Context Enrichment

```php
\Sentry\configureScope(function ($scope) {
    $scope->setUser([
        'id' => auth()->id(),
        'email' => auth()->user()->email
    ]);
});
```

### Sample Rates

```php
'traces_sample_rate' => env('SENTRY_TRACES_SAMPLE_RATE', 0.2),
'profiles_sample_rate' => env('SENTRY_PROFILES_SAMPLE_RATE', 0.1),
```

## DataDog Integration Details

### Metric Collection

```php
// Custom metrics
\DDTrace\GlobalTracer::get()->trace('process_order', [
    'resource' => 'OrderProcessor',
    'service' => 'orders'
]);
```

### Log Correlation

```php
// Add trace ID to logs
Log::info('Message', [
    'dd' => [
        'trace_id' => \DDTrace\GlobalTracer::get()->getActiveSpan()->getTraceId()
    ]
]);
```

### Environment Tagging

```php
DD_ENV=production
DD_SERVICE=api
DD_VERSION=1.2.3
```

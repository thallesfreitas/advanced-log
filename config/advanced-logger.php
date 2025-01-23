<?php

return [
    'slack' => [
        'webhook_url' => env('LOGGER_SLACK_WEBHOOK_URL', ''),
        'channel' => env('LOGGER_SLACK_CHANNEL', '#logs'),
        'username' => env('LOGGER_SLACK_USERNAME', 'Logger Bot'),
    ],

    'sentry' => [
        'dsn' => env('LOGGER_SENTRY_DSN', ''),
        'traces_sample_rate' => env('LOGGER_SENTRY_TRACES_SAMPLE_RATE', 1.0),
    ],

    'datadog' => [
        'api_key' => env('LOGGER_DATADOG_API_KEY', ''),
        'app_key' => env('LOGGER_DATADOG_APP_KEY', ''),
        'service_name' => env('LOGGER_DATADOG_SERVICE', 'laravel-app'),
    ],

    'services' => [
        'slack' => env('LOGGER_ENABLE_SLACK', true),
        'sentry' => env('LOGGER_ENABLE_SENTRY', true),
        'datadog' => env('LOGGER_ENABLE_DATADOG', true),
    ],
];
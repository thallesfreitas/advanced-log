<?php

return [
    'services' => [
        'slack' => [
            'webhook_url' => env('LOGGER_SLACK_WEBHOOK_URL', ''),
            'channel' => env('LOGGER_SLACK_CHANNEL', '#logs'),
            'username' => env('LOGGER_SLACK_USERNAME', 'LoggerBot'),
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
    ],

    'env' => env('LOGGER_ENV', 'development'),

    'enabled' => [
        'local' => env('LOGGER_ENABLED_LOCAL', 'performance,audit,security,api,database,job,cache,request,payment,notification,file,auth,export'),
        'prod' => env('LOGGER_ENABLED_PROD', 'security,api,database,cache,request,notification,file,auth,export'),
    ],

    'logtype' => [
        'emergency' => env('LOGGER_EMERGENCY_SERVICES', 'slack,sentry'),
        'alert' => env('LOGGER_ALERT_SERVICES', 'slack,sentry'),
        'critical' => env('LOGGER_CRITICAL_SERVICES', 'slack,sentry'),
        'error' => env('LOGGER_ERROR_SERVICES', 'slack,sentry'),
        'warning' => env('LOGGER_WARNING_SERVICES', 'slack'),
        'notice' => env('LOGGER_NOTICE_SERVICES', 'slack'),
        'info' => env('LOGGER_INFO_SERVICES', 'slack'),
        'debug' => env('LOGGER_DEBUG_SERVICES', 'slack'),
        'performance' => env('LOGGER_PERFORMANCE_SERVICES', 'slack,sentry'),
        'audit' => env('LOGGER_AUDIT_SERVICES', 'slack,sentry'),
        'security' => env('LOGGER_SECURITY_SERVICES', 'slack,sentry'),
        'api' => env('LOGGER_API_SERVICES', 'slack,sentry'),
        'database' => env('LOGGER_DATABASE_SERVICES', 'slack,sentry'),
        'job' => env('LOGGER_JOB_SERVICES', 'slack,sentry'),
        'cache' => env('LOGGER_CACHE_SERVICES', 'slack'),
        'request' => env('LOGGER_REQUEST_SERVICES', 'slack,sentry'),
        'payment' => env('LOGGER_PAYMENT_SERVICES', 'slack'),
        'notification' => env('LOGGER_NOTIFICATION_SERVICES', 'slack,sentry'),
        'file' => env('LOGGER_FILE_SERVICES', 'slack,sentry'),
        'auth' => env('LOGGER_AUTH_SERVICES', 'slack,sentry'),
        'export' => env('LOGGER_EXPORT_SERVICES', 'slack'),
    ],
];
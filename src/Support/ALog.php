<?php

namespace Tfo\AdvancedLog\Support;

use Illuminate\Support\Facades\Facade;
use Tfo\AdvancedLog\Loggers\{
    PerformanceLogger,
    AuditLogger,
    SecurityLogger,
    ApiLogger,
    DatabaseLogger,
    JobLogger,
    CacheLogger,
    RequestLogger,
    PaymentLogger,
    NotificationLogger,
    FileLogger,
    AuthLogger,
    ExportLogger
};

/**
 * Advanced Logging Facade
 */
class ALog extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'advanced-log';
    }

    /**
     * Log performance metrics
     */
    public static function performance(string $operation, float $duration, array $context = []): void
    {
        (new PerformanceLogger($operation, $duration))->log($context);
    }

    /**
     * Log audit events
     */
    public static function audit(string $action, string $model, mixed $id, array $changes = [], ?string $user = null): void
    {
        (new AuditLogger($action, $model, $id, $changes, $user))->log();
    }

    /**
     * Log security events
     */
    public static function security(string $event, array $context = []): void
    {
        (new SecurityLogger($event))->log($context);
    }

    /**
     * Log API interactions
     */
    public static function api(string $endpoint, string $method, mixed $response, ?float $duration = null): void
    {
        (new ApiLogger($endpoint, $method, $response, $duration))->log();
    }

    /**
     * Log database operations
     */
    public static function database(string $operation, string $table, mixed $id = null, array $context = []): void
    {
        (new DatabaseLogger($operation, $table, $id))->log($context);
    }

    /**
     * Log job execution
     */
    public static function job(string $job, string $status, array $context = []): void
    {
        (new JobLogger($job, $status))->log($context);
    }

    /**
     * Log cache operations
     */
    public static function cache(string $action, string $key, array $context = []): void
    {
        (new CacheLogger($action, $key))->log($context);
    }

    /**
     * Log HTTP requests
     */
    public static function request(string $message, array $context = []): void
    {
        (new RequestLogger($message))->log($context);
    }

    /**
     * Log payment transactions
     */
    public static function payment(string $status, float $amount, string $provider, array $context = []): void
    {
        (new PaymentLogger($status, $amount, $provider))->log($context);
    }

    /**
     * Log notifications
     */
    public static function notification(string $channel, string $recipient, string $type, array $context = []): void
    {
        (new NotificationLogger($channel, $recipient, $type))->log($context);
    }

    /**
     * Log file operations
     */
    public static function file(string $action, string $path, array $context = []): void
    {
        (new FileLogger($action, $path))->log($context);
    }

    /**
     * Log authentication events
     */
    public static function auth(string $event, array $context = []): void
    {
        (new AuthLogger($event))->log($context);
    }

    /**
     * Log data exports
     */
    public static function export(string $type, int $count, array $context = []): void
    {
        (new ExportLogger($type, $count))->log($context);
    }
}
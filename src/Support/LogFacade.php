<?php

namespace Tfo\AdvancedLog\Support;

use Illuminate\Support\Facades\Log as BaseFacade;

class LogFacade extends BaseFacade
{

    protected static function getFacadeAccessor()
    {
        return 'log';
    }

    public static function audit(string $action, string $model, mixed $id, array $changes = [], ?string $user = null)
    {



        return static::info("Audit: {$action} on {$model} #{$id}", [
            'action' => $action,
            'model' => $model,
            'id' => $id,
            'changes' => $changes,
            'user' => $user ?? auth()->user()?->email ?? 'system',
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public static function performance(string $operation, float $duration, array $context = [])
    {
        $threshold = config('advanced-logger.performance_threshold', 1000);
        if ($duration > $threshold) {
            return static::warning("Performance Alert: {$operation}", array_merge($context, [
                'duration' => round($duration, 2) . 'ms',
                'threshold' => $threshold . 'ms',
                'exceeded_by' => round($duration - $threshold, 2) . 'ms'
            ]));
        }
    }

    public static function security(string $event, array $context = [])
    {
        return static::warning("Security: {$event}", array_merge($context, [
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user' => auth()->user()?->email ?? 'guest',
            'url' => request()->fullUrl(),
            'method' => request()->method()
        ]));
    }

    public static function api(string $endpoint, string $method, mixed $response, ?float $duration = null)
    {
        return static::info("API {$method}: {$endpoint}", [
            'endpoint' => $endpoint,
            'method' => $method,
            'response_code' => $response instanceof \Illuminate\Http\Response ? $response->status() : null,
            'duration' => $duration ? round($duration, 2) . 'ms' : null,
            'user' => auth()->user()?->email ?? 'guest'
        ]);
    }

    public static function database(string $operation, string $table, mixed $id = null, array $context = [])
    {
        return static::info("DB {$operation}: {$table}", array_merge($context, [
            'operation' => $operation,
            'table' => $table,
            'record_id' => $id,
            'connection' => config('database.default')
        ]));
    }

    public static function customException(\Throwable $exception, string $context = 'system')
    {
        return static::error($exception->getMessage(), [
            'type' => get_class($exception),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'previous' => $exception->getPrevious() ? get_class($exception->getPrevious()) : null,
            'context' => $context,
            'url' => request()->fullUrl(),
            'user' => auth()->user()?->email ?? 'guest'
        ]);
    }

    public static function job(string $job, string $status, array $context = [])
    {
        return static::info("Job {$status}: {$job}", array_merge($context, [
            'job' => $job,
            'status' => $status,
            'queue' => $context['queue'] ?? 'default',
            'attempt' => $context['attempt'] ?? 1,
            'duration' => $context['duration'] ?? null
        ]));
    }

    public static function cache(string $action, string $key, array $context = [])
    {
        return static::debug("Cache {$action}: {$key}", array_merge($context, [
            'action' => $action,
            'key' => $key,
            'store' => config('cache.default')
        ]));
    }

    public static function notification(string $notification, string $channel, string $recipient, array $context = [])
    {
        return static::info("Notification sent: {$notification}", array_merge($context, [
            'notification' => $notification,
            'channel' => $channel,
            'recipient' => $recipient
        ]));
    }

    public static function request(string $message, array $context = [])
    {
        return static::info("Request: {$message}", array_merge($context, [
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user' => auth()->user()?->email ?? 'guest',
            'inputs' => request()->except(['password', 'password_confirmation'])
        ]));
    }

    protected static function getFacadeAccessor()
    {
        return 'log';
    }
}
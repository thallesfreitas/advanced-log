<?php

namespace Tfo\AdvancedLog\Facades;

use Illuminate\Support\Facades\Facade;

class ALog extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'advanced-log';
    }

    public static function performance(string $operation, float $duration, array $context = []): void
    {
        $threshold = config('advanced-logger.performance_threshold', 1000);

        if ($duration > $threshold) {
            $context = array_merge($context, [
                'duration' => round($duration, 2) . 'ms',
                'threshold' => $threshold . 'ms',
                'exceeded_by' => round($duration - $threshold, 2) . 'ms'
            ]);

            static::warning("Performance Alert: {$operation}", $context);
        }
    }

    public static function audit(string $action, string $model, mixed $id, array $changes = [], ?string $user = null): void
    {
        $context = [
            'action' => $action,
            'model' => $model,
            'id' => $id,
            'changes' => $changes,
            'user' => $user ?? auth()->user()?->email ?? 'system',
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ];

        static::info("Audit: {$action} on {$model} #{$id}", $context);
    }

    public static function security(string $event, array $context = []): void
    {
        $context = array_merge($context, [
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user' => auth()->user()?->email ?? 'guest',
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);

        static::warning("Security: {$event}", $context);
    }

    public static function api(string $endpoint, string $method, mixed $response, ?float $duration = null): void
    {
        $context = [
            'endpoint' => $endpoint,
            'method' => $method,
            'response_code' => $response instanceof \Illuminate\Http\Response ? $response->status() : null,
            'duration' => $duration ? round($duration, 2) . 'ms' : null,
            'user' => auth()->user()?->email ?? 'guest',
        ];

        static::info("API {$method}: {$endpoint}", $context);
    }

    public static function database(string $operation, string $table, mixed $id = null, array $context = []): void
    {
        $context = array_merge($context, [
            'operation' => $operation,
            'table' => $table,
            'record_id' => $id,
            'connection' => config('database.default'),
        ]);

        static::info("DB {$operation}: {$table}", $context);
    }

    public static function job(string $job, string $status, array $context = []): void
    {
        $context = array_merge($context, [
            'job' => $job,
            'status' => $status,
            'queue' => $context['queue'] ?? 'default',
            'attempt' => $context['attempt'] ?? 1,
            'duration' => $context['duration'] ?? null,
        ]);

        static::info("Job {$status}: {$job}", $context);
    }

    public static function cache(string $action, string $key, array $context = []): void
    {
        $context = array_merge($context, [
            'action' => $action,
            'key' => $key,
            'store' => config('cache.default'),
        ]);

        static::debug("Cache {$action}: {$key}", $context);
    }

    public static function request(string $message, array $context = []): void
    {
        $context = array_merge($context, [
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user' => auth()->user()?->email ?? 'guest',
            'inputs' => request()->except(['password', 'password_confirmation']),
        ]);

        static::info("Request: {$message}", $context);
    }
}
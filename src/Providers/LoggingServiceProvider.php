<?php

namespace Tfo\AdvancedLog\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Monolog\Logger as MonologLogger;
use Tfo\AdvancedLog\Services\Logging\Handlers\MultiChannelHandler;
use Tfo\AdvancedLog\Services\Logging\Formatters\SlackFormatter;
use Tfo\AdvancedLog\Services\Logging\Notifications\SlackNotificationService;
use Tfo\AdvancedLog\Services\Logging\Notifications\SentryNotificationService;
use Tfo\AdvancedLog\Services\Logging\Notifications\DataDogNotificationService;

class LoggingServiceProvider extends ServiceProvider
{
    public function register(): void
    {

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/advanced-logger.php',
            'advanced-logger'
        );


        $this->app->extend('log', function ($log) {
            $monolog = $log->driver()->getLogger();
            $monolog->pushHandler(new MultiChannelHandler(
                new SlackFormatter(),
                $this->getEnabledServices()
            ));
            return $log;
        });
        $this->registerMacros();

        $this->app->register(RouteServiceProvider::class);

    }

    // public function boot(): void
    // {
    //     if ($this->app->runningInConsole()) {
    //         $this->publishes([
    //             __DIR__ . '/../../config/advanced-logger.php' => config_path('advanced-logger.php'),
    //         ], 'advanced-logger-config');
    //     }

    //     $this->app->register(RouteServiceProvider::class);
    //     $this->registerMacros();
    // }

    private function getEnabledServices(): array
    {
        $services = [];

        if (config('advanced-logger.services.slack')) {
            $services[] = new SlackNotificationService();
        }

        if (config('advanced-logger.services.sentry')) {
            $services[] = new SentryNotificationService();
        }

        if (config('advanced-logger.services.datadog')) {
            $services[] = new DataDogNotificationService();
        }

        return $services;
    }

    public function registerMacros(): void
    {

        // Logs de Performance
        Log::macro('performance', function (string $operation, float $duration, array $context = []) {
            $threshold = config('advanced-logger.performance_threshold', 1000);

            if ($duration > $threshold) {
                Log::warning("Performance Alert: {$operation}", array_merge($context, [
                    'duration' => round($duration, 2) . 'ms',
                    'threshold' => $threshold . 'ms',
                ]));
            }
        });

        // Logs de Auditoria
        Log::macro('audit', function (string $action, string $model, mixed $id, array $changes = [], ?string $user = null) {
            $context = [
                'action' => $action,
                'model' => $model,
                'id' => $id,
                'changes' => $changes,
                'user' => $user ?? auth()->user()?->email ?? 'system',
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ];

            $this->info("Audit: {$action} on {$model} #{$id}", $context);
        });

        // Logs de Segurança
        Log::macro('security', function (string $event, array $context = []) {
            $context = array_merge($context, [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'user' => auth()->user()?->email ?? 'guest',
                'url' => request()->fullUrl(),
                'method' => request()->method(),
            ]);

            $this->warning("Security: {$event}", $context);
        });

        // Logs de API
        Log::macro('api', function (string $endpoint, string $method, mixed $response, float $duration = null) {
            $context = [
                'endpoint' => $endpoint,
                'method' => $method,
                'response_code' => $response instanceof \Illuminate\Http\Response ? $response->status() : null,
                'duration' => $duration ? round($duration, 2) . 'ms' : null,
                'user' => auth()->user()?->email ?? 'guest',
            ];

            $this->info("API {$method}: {$endpoint}", $context);
        });

        // Logs de Banco de Dados
        Log::macro('database', function (string $operation, string $table, mixed $id = null, array $context = []) {
            $context = array_merge($context, [
                'operation' => $operation,
                'table' => $table,
                'record_id' => $id,
                'connection' => config('database.default'),
            ]);

            $this->info("DB {$operation}: {$table}", $context);
        });

        // Logs de Exceção Detalhada
        Log::macro('exception', function (Throwable $exception, string $context = 'system') {
            $context = [
                'type' => get_class($exception),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
                'previous' => $exception->getPrevious() ? get_class($exception->getPrevious()) : null,
                'context' => $context,
                'url' => request()->fullUrl(),
                'user' => auth()->user()?->email ?? 'guest',
            ];

            $this->error($exception->getMessage(), $context);
        });

        // Logs de Job
        Log::macro('job', function (string $job, string $status, array $context = []) {
            $context = array_merge($context, [
                'job' => $job,
                'status' => $status,
                'queue' => $context['queue'] ?? 'default',
                'attempt' => $context['attempt'] ?? 1,
                'duration' => $context['duration'] ?? null,
            ]);

            $this->info("Job {$status}: {$job}", $context);
        });

        // Logs de Cache
        Log::macro('cache', function (string $action, string $key, array $context = []) {
            $context = array_merge($context, [
                'action' => $action,
                'key' => $key,
                'store' => config('cache.default'),
            ]);

            $this->debug("Cache {$action}: {$key}", $context);
        });

        // Logs de Notificação
        Log::macro('notification', function (string $notification, string $channel, string $recipient, array $context = []) {
            $context = array_merge($context, [
                'notification' => $notification,
                'channel' => $channel,
                'recipient' => $recipient,
            ]);

            $this->info("Notification sent: {$notification}", $context);
        });

        // Logs de Requisição
        Log::macro('request', function (string $message, array $context = []) {
            $context = array_merge($context, [
                'method' => request()->method(),
                'url' => request()->fullUrl(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'user' => auth()->user()?->email ?? 'guest',
                'inputs' => request()->except(['password', 'password_confirmation']),
            ]);

            $this->info("Request: {$message}", $context);
        });
    }
}
<?php

namespace Tfo\AdvancedLog\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
// use Illuminate\Log\Logger;
// use Monolog\Logger as MonologLogger;
use Tfo\AdvancedLog\Services\Logging\Formatters\SlackFormatter;
use Tfo\AdvancedLog\Services\Logging\Handlers\MultiChannelHandler;
use Tfo\AdvancedLog\Services\Logging\Notifications\DataDogNotificationService;
use Tfo\AdvancedLog\Services\Logging\Notifications\SentryNotificationService;
use Tfo\AdvancedLog\Services\Logging\Notifications\SlackNotificationService;

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


        Log::macro('performance', function (string $operation, float $duration, array $context = []) {
            $threshold = config('advanced-logger.performance_threshold', 1000);

            if ($duration > $threshold) {
                Log::channel('custom')->warning("Performance Alert: {$operation}", array_merge($context, [
                    'duration' => round($duration, 2) . 'ms',
                    'threshold' => $threshold . 'ms',
                ]));
            }
        });


        Log::macro('audit', function (string $action, string $model, mixed $id, array $changes = [], ?string $user = null) {
            Log::channel('custom')->info("Audit: {$action} on {$model} #{$id}", [
                'action' => $action,
                'model' => $model,
                'id' => $id,
                'changes' => $changes,
                'user' => $user ?? auth()->user()?->email ?? 'system',
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });
    }

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

}
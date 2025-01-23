<?php

namespace Tfo\AdvancedLog\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Log\Logger;
use Monolog\Logger as MonologLogger;

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


        Logger::macro('performance', function (string $operation, float $duration, array $context = []) {
            $threshold = config('advanced-logger.performance_threshold', 1000);

            if ($duration > $threshold) {
                $this->warning("Performance Alert: {$operation}", array_merge($context, [
                    'duration' => round($duration, 2) . 'ms',
                    'threshold' => $threshold . 'ms',
                ]));
            }
        });


        Logger::macro('audit', function (string $action, string $model, mixed $id, array $changes = [], ?string $user = null) {
            $this->info("Audit: {$action} on {$model} #{$id}", [
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
}
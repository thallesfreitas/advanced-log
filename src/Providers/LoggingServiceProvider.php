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
use Tfo\AdvancedLog\Console\InstallCommand;

class LoggingServiceProvider extends ServiceProvider
{
    public function register(): void
    {

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/advanced-logger.php',
            'advanced-logger'
        );

        // Estende o LogManager do Laravel
        $this->app->extend('log', function ($log) {
            $monolog = $log->getLogger();

            // Adiciona nosso handler customizado ao Monolog
            $monolog->pushHandler(new MultiChannelHandler(
                new SlackFormatter(),
                $this->getEnabledServices()
            ));

            return $log;
        });

        $this->app->singleton('advanced-log', function ($app) {
            return $app['log'];
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/advanced-logger.php' => config_path('advanced-logger.php'),
            ], 'advanced-logger-config');
        }
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
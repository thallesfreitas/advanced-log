<?php

namespace Tfo\AdvancedLog\Providers;

use Illuminate\Support\ServiceProvider;
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
        // $this->mergeConfigFrom(
        //     __DIR__ . '/../../config/advanced-logger.php',
        //     'advanced-logger'
        // );

        $this->app->extend('log', function ($log) {
            $monolog = $log->getLogger();

            $handler = new MultiChannelHandler(
                new SlackFormatter(),
                $this->getEnabledServices()
            );

            $monolog->pushHandler($handler);

            return $log;
        });

        $this->app->singleton('advanced-log', function ($app) {
            return $app['log'];
        });
    }

    public function boot(): void
    {
        // if ($this->app->runningInConsole()) {
        //     $this->publishes([
        //         __DIR__ . '/../../config/advanced-logger.php' => config_path('advanced-logger.php'),
        //     ], 'advanced-logger-config');
        // }

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../../config/advanced-logger.php' => config_path('advanced-logger.php'),
                __DIR__ . '/../../routes/test_routes.php' => base_path('routes/advanced-logger.php'),
            ], 'laravel-assets');

        }
    }

    // private function getEnabledServices(): array
    // {
    //     $services = [];

    //     if (config('advanced-logger.services.slack')) {
    //         $services[] = new SlackNotificationService();
    //     }

    //     if (config('advanced-logger.services.sentry')) {
    //         $services[] = new SentryNotificationService();
    //     }

    //     if (config('advanced-logger.services.datadog')) {
    //         $services[] = new DataDogNotificationService();
    //     }

    //     return $services;
    // }


    private function getEnabledServices(): array
    {
        $env = config('advanced-logger.env');

        $enabledLogs = explode(',', config("advanced-logger.enabled.$env"));

        $services = [];
        foreach ($enabledLogs as $level) {
            $levelServices = explode(',', config("advanced-logger.services.$level"));
            foreach ($levelServices as $service) {
                if (!in_array($service, $services)) {
                    $services[] = $this->createNotificationService($service);
                }
            }
        }

        return $services;
    }

    private function createNotificationService($service)
    {
        return match ($service) {
            'slack' => new SlackNotificationService(),
            'sentry' => new SentryNotificationService(),
            'datadog' => new DataDogNotificationService(),
            default => null,
        };
    }
}
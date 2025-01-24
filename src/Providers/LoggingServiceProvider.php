<?php

namespace Tfo\AdvancedLog\Providers;

use Illuminate\Support\ServiceProvider;
use Tfo\AdvancedLog\Providers\AlogRouteServiceProvider;
use Tfo\AdvancedLog\Services\Logging\Formatters\SlackFormatter;
use Tfo\AdvancedLog\Services\Logging\Handlers\MultiChannelHandler;
use Tfo\AdvancedLog\Services\Logging\Notifications\SlackNotificationService;
use Tfo\AdvancedLog\Services\Logging\Notifications\SentryNotificationService;
use Tfo\AdvancedLog\Services\Logging\Notifications\DataDogNotificationService;
use Tfo\AdvancedLog\Console\InstallCommand;

class LoggingServiceProvider extends ServiceProvider
{

    protected $providers = [
        AlogRouteServiceProvider::class,
    ];
    public function register(): void
    {

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/advanced-log.php',
            'advanced-log'
        );

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

    }


    private function getEnabledServices(): array
    {
        $env = config('advanced-log.env');

        $enabledLogs = explode(',', config("advanced-log.enabled.$env"));

        $services = [];
        foreach ($enabledLogs as $level) {
            $levelServices = explode(',', config("advanced-log.logtype.$level"));
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
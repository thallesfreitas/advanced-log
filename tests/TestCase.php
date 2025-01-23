<?php

namespace Tfo\AdvancedLog\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Tfo\AdvancedLog\Providers\LoggingServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LoggingServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('advanced-logger.slack.webhook_url', 'https://hooks.slack.com/services/TEST');
        $app['config']->set('advanced-logger.slack.channel', '#test');
        $app['config']->set('advanced-logger.services.slack', true);
        $app['config']->set('advanced-logger.services.sentry', false);
        $app['config']->set('advanced-logger.services.datadog', false);
        $app['config']->set('advanced-logger.performance_threshold', 1000);
    }
}
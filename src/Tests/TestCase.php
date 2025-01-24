<?php

namespace Tfo\AdvancedLog\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Tfo\AdvancedLog\Providers\LoggingServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LoggingServiceProvider::class
        ];
    }
}
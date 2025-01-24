<?php

namespace Tfo\AdvancedLog\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use PHPUnit\Framework\Assert;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            'Tfo\AdvancedLog\Providers\LoggingServiceProvider'
        ];
    }


}
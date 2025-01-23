<?php

namespace Tfo\AdvancedLog\Facades;

use Illuminate\Support\Facades\Facade;
use Tfo\AdvancedLog\Contracts\LoggerInterface;

class Log extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LoggerInterface::class;
    }
}
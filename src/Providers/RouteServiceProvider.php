<?php

namespace Tfo\AdvancedLog\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app()->environment('local')) {
            Route::prefix('logs')
                ->middleware(['web'])
                ->group(__DIR__ . '/../../routes/test.php');
        }
    }
}
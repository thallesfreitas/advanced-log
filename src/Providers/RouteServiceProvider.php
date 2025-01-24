<?php

namespace Tfo\AdvancedLog\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function (): void {
            if ($this->app->environment('local')) {
                Route::middleware(['web'])
                    ->group(base_path('routes/advanced-logger.php'));
            }
        });
    }
}
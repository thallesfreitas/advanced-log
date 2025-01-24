<?php

namespace Tfo\AdvancedLog\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class AlogRouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function (): void {
            if ($this->app->environment('local')) {
                $routePath = base_path('routes/advanced-log.php');
                if (file_exists($routePath)) {
                    Route::middleware(['web'])
                        ->group($routePath);
                }
            }
        });
    }
}
<?php

namespace Tfo\AdvancedLog\Services\Logging;

use Illuminate\Log\LogManager;
use Illuminate\Support\Facades\Log;

class CustomLogger extends LogManager
{
    public function __construct($app)
    {
        parent::__construct($app);
        $this->registerMacros();
    }

    protected function registerMacros(): void
    {
        Log::macro('performance', function (string $operation, float $duration, array $context = []) {
            $threshold = config('advanced-logger.performance_threshold', 1000);
            if ($duration > $threshold) {
                return $this->warning("Performance Alert: {$operation}", array_merge($context, [
                    'duration' => round($duration, 2) . 'ms',
                    'threshold' => $threshold . 'ms',
                    'exceeded_by' => round($duration - $threshold, 2) . 'ms'
                ]));
            }
        });

    }
}
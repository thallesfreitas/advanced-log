<?php

namespace Tfo\AdvancedLog\Services\Logging;

use Illuminate\Log\LogManager;

class CustomLogger extends LogManager
{
    public function __construct($app)
    {
        parent::__construct($app);
    }

    protected function performance(string $operation, float $duration, array $context = [])
    {

        $threshold = config('advanced-logger.performance_threshold', 1000);
        if ($duration > $threshold) {
            return $this->warning("Performance Alert: {$operation}", array_merge($context, [
                'duration' => round($duration, 2) . 'ms',
                'threshold' => $threshold . 'ms',
            ]));
        }
    }

    protected function audit(string $action, string $model, mixed $id, array $changes = [], ?string $user = null)
    {
        return $this->info("Audit: {$action} on {$model} #{$id}", [
            'action' => $action,
            'model' => $model,
            'id' => $id,
            'changes' => $changes,
            'user' => $user ?? auth()->user()?->email ?? 'system',
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    // Adicione os outros métodos de forma similar
}
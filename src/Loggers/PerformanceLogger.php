<?php

namespace Tfo\AdvancedLog\Loggers;

use Illuminate\Support\Facades\Log;

/**
 * Logs performance metrics and alerts when thresholds are exceeded
 * 
 * @example
 * // Log performance of an operation
 * $startTime = microtime(true);
 * // ... your operation code ...
 * $duration = (microtime(true) - $startTime) * 1000;
 * (new PerformanceLogger('Process Order', $duration))->log(['order_id' => 123]);
 * 
 * @example
 * // Log performance with custom threshold
 * $logger = new PerformanceLogger('API Call', $duration, 500); // 500ms threshold
 * $logger->log(['endpoint' => '/api/users']);
 */
class PerformanceLogger extends BaseLogger
{
    private string $operation;
    private float $duration;
    private int $threshold;

    public function __construct(
        string $operation,
        float $duration,
        ?int $threshold = null
    ) {
        $this->operation = $operation;
        $this->duration = $duration;
        $this->threshold = $threshold ?? config('advanced-log.performance_threshold', 1000);
    }

    public function log(array $context = []): void
    {
        if ($this->duration <= $this->threshold) {
            Log::debug("Performance within threshold: {$this->operation}", $this->getPerformanceContext($context));
            return;
        }

        Log::warning(
            "Performance Alert: {$this->operation}",
            $this->getPerformanceContext($context)
        );
    }

    private function getPerformanceContext(array $context): array
    {
        $performanceContext = [
            'operation' => $this->operation,
            'duration' => round($this->duration, 2) . 'ms',
            'threshold' => $this->threshold . 'ms',
            'exceeded_by' => $this->duration > $this->threshold
                ? round($this->duration - $this->threshold, 2) . 'ms'
                : '0ms',
            'timestamp' => now()->format('Y-m-d H:i:s.u')
        ];

        return $this->mergeContext(array_merge($performanceContext, $context));
    }
}
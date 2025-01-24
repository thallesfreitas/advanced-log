<?php

namespace Tfo\AdvancedLog\Loggers;

use Illuminate\Support\Facades\Log;
use Monolog\Level;


/**
 * Logs background job execution status and metrics
 * 
 * @example
 * // Log job start
 * (new JobLogger('SendEmail', 'started', ['email' => 'user@example.com']))->log();
 * 
 * @example
 * // Log completed job with duration
 * (new JobLogger('ProcessOrder', 'completed'))->log([
 *     'order_id' => 123,
 *     'duration' => 1500,
 *     'attempt' => 2
 * ]);
 */
class JobLogger extends BaseLogger
{
    public function __construct(
        private string $job,
        private string $status,
        private array $metadata = []
    ) {
    }

    public function log(array $context = []): void
    {
        $jobContext = [
            'job' => $this->job,
            'status' => $this->status,
            'queue' => $this->metadata['queue'] ?? config('queue.default'),
            'attempt' => $this->metadata['attempt'] ?? 1,
            'duration' => isset($context['duration']) ? round($context['duration'], 2) . 'ms' : null,
            'timestamp' => now()->format('Y-m-d H:i:s.u'),
            'connection' => config('queue.default')
        ];

        Log::log(
            $this->getLogLevel()->name,
            "Job {$this->status}: {$this->job}",
            $this->mergeContext(array_merge($jobContext, $context))
        );
    }

    private function getLogLevel(): Level
    {
        return match ($this->status) {
            'failed' => Level::Error,
            'retrying' => Level::Warning,
            'started' => Level::Debug,
            default => Level::Info
        };
    }
}
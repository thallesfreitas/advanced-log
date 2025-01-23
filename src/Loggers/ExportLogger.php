<?php

namespace Tfo\AdvancedLog\Loggers;

use Illuminate\Support\Facades\Log;

/**
 * Logs data export operations and metrics
 * 
 * @example
 * // Log CSV export
 * (new ExportLogger('users', 1000))->log([
 *     'format' => 'csv',
 *     'filters' => ['status' => 'active']
 * ]);
 * 
 * @example
 * // Log large data export with duration
 * (new ExportLogger('orders', 50000))->log([
 *     'format' => 'excel',
 *     'duration' => 15000,
 *     'size' => '25MB'
 * ]);
 */
class ExportLogger extends BaseLogger
{
    public function __construct(
        private string $type,
        private int $count,
        private string $format = 'csv'
    ) {
    }

    public function log(array $context = []): void
    {
        $exportContext = [
            'type' => $this->type,
            'record_count' => $this->count,
            'format' => $this->format,
            'timestamp' => now()->format('Y-m-d H:i:s.u'),
            'user' => auth()->user()?->email ?? 'system',
            'filters' => $context['filters'] ?? [],
            'duration' => isset($context['duration']) ? round($context['duration'], 2) . 'ms' : null,
            'file_size' => $context['size'] ?? null,
            'export_id' => uniqid('export_', true)
        ];

        Log::log(
            $this->getLogLevel()->name,
            "Export {$this->type}: {$this->count} records",
            $this->mergeContext(array_merge($exportContext, $context))
        );
    }

    private function getLogLevel(): Level
    {
        return match (true) {
            $this->count > 100000 => self::WARNING,
            $this->count > 10000 => self::NOTICE,
            default => self::INFO
        };
    }
}
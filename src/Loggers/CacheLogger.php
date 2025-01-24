<?php

namespace Tfo\AdvancedLog\Loggers;

use Illuminate\Support\Facades\Log;

/**
 * Logs cache operations and performance
 * 
 * @example
 * // Log cache hit
 * (new CacheLogger('hit', 'user:123'))->log([
 *     'ttl' => 3600
 * ]);
 * 
 * @example
 * // Log cache miss and regeneration
 * (new CacheLogger('miss', 'products:list'))->log([
 *     'generation_time' => 1500,
 *     'size' => '2.5MB'
 * ]);
 */
class CacheLogger extends BaseLogger
{
    public function __construct(
        private string $action,
        private string $key,
        private ?string $store = null
    ) {
        $this->store = $store ?? config('cache.default');
    }

    public function log(array $context = []): void
    {
        $cacheContext = [
            'action' => $this->action,
            'key' => $this->key,
            'store' => $this->store,
            'ttl' => $context['ttl'] ?? null,
            'size' => $context['size'] ?? null,
            'timestamp' => now()->format('Y-m-d H:i:s.u')
        ];

        Log::log(
            $this->getLogLevel()->name,
            "Cache {$this->action}: {$this->key}",
            $this->mergeContext(array_merge($cacheContext, $context))
        );
    }


    private function getLogLevel(): \Monolog\Level
    {
        return match ($this->status) {
            'failed' => Level::Error,
            'retrying' => Level::Warning,
            'started' => Level::Debug,
            default => Level::Info
        };
    }
}
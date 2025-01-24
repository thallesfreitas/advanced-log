<?php

namespace Tfo\AdvancedLog\Loggers;

use Illuminate\Support\Facades\Log;
use Monolog\Level;


/**
 * Logs database operations and queries
 * 
 * @example
 * // Log record creation
 * (new DatabaseLogger('create', 'users', 1))->log([
 *     'data' => ['name' => 'John', 'email' => 'john@example.com']
 * ]);
 * 
 * @example
 * // Log batch operation
 * (new DatabaseLogger('delete', 'orders', null))->log([
 *     'condition' => 'status = pending',
 *     'affected_rows' => 50
 * ]);
 */
class DatabaseLogger extends BaseLogger
{
    public function __construct(
        private string $operation,
        private string $table,
        private mixed $id = null,
        private ?string $connection = null
    ) {
        $this->connection = $connection ?? config('database.default');
    }

    public function log(array $context = []): void
    {
        $dbContext = [
            'operation' => $this->operation,
            'table' => $this->table,
            'record_id' => $this->id,
            'connection' => $this->connection,
            'query_time' => $context['query_time'] ?? null,
            'affected_rows' => $context['affected_rows'] ?? null,
            'timestamp' => now()->format('Y-m-d H:i:s.u')
        ];

        $level = $this->getLogLevel();

        Log::log(
            $level->name,
            "DB {$this->operation}: {$this->table}" . ($this->id ? " #{$this->id}" : ''),
            $this->mergeContext(array_merge($dbContext, $context))
        );
    }

    private function getLogLevel(): Level
    {
        return match (strtolower($this->operation)) {
            'delete', 'truncate' => Level::Warning,
            'create', 'update', 'insert' => Level::Info,
            'select', 'read' => Level::Debug,
            default => Level::Info
        };
    }
}
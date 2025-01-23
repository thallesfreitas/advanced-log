<?php

namespace Tfo\AdvancedLog\Loggers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Monolog\Level;

/**
 * Base class for all loggers providing common functionality
 */
abstract class BaseLogger
{
    /**
     * Log level constants matching Monolog levels
     */
    protected const EMERGENCY = Level::Emergency;
    protected const ALERT = Level::Alert;
    protected const CRITICAL = Level::Critical;
    protected const ERROR = Level::Error;
    protected const WARNING = Level::Warning;
    protected const NOTICE = Level::Notice;
    protected const INFO = Level::Info;
    protected const DEBUG = Level::Debug;

    /**
     * Execute the logging operation
     * 
     * @param array $context Additional context data to include in log
     */
    abstract public function log(array $context = []): void;

    /**
     * Get common context data included in all logs
     */
    protected function getCommonContext(): array
    {
        return [
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'environment' => config('app.env'),
            'ip' => Request::ip(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email ?? 'guest',
            'user_agent' => Request::userAgent(),
        ];
    }

    /**
     * Merge additional context with common context
     */
    protected function mergeContext(array $additionalContext): array
    {
        return array_merge($this->getCommonContext(), $additionalContext);
    }

    /**
     * Format an exception for logging
     */
    protected function formatException(\Throwable $e): array
    {
        return [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ];
    }

    /**
     * Clean sensitive data from context
     */
    protected function cleanSensitiveData(array $context): array
    {
        $sensitiveKeys = ['password', 'token', 'secret', 'key', 'auth'];

        return array_map(function ($value, $key) use ($sensitiveKeys) {
            if (is_string($key) && str_contains(strtolower($key), $sensitiveKeys)) {
                return '[REDACTED]';
            }
            return $value;
        }, $context, array_keys($context));
    }
}
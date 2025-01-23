<?php

namespace Tfo\AdvancedLog\Loggers;

use Illuminate\Support\Facades\Log;

/**
 * Logs HTTP request details and metrics
 * 
 * @example
 * // Log API request details
 * (new RequestLogger('API Request'))->log([
 *     'endpoint' => '/api/users',
 *     'params' => ['page' => 1, 'limit' => 10]
 * ]);
 * 
 * @example
 * // Log form submission
 * (new RequestLogger('Form Submission'))->log([
 *     'form' => 'contact',
 *     'validation_errors' => $errors->toArray()
 * ]);
 */
class RequestLogger extends BaseLogger
{
    public function __construct(
        private string $message,
        private bool $logRequestBody = true
    ) {
    }

    public function log(array $context = []): void
    {
        $requestContext = [
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'route' => request()->route()?->getName(),
            'inputs' => $this->logRequestBody ? $this->sanitizeInputs() : null,
            'headers' => $this->getRelevantHeaders(),
            'response_time' => defined('LARAVEL_START') ?
                round((microtime(true) - LARAVEL_START) * 1000, 2) . 'ms' : null,
            'timestamp' => now()->format('Y-m-d H:i:s.u')
        ];

        Log::log(
            $this->getLogLevel()->name,
            "Request: {$this->message}",
            $this->mergeContext(array_merge($requestContext, $context))
        );
    }

    private function sanitizeInputs(): array
    {
        $inputs = request()->except(['password', 'password_confirmation', 'token']);
        return $this->cleanSensitiveData($inputs);
    }

    private function getRelevantHeaders(): array
    {
        $relevantHeaders = [
            'accept',
            'accept-language',
            'if-none-match',
            'if-modified-since',
            'content-type',
            'user-agent',
            'referer',
            'origin'
        ];

        return collect(request()->headers->all())
            ->only($relevantHeaders)
            ->map(fn($value) => head($value))
            ->all();
    }

    private function getLogLevel(): Level
    {
        return match (strtoupper(request()->method())) {
            'DELETE', 'PUT', 'PATCH' => self::NOTICE,
            'POST' => self::INFO,
            default => self::DEBUG
        };
    }
}
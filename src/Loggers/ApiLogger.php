<?php

namespace Tfo\AdvancedLog\Loggers;

use Illuminate\Support\Facades\Log;

/**
 * Logs API interactions, responses, and performance metrics
 * 
 * @example
 * // Log API request with duration
 * (new ApiLogger('/api/users', 'GET', $response, 235.5))->log();
 * 
 * @example
 * // Log API error
 * (new ApiLogger('/api/orders', 'POST', $errorResponse))->log([
 *     'request_body' => $requestData,
 *     'error_code' => 'ORDER_001'
 * ]);
 */
class ApiLogger extends BaseLogger
{
    public function __construct(
        private string $endpoint,
        private string $method,
        private mixed $response,
        private ?float $duration = null
    ) {
    }

    public function log(array $context = []): void
    {
        $apiContext = [
            'endpoint' => $this->endpoint,
            'method' => $this->method,
            'response_code' => $this->getResponseCode(),
            'duration' => $this->duration ? round($this->duration, 2) . 'ms' : null,
            'timestamp' => now()->format('Y-m-d H:i:s.u'),
            'response_body' => $this->formatResponse()
        ];

        $level = $this->determineLogLevel();

        Log::log(
            $level->name,
            "API {$this->method}: {$this->endpoint}",
            $this->mergeContext(array_merge($apiContext, $context))
        );
    }

    private function getResponseCode(): ?int
    {
        if ($this->response instanceof \Illuminate\Http\Response) {
            return $this->response->status();
        }
        return null;
    }

    private function formatResponse(): array|string|null
    {
        if ($this->response instanceof \Illuminate\Http\Response) {
            $content = $this->response->content();
            return is_string($content) ? $content : json_encode($content);
        }
        return null;
    }

    private function determineLogLevel(): Level
    {
        $code = $this->getResponseCode();

        return match (true) {
            $code >= 500 => self::ERROR,
            $code >= 400 => self::WARNING,
            default => self::INFO
        };
    }
}
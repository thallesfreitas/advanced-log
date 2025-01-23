<?php

namespace Tfo\AdvancedLog\Services\Logging\Notifications;

use Tfo\AdvancedLog\Contracts\NotificationServiceInterface;
use Datadog\LogLevel;

class DataDogNotificationService implements NotificationServiceInterface
{
    private $ddLogger;

    public function __construct()
    {
        if (config('advanced-logger.services.datadog')) {
            \DDTrace\Bootstrap::tracerAndLogger();
            $this->ddLogger = \DDTrace\Bootstrap::getLogger();
        }
    }

    public function send(string $message, ?array $attachment = null): void
    {
        if (!$this->ddLogger || !config('advanced-logger.services.datadog')) {
            return;
        }

        $context = $this->formatContext($attachment);
        $level = $this->getLogLevel($attachment);

        $this->ddLogger->log($level, $message, $context);
    }

    private function getLogLevel(array $attachment = null): string
    {
        $levelMap = [
            'debug' => LogLevel::DEBUG,
            'info' => LogLevel::INFO,
            'warning' => LogLevel::WARNING,
            'error' => LogLevel::ERROR,
            'critical' => LogLevel::CRITICAL
        ];

        if ($attachment && isset($attachment['fields'])) {
            foreach ($attachment['fields'] as $field) {
                if ($field['title'] === 'Nível') {
                    return $levelMap[strtolower($field['value'])] ?? LogLevel::INFO;
                }
            }
        }

        return LogLevel::INFO;
    }

    private function formatContext(array $attachment = null): array
    {
        $context = [
            'env' => config('app.env'),
            'service' => config('app.name')
        ];

        if ($attachment && isset($attachment['fields'])) {
            foreach ($attachment['fields'] as $field) {
                $context[$field['title']] = $field['value'];
            }
        }

        return $context;
    }
}
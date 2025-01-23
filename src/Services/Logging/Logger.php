<?php

namespace Tfo\AdvancedLog\Services\Logging;

use Tfo\AdvancedLog\Contracts\LoggerInterface;
use Tfo\AdvancedLog\Contracts\LogFormatterInterface;
use Tfo\AdvancedLog\Contracts\NotificationServiceInterface;

class Logger implements LoggerInterface
{
    private LogFormatterInterface $formatter;
    private array $services;

    public function __construct(LogFormatterInterface $formatter, array $services)
    {
        $this->formatter = $formatter;
        $this->services = $services;
    }

    public function log(string $level, string $message, array $context = []): void
    {
        try {
            $formatted = $this->formatter->format($level, $message, $context);

            foreach ($this->services as $service) {
                if ($service instanceof NotificationServiceInterface) {
                    $service->send($formatted['message'], $formatted['attachment']);
                }
            }
        } catch (\Throwable $e) {
            // Fallback para log de arquivo em caso de erro
            $this->logToFile('Error in logger: ' . $e->getMessage());
        }
    }

    private function logToFile(string $message): void
    {
        $logPath = storage_path('logs/advanced-logger.log');
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] {$message}" . PHP_EOL;

        file_put_contents($logPath, $logMessage, FILE_APPEND);
    }
}
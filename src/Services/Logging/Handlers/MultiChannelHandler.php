<?php

namespace Tfo\AdvancedLog\Services\Logging\Handlers;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Tfo\AdvancedLog\Contracts\LogFormatterInterface;
use Tfo\AdvancedLog\Contracts\NotificationServiceInterface;

class MultiChannelHandler extends AbstractProcessingHandler
{
    protected LogFormatterInterface $formatter;
    private array $services;

    public function __construct(
        LogFormatterInterface $formatter,
        array $services,
        $level = \Monolog\Level::Debug,
        bool $bubble = true
    ) {
        parent::__construct($level, $bubble);
        $this->formatter = $formatter;
        $this->services = $services;
    }

    protected function write(LogRecord $record): void
    {
        try {
            $formatted = $this->formatter->format(
                strtolower($record->level->name),
                $record->message,
                $record->context
            );

            foreach ($this->services as $service) {
                if ($service instanceof NotificationServiceInterface) {
                    $service->send($formatted['message'], $formatted['attachment']);
                }
            }
        } catch (\Throwable $e) {
            // Fallback para log de arquivo em caso de erro
            $this->logError('Error in MultiChannelHandler: ' . $e->getMessage());
        }
    }

    private function logError(string $message): void
    {
        $logPath = storage_path('logs/advanced-logger-errors.log');
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($logPath, "[{$timestamp}] {$message}" . PHP_EOL, FILE_APPEND);
    }
}
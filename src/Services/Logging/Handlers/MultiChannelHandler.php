<?php

namespace Tfo\AdvancedLog\Services\Logging\Handlers;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Tfo\AdvancedLog\Contracts\LogFormatterInterface;
use Tfo\AdvancedLog\Contracts\NotificationServiceInterface;
use Tfo\AdvancedLog\Support\LogFacade;

class MultiChannelHandler extends AbstractProcessingHandler
{
    protected LogFormatterInterface $customFormatter;
    private array $services;

    public function __construct(LogFormatterInterface $formatter, array $services)
    {
        parent::__construct();
        $this->customFormatter = $formatter;
        $this->services = $services;
    }
    protected function write(LogRecord $record): void
    {
        if (method_exists(LogFacade::class, $record->channel)) {
            LogFacade::{$record->channel}($record->message, $record->context);
            return;
        }

        $formatted = $this->customFormatter->format(
            strtolower($record->level->name),
            $record->message,
            $record->context
        );

        foreach ($this->services as $service) {
            $service->send($formatted['message'], $formatted['attachment']);
        }
    }

    private function logError(string $message): void
    {
        $logPath = storage_path('logs/advanced-logger-errors.log');
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($logPath, "[{$timestamp}] {$message}" . PHP_EOL, FILE_APPEND);
    }
}
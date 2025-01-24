<?php

namespace Tfo\AdvancedLog\Services\Logging\Handlers;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Monolog\Formatter\FormatterInterface;
use Tfo\AdvancedLog\Contracts\NotificationServiceInterface;
use Monolog\Level;

class MultiChannelHandler extends AbstractProcessingHandler
{
    protected ?FormatterInterface $formatter = null;
    private array $services;
    private $customFormatter;

    public function __construct(
        $customFormatter,
        array $services,
        $level = Level::Debug,
        bool $bubble = true
    ) {
        parent::__construct($level, $bubble);
        $this->customFormatter = $customFormatter;
        $this->services = $services;
    }

    protected function write(LogRecord $record): void
    {
        try {
            $formatted = $this->customFormatter->format(
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
<?php

namespace Tfo\AdvancedLog\Services\Logging;

use Monolog\Logger;
use Tfo\AdvancedLog\Services\Logging\Formatters\SlackFormatter;
use Tfo\AdvancedLog\Services\Logging\Handlers\MultiChannelHandler;
use Tfo\AdvancedLog\Services\Logging\Notifications\DataDogNotificationService;
use Tfo\AdvancedLog\Services\Logging\Notifications\SentryNotificationService;
use Tfo\AdvancedLog\Services\Logging\Notifications\SlackNotificationService;

class CustomLoggerFactory
{
    public function __invoke(array $config)
    {
        $logger = new Logger('advanced');

        $handler = new MultiChannelHandler(
            new SlackFormatter(),
            [
                new SlackNotificationService(),
                new SentryNotificationService(),
                new DataDogNotificationService(),
            ]
        );

        $logger->pushHandler($handler);

        return $logger;
    }
}
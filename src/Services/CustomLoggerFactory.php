<?php

namespace Tfo\AdvancedLog\Services\Logging;

use Monolog\Logger;

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
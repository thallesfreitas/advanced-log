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
    // public function __invoke(array $config)
    // {
    //     $logger = new Logger('advanced');

    //     $handler = new MultiChannelHandler(
    //         new SlackFormatter(),
    //         [
    //             new SlackNotificationService(),
    //             new SentryNotificationService(),
    //             new DataDogNotificationService(),
    //         ]
    //     );

    //     $logger->pushHandler($handler);

    //     return $logger;
    // }

    public function __invoke(array $config)
    {
        $logger = new Logger('advanced');

        $env = $config['env'];
        $enabledLogs = explode(',', $config["enabled_$env"]);

        $services = [];
        foreach ($enabledLogs as $level) {
            $levelServices = explode(',', $config["{$level}_services"]);
            foreach ($levelServices as $service) {
                if (!in_array($service, $services)) {
                    $services[] = $this->createNotificationService($service);
                }
            }
        }

        $handler = new MultiChannelHandler(new SlackFormatter(), $services);
        $logger->pushHandler($handler);

        return $logger;
    }

    private function createNotificationService($service)
    {
        return match ($service) {
            'slack' => new SlackNotificationService(),
            'sentry' => new SentryNotificationService(),
            'datadog' => new DataDogNotificationService(),
            default => throw new \InvalidArgumentException("Unknown notification service: $service"),
        };
    }
}
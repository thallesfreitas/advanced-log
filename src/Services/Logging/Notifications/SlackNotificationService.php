<?php

namespace Tfo\AdvancedLog\Services\Logging\Notifications;

use Tfo\AdvancedLog\Contracts\NotificationServiceInterface;
use Tfo\AdvancedLog\Models\SlackNotifier;
use Tfo\AdvancedLog\Services\Notifications\SlackNotification;

class SlackNotificationService implements NotificationServiceInterface
{
    private SlackNotifier $notifier;

    public function __construct()
    {
        $this->notifier = new SlackNotifier();
    }

    public function send(string $message, array $attachment = null): void
    {
        if (!config('advanced-log.services.slack')) {
            return;
        }

        $this->notifier->notify(new SlackNotification($message, $attachment));
    }
}
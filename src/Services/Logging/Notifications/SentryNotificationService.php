<?php

namespace Tfo\AdvancedLog\Services\Logging\Notifications;

use Tfo\AdvancedLog\Contracts\NotificationServiceInterface;
use Sentry\State\Scope;

class SentryNotificationService implements NotificationServiceInterface
{
    public function send(string $message, array $attachment = null): void
    {
        if (!config('advanced-logger.services.sentry') || !config('advanced-logger.sentry.dsn')) {
            return;
        }

        \Sentry\configureScope(function (Scope $scope) use ($attachment) {
            if (isset($attachment['fields'])) {
                foreach ($attachment['fields'] as $field) {
                    $scope->setExtra($field['title'], $field['value']);
                }
            }
        });

        if (isset($attachment['error'])) {
            \Sentry\captureException($attachment['error']);
        } else {
            \Sentry\captureMessage($message);
        }
    }
}
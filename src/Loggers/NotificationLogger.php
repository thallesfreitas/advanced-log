<?php

namespace Tfo\AdvancedLog\Loggers;

use Illuminate\Support\Facades\Log;

/**
 * Logs notification events across different channels
 * 
 * @example
 * // Log email notification
 * (new NotificationLogger('email', 'user@example.com', 'welcome'))->log([
 *     'template' => 'welcome-email',
 *     'variables' => ['name' => 'John']
 * ]);
 * 
 * @example
 * // Log push notification
 * (new NotificationLogger('push', 'device_token_123', 'order_update'))->log([
 *     'title' => 'Order Shipped',
 *     'body' => 'Your order #123 has been shipped'
 * ]);
 */
class NotificationLogger extends BaseLogger
{
    public function __construct(
        private string $channel,
        private string $recipient,
        private string $type,
        private ?string $sender = null
    ) {
        $this->sender = $sender ?? auth()->user()?->email ?? 'system';
    }

    public function log(array $context = []): void
    {
        $notificationContext = [
            'channel' => $this->channel,
            'recipient' => $this->recipient,
            'type' => $this->type,
            'sender' => $this->sender,
            'timestamp' => now()->format('Y-m-d H:i:s.u'),
            'notification_id' => uniqid('notif_', true)
        ];

        Log::log(
            $this->getLogLevel()->name,
            "Notification sent: {$this->type}",
            $this->mergeContext(array_merge($notificationContext, $context))
        );
    }

    private function getLogLevel(): Level
    {
        return match ($this->channel) {
            'sms', 'push' => self::NOTICE,
            'slack', 'telegram' => self::INFO,
            'email' => self::INFO,
            default => self::DEBUG
        };
    }
}
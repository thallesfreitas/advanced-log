<?php

namespace Tfo\AdvancedLog\Services\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class SlackNotification extends Notification
{
    private string $message;
    private ?array $attachment;

    public function __construct(string $message, ?array $attachment = null)
    {
        $this->message = $message;
        $this->attachment = $attachment;
    }

    public function via($notifiable): array
    {
        return ['slack'];
    }

    public function toSlack($notifiable): SlackMessage
    {
        $slack = (new SlackMessage)
            ->from(config('advanced-log.slack.username'))
            ->to(config('advanced-log.slack.channel'))
            ->content($this->message);

        if ($this->attachment) {
            $slack->attachment(function ($attachment) {
                if (isset($this->attachment['color'])) {
                    $attachment->color($this->attachment['color']);
                }

                if (isset($this->attachment['fields'])) {
                    $formattedFields = [];
                    foreach ($this->attachment['fields'] as $field) {
                        if (isset($field['title']) && isset($field['value'])) {
                            $formattedFields[$field['title']] = $field['value'];
                        }
                    }
                    if (!empty($formattedFields)) {
                        $attachment->fields($formattedFields);
                    }
                }
            });
        }

        return $slack;
    }
}
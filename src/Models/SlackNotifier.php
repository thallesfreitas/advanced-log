<?php

namespace Tfo\AdvancedLog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SlackNotifier extends Model
{
    use Notifiable;

    public function routeNotificationForSlack(): string
    {
        return config('advanced-log.services.slack.webhook_url');
    }
}
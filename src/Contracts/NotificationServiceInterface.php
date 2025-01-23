<?php

namespace Tfo\AdvancedLog\Contracts;

interface NotificationServiceInterface
{
    public function send(string $message, ?array $attachment = null): void;

}
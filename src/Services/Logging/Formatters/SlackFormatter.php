<?php

namespace Tfo\AdvancedLog\Services\Logging\Formatters;

use Tfo\AdvancedLog\Contracts\LogFormatterInterface;

class SlackFormatter implements LogFormatterInterface
{
    protected array $levelEmojis = [
        'debug' => '🔍',
        'info' => 'ℹ️',
        'warning' => '⚠️',
        'error' => '❌',
        'critical' => '🚨'
    ];

    protected array $levelColors = [
        'debug' => '#7F8C8D',
        'info' => '#3498DB',
        'warning' => '#F1C40F',
        'error' => '#E74C3C',
        'critical' => '#C0392B'
    ];

    public function format(string $level, string $message, array $context = []): array
    {
        $emoji = $this->levelEmojis[$level] ?? '📋';
        $color = $this->levelColors[$level] ?? '#000000';

        return [
            'message' => sprintf("%s *[%s]* %s", $emoji, strtoupper($level), $message),
            'attachment' => $this->formatAttachment($level, $context, $color)
        ];
    }

    private function formatAttachment(string $level, array $context, string $color): array
    {
        $fields = [
            [
                'title' => 'Nível',
                'value' => ucfirst($level),
                'short' => true
            ],
            [
                'title' => 'Ambiente',
                'value' => ucfirst(config('app.env')),
                'short' => true
            ]
        ];

        foreach ($context as $key => $value) {
            $fields[] = [
                'title' => ucfirst($key),
                'value' => is_array($value) || is_object($value)
                    ? json_encode($value, JSON_PRETTY_PRINT)
                    : (string) $value,
                'short' => false
            ];
        }

        return [
            'color' => $color,
            'fields' => $fields
        ];
    }
}
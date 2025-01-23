<?php

namespace Tfo\AdvancedLog\Loggers;

use Illuminate\Support\Facades\Log;

/**
 * Logs security-related events and potential threats
 * 
 * @example
 * // Log failed login attempt
 * (new SecurityLogger('Login Failed'))->log([
 *     'email' => 'user@example.com',
 *     'attempts' => 3
 * ]);
 * 
 * @example
 * // Log suspicious activity
 * (new SecurityLogger('Suspicious API Access'))->log([
 *     'reason' => 'Multiple requests from same IP',
 *     'count' => 100,
 *     'timeframe' => '1 minute'
 * ]);
 */
class SecurityLogger extends BaseLogger
{
    private string $event;
    private array $securityLevels = [
        'low' => self::WARNING,
        'medium' => self::ALERT,
        'high' => self::CRITICAL
    ];

    public function __construct(
        string $event,
        private string $severity = 'medium'
    ) {
        $this->event = $event;
    }

    public function log(array $context = []): void
    {
        $securityContext = [
            'event' => $this->event,
            'severity' => $this->severity,
            'session_id' => session()->getId(),
            'timestamp' => now()->format('Y-m-d H:i:s.u'),
            'route' => request()->route()?->getName()
        ];

        $level = $this->securityLevels[$this->severity] ?? self::WARNING;
        $cleanContext = $this->cleanSensitiveData($context);

        Log::log(
            $level->name,
            "Security Alert: {$this->event}",
            $this->mergeContext(array_merge($securityContext, $cleanContext))
        );
    }
}
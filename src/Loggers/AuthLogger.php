<?php

namespace Tfo\AdvancedLog\Loggers;

use Illuminate\Support\Facades\Log;
use Monolog\Level;

/**
 * Logs authentication and authorization events
 * 
 * @example
 * // Log successful login
 * (new AuthLogger('login_success'))->log([
 *     'remember' => true,
 *     'device' => 'iPhone 13'
 * ]);
 * 
 * @example
 * // Log failed permission check
 * (new AuthLogger('permission_denied'))->log([
 *     'permission' => 'delete-users',
 *     'resource' => 'User#123'
 * ]);
 */
class AuthLogger extends BaseLogger
{
    private array $criticalEvents = ['password_reset', 'mfa_disabled', 'api_key_created'];

    public function __construct(
        private string $event,
        private ?array $metadata = []
    ) {
    }

    public function log(array $context = []): void
    {
        $authContext = [
            'event' => $this->event,
            'user_id' => auth()->id(),
            'email' => auth()->user()?->email,
            'roles' => $this->getUserRoles(),
            'permissions' => $this->getUserPermissions(),
            'session_id' => session()->getId(),
            'timestamp' => now()->format('Y-m-d H:i:s.u'),
            'metadata' => $this->metadata
        ];

        Log::log(
            $this->getLogLevel()->name,
            "Auth {$this->event}",
            $this->mergeContext(array_merge($authContext, $context))
        );
    }

    private function getLogLevel(): Level
    {
        if (in_array($this->event, $this->criticalEvents)) {
            return self::CRITICAL;
        }

        return match ($this->event) {
            'login_failed', 'logout_forced', 'password_changed' => self::WARNING,
            'login_success', 'logout' => self::INFO,
            default => self::INFO
        };
    }

    private function getUserRoles(): array
    {
        if (!auth()->check()) {
            return [];
        }

        return method_exists(auth()->user(), 'getRoleNames')
            ? auth()->user()->getRoleNames()->toArray()
            : [];
    }

    private function getUserPermissions(): array
    {
        if (!auth()->check()) {
            return [];
        }

        return method_exists(auth()->user(), 'getPermissionNames')
            ? auth()->user()->getPermissionNames()->toArray()
            : [];
    }
}
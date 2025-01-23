<?php

namespace Tfo\AdvancedLog\Loggers;

use Illuminate\Support\Facades\Log;

/**
 * Logs audit events for tracking changes to models and resources
 * 
 * @example
 * // Log a user update
 * (new AuditLogger('update', 'User', 123, [
 *     'name' => ['old' => 'John', 'new' => 'Johnny'],
 *     'email' => ['old' => 'john@mail.com', 'new' => 'johnny@mail.com']
 * ]))->log();
 * 
 * @example
 * // Log a record deletion
 * (new AuditLogger('delete', 'Post', 456))->log(['reason' => 'spam']);
 */
class AuditLogger extends BaseLogger
{
    private string $action;
    private string $model;
    private mixed $id;
    private ?array $changes;
    private ?string $user;

    public function __construct(
        string $action,
        string $model,
        mixed $id,
        array $changes = null,
        string $user = null
    ) {
        $this->action = $action;
        $this->model = $model;
        $this->id = $id;
        $this->changes = $changes;
        $this->user = $user;
    }

    public function log(array $context = []): void
    {
        $auditContext = [
            'action' => $this->action,
            'model' => $this->model,
            'id' => $this->id,
            'changes' => $this->changes,
            'user' => $this->user ?? auth()->user()?->email ?? 'system',
            'timestamp' => now()->format('Y-m-d H:i:s.u')
        ];

        Log::info(
            "Audit: {$this->action} on {$this->model} #{$this->id}",
            $this->mergeContext(array_merge($auditContext, $context))
        );
    }
}
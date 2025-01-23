<?php
namespace Tfo\AdvancedLog\Services\Logging;

use Illuminate\Log\LogManager;
use Illuminate\Support\Traits\Macroable;

class CustomLogger extends LogManager
{
    use Macroable;

    public function __construct($app)
    {
        parent::__construct($app);
        self::registerMacros();
    }

    protected static function registerMacros()
    {
        if (!static::hasMacro('audit')) {
            static::macro('audit', function (string $action, string $model, mixed $id, array $changes = [], ?string $user = null) {
                return static::info("Audit: {$action} on {$model} #{$id}", [
                    'action' => $action,
                    'model' => $model,
                    'id' => $id,
                    'changes' => $changes,
                    'user' => $user ?? auth()->user()?->email ?? 'system',
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
            });
        }

        // Adicione outros macros da mesma forma...
    }
}
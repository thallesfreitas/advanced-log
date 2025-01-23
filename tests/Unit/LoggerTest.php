<?php

namespace Tfo\AdvancedLog\Tests\Unit;

use Tfo\AdvancedLog\Tests\TestCase;
use Tfo\AdvancedLog\Facades\Log;
use Mockery;

class LoggerTest extends TestCase
{
    public function test_can_log_simple_message(): void
    {
        $this->expectNotToPerformAssertions();

        Log::log('info', 'Test message');
    }

    public function test_can_log_with_context(): void
    {
        $this->expectNotToPerformAssertions();

        Log::log('error', 'Error message', [
            'user_id' => 1,
            'action' => 'test'
        ]);
    }
}
<?php

namespace Tfo\AdvancedLog\Tests\Unit;

use Illuminate\Support\Facades\Log;
use Tfo\AdvancedLog\Tests\TestCase;
use Exception;

class LoggerTest extends TestCase
{
    public function test_default_log_levels()
    {
        $this->expectNotToPerformAssertions();

        Log::emergency('Test emergency');
        Log::alert('Test alert');
        Log::critical('Test critical');
        Log::error('Test error');
        Log::warning('Test warning');
        Log::notice('Test notice');
        Log::info('Test info');
        Log::debug('Test debug');
    }

    public function test_log_with_context()
    {
        $this->expectNotToPerformAssertions();

        Log::error('Test error', ['key' => 'value']);
    }

    public function test_log_exception()
    {
        $this->expectNotToPerformAssertions();

        try {
            throw new Exception('Test exception');
        } catch (Exception $e) {
            Log::error('Exception test', ['exception' => $e]);
        }
    }

    public function test_performance_macro()
    {
        $this->expectNotToPerformAssertions();
        Log::performance('Test Operation', 1500);
    }

    public function test_audit_macro()
    {
        $this->expectNotToPerformAssertions();
        Log::audit('update', 'User', 1, ['name' => 'Test']);
    }

    public function test_security_macro()
    {
        $this->expectNotToPerformAssertions();
        Log::security('Failed Login', ['email' => 'test@test.com']);
    }
}
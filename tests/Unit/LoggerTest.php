<?php

namespace Tfo\AdvancedLog\Tests\Unit;

use Tfo\AdvancedLog\Facades\ALog;
use Tfo\AdvancedLog\Tests\TestCase;
use Exception;

class LoggerTest extends TestCase
{
    public function test_default_log_levels()
    {
        $this->expectNotToPerformAssertions();

        ALog::emergency('Test emergency');
        ALog::alert('Test alert');
        ALog::critical('Test critical');
        ALog::error('Test error');
        ALog::warning('Test warning');
        ALog::notice('Test notice');
        ALog::info('Test info');
        ALog::debug('Test debug');
    }

    public function test_log_with_context()
    {
        $this->expectNotToPerformAssertions();

        ALog::error('Test error', ['key' => 'value']);
    }

    public function test_log_exception()
    {
        $this->expectNotToPerformAssertions();

        try {
            throw new Exception('Test exception');
        } catch (Exception $e) {
            ALog::error('Exception test', ['exception' => $e]);
        }
    }

    public function test_performance_macro()
    {
        $this->expectNotToPerformAssertions();
        ALog::performance('Test Operation', 1500);
    }

    public function test_audit_macro()
    {
        $this->expectNotToPerformAssertions();
        ALog::audit('update', 'User', 1, ['name' => 'Test']);
    }

    public function test_security_macro()
    {
        $this->expectNotToPerformAssertions();
        ALog::security('Failed Login', ['email' => 'test@test.com']);
    }
}
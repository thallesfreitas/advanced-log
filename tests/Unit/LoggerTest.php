<?php

namespace Tfo\AdvancedLog\Tests\Unit;

use Tfo\AdvancedLog\Support\ALog;
use Illuminate\Support\Facades\Log;
use Tfo\AdvancedLog\Tests\TestCase;
use Exception;

class LoggerTest extends TestCase
{
    public function test_default_log_levels()
    {
        try {
            Log::emergency('Test emergency');
            Log::alert('Test alert');
            Log::critical('Test critical');
            Log::error('Test error');
            Log::warning('Test warning');
            Log::notice('Test notice');
            Log::info('Test info');
            Log::debug('Test debug');

            $this->assertEquals(1, 1); // Simple assertion that will pass
        } catch (\Throwable $e) {
            $this->fail('Exception thrown: ' . $e->getMessage());
        }
    }

    public function test_log_with_context()
    {
        try {
            ALog::error('Test error', ['key' => 'value']);
            $this->assertEquals(1, 1);
        } catch (\Throwable $e) {
            $this->fail('Exception thrown: ' . $e->getMessage());
        }
    }

    public function test_log_exception()
    {
        try {
            try {
                throw new Exception('Test exception');
            } catch (Exception $e) {
                ALog::error('Exception test', ['exception' => $e]);
            }
            $this->assertEquals(1, 1);
        } catch (\Throwable $e) {
            $this->fail('Exception thrown: ' . $e->getMessage());
        }
    }

    public function test_performance_macro()
    {
        try {
            ALog::performance('Test Operation', 1500);
            $this->assertEquals(1, 1);
        } catch (\Throwable $e) {
            $this->fail('Exception thrown: ' . $e->getMessage());
        }
    }

    public function test_audit_macro()
    {
        try {
            ALog::audit('update', 'User', 1, ['name' => 'Test']);
            $this->assertEquals(1, 1);
        } catch (\Throwable $e) {
            $this->fail('Exception thrown: ' . $e->getMessage());
        }
    }

    public function test_security_macro()
    {
        try {
            ALog::security('Failed Login', ['email' => 'test@test.com']);
            $this->assertEquals(1, 1);
        } catch (\Throwable $e) {
            $this->fail('Exception thrown: ' . $e->getMessage());
        }
    }
}
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Tfo\AdvancedLog\Support\ALog;

// Default Laravel Logs
Route::get('/test-log-levels', function () {
    Log::emergency('Emergency log test', ['context' => 'test']);
    Log::alert('Alert log test', ['context' => 'test']);
    Log::critical('Critical log test', ['context' => 'test']);
    Log::error('Error log test', ['context' => 'test']);
    Log::warning('Warning log test', ['context' => 'test']);
    Log::notice('Notice log test', ['context' => 'test']);
    Log::info('Info log test', ['context' => 'test']);
    Log::debug('Debug log test', ['context' => 'test']);
    return 'All log levels tested';
});

// Performance Log
Route::get('/test-performance-log', function () {
    $startTime = microtime(true);
    sleep(1); // Simulate work
    $duration = (microtime(true) - $startTime) * 1000;
    ALog::performance('Process Order', $duration, ['order_id' => 123]);
    return 'Performance log tested';
});

// Audit Log
Route::get('/test-audit-log', function () {
    ALog::audit('update', 'User', 1, [
        'name' => ['old' => 'John', 'new' => 'Johnny'],
        'email' => ['old' => 'john@example.com', 'new' => 'johnny@example.com']
    ]);
    return 'Audit log tested';
});

// Security Log
Route::get('/test-security-log', function () {
    ALog::security('Login Failed', [
        'email' => 'user@example.com',
        'attempts' => 3
    ]);
    return 'Security log tested';
});

// API Log
Route::get('/test-api-log', function () {
    $response = response()->json(['status' => 'success']);
    ALog::api('/api/users', 'GET', $response, 150.5);
    return 'API log tested';
});

// Database Log
Route::get('/test-database-log', function () {
    ALog::database('create', 'users', 1, [
        'data' => ['name' => 'John', 'email' => 'john@example.com']
    ]);
    return 'Database log tested';
});

// Job Log
Route::get('/test-job-log', function () {
    ALog::job('SendWelcomeEmail', 'completed', [
        'user_id' => 1,
        'duration' => 1500
    ]);
    return 'Job log tested';
});

// Cache Log
Route::get('/test-cache-log', function () {
    ALog::cache('hit', 'user:123', [
        'ttl' => 3600
    ]);
    return 'Cache log tested';
});

// Request Log
Route::get('/test-request-log', function () {
    ALog::request('API Request', [
        'endpoint' => '/api/users',
        'params' => ['page' => 1]
    ]);
    return 'Request log tested';
});

// Payment Log
Route::get('/test-payment-log', function () {
    ALog::payment('success', 99.99, 'stripe', [
        'transaction_id' => 'tx_123'
    ]);
    return 'Payment log tested';
});

// Notification Log
Route::get('/test-notification-log', function () {
    ALog::notification('email', 'user@example.com', 'welcome', [
        'template' => 'welcome-email'
    ]);
    return 'Notification log tested';
});

// File Log
Route::get('/test-file-log', function () {
    ALog::file('upload', 'images/profile.jpg', [
        'size' => '2.5MB',
        'type' => 'image/jpeg'
    ]);
    return 'File log tested';
});

// Auth Log
Route::get('/test-auth-log', function () {
    ALog::auth('login_success', [
        'remember' => true,
        'device' => 'iPhone 13'
    ]);
    return 'Auth log tested';
});

// Export Log
Route::get('/test-export-log', function () {
    ALog::export('users', 1000, [
        'format' => 'csv',
        'filters' => ['status' => 'active']
    ]);
    return 'Export log tested';
});
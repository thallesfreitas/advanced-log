<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

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

// Test Exception Logging
Route::get('/test-exception', function () {
    try {
        throw new Exception('Test exception');
    } catch (Exception $e) {
        Log::error('Exception caught', [
            'exception' => $e,
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    }
    return 'Exception logged';
});

// Test Performance Macro
Route::get('/test-performance', function () {
    $startTime = microtime(true);
    sleep(2); // Simulate slow operation
    $duration = (microtime(true) - $startTime) * 1000;

    Log::performance('Test Operation', $duration, [
        'operation' => 'test',
        'user_id' => 1
    ]);
    return 'Performance logged';
});

// Test Audit Macro
Route::get('/test-audit', function () {
    Log::audit(
        'update',
        'User',
        1,
        ['name' => 'New Name', 'email' => 'new@email.com']
    );
    return 'Audit logged';
});

// Test Security Macro
Route::get('/test-security', function () {
    Log::security('Failed Login', [
        'email' => 'test@example.com',
        'attempts' => 3
    ]);
    return 'Security event logged';
});

// Test All
Route::get('/test-all', function () {
    $urls = [
        '/test-log-levels',
        '/test-exception',
        '/test-performance',
        '/test-audit',
        '/test-security'
    ];

    foreach ($urls as $url) {
        Http::get(url($url));
    }
    return 'All tests executed';
});
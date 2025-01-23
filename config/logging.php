<?php

return [
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single', 'custom'],
            'ignore_exceptions' => false,
        ],

        'custom' => [
            'driver' => 'custom',
            'via' => Tfo\AdvancedLog\Services\Logging\CustomLogger::class,
        ],
    ],
];
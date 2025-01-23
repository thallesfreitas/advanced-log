<?php

return [
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['advanced'],
            'ignore_exceptions' => false,
        ],

        'advanced' => [
            'driver' => 'advanced'
        ],
    ]
];
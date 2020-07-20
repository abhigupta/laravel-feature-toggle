<?php

return [
    'provider' => 'splitio',

    'splitio' => [
        'api_key' => env('SPLITIO_API_KEY', 'localhost'),

        'factory' => [
            'cache' => [
                'adapter' => 'predis',

                'options' => [
                    'prefix' => env('SPLITIO_REDIS_PREFIX', '')
                ],

                'parameters' => [
                    'scheme' => env('SPLITIO_REDIS_SCHEME', 'tcp'),
                    'host' => env('SPLITIO_REDIS_HOST', 'localhost'),
                    'port' => env('SPLITIO_REDIS_PORT', 6279),
                    'password' => env('SPLITIO_REDIS_PASSWORD')
                ]
            ],

            'log' => ['adapter' => 'stdout', 'level' => 'error']
        ]
    ]
];

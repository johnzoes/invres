<?php

return [

    // ...

    'connections' => [
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'host' => env('VITE_PUSHER_HOST', 'ws-us2.pusher.com'),
                'port' => env('VITE_PUSHER_PORT', 443),
                'scheme' => env('VITE_PUSHER_SCHEME', 'https'),
                'encrypted' => true,
                'useTLS' => true,
            ],
        ],
        // otras conexiones, si las tienes
    ],

    // ...

];

<?php

return [
    'production' => [
        // Solo los providers necesarios para broadcasting
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        App\Providers\BroadcastServiceProvider::class,
    ],
];
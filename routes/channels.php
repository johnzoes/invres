<?php

use Illuminate\Support\Facades\Broadcast;

// ...

Broadcast::channel('notifications', function () {
    return true; // Permite a cualquier usuario suscribirse al canal
});

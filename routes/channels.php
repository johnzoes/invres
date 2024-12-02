<?php

use Illuminate\Support\Facades\Broadcast;

// ...

Broadcast::channel('notifications', function ($user) {
    // Log de autorización de canal
    \Log::info('Autorización de canal', [
        'user' => $user ? $user->id : 'invitado'
    ]);
    return true; // O añade tu lógica de autorización
});

Broadcast::channel('reservas.{reservaId}', function ($user, $reservaId) {
    // Lógica para autenticar el acceso al canal
    return true;  // o alguna lógica que determine si el usuario puede escuchar este canal
});


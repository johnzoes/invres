<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('notifications', function ($user) {
    return true; // Permite todos los usuarios temporalmente para pruebas
});
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Registrar rutas para broadcasting
        Broadcast::routes();

        // Cargar los canales definidos en routes/channels.php
        require base_path('routes/channels.php');
    }
}

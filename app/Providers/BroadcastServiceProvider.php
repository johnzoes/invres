<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services for broadcasting.
     *
     * @return void
     */
    public function boot()
    {
        // Registrar rutas de broadcasting sin autenticación
        Broadcast::routes();

        // Cargar las definiciones de los canales desde routes/channels.php
        require base_path('routes/channels.php');
    }
}

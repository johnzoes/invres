<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notificacion;
use Illuminate\Support\ServiceProvider;
use Illuminate\Broadcasting\BroadcastServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $notificaciones = auth()->check()
                ? auth()->user()->unreadNotifications
                : collect();

            $view->with('notificaciones', $notificaciones);
        });
    }

    public function register(): void
    {
        $this->app->register(BroadcastServiceProvider::class);
        $this->app->register(\App\Providers\BroadcastServiceProvider::class);
    }
}
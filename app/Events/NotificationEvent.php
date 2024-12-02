<?php


// app/Events/NotificationEvent.php

namespace App\Events;

use App\Models\Reserva;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reserva;

    public function __construct(Reserva $reserva)
    {
        $this->reserva = $reserva;
        
        // Log adicional en el constructor
        \Log::info('Creando evento de notificación', [
            'reserva_id' => $reserva->id
        ]);
    }

    public function broadcastOn()
    {
        // Log del canal
        \Log::info('Canal de broadcast', [
            'canal' => 'notifications'
        ]);
        return new Channel('notifications');
    }

    public function broadcastWith()
    {
        $data = [
            'mensaje' => 'Nueva reserva creada',
            'reserva_id' => $this->reserva->id,
        ];

        // Log de los datos a transmitir
        \Log::info('Datos de broadcast', $data);

        return $data;
    }
}
<?php


namespace App\Events;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class NotificationEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mensaje;

    public function __construct($mensaje)
    {

        $this->mensaje = $mensaje;
    }

    public function broadcastOn()
    {
        \Log::info("Evento emitido en el canal notifications");
        return new Channel('notifications');
    }

    public function broadcastAs()
{
    return 'NotificationEvent';
}

    

    public function broadcastWith()
    {
        return [
            'mensaje' => $this->mensaje,
        ];
    }
}

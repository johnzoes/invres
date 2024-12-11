<?php
// app/Events/NotificationEvent.php
namespace App\Events;

use App\Models\Reserva;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mensaje;
    private $reserva;

    public function __construct(Reserva $reserva)
    {
        $this->reserva = $reserva;
        $this->mensaje = "Nueva reserva creada #{$reserva->id}";
    }

    public function broadcastOn(): Channel
    {
        return new Channel('notifications');
    }

    public function broadcastAs()
    {
        return 'NotificationEvent';
    }

    public function broadcastWith(): array
    {
        return [
            'mensaje' => $this->mensaje,
            'reserva_id' => $this->reserva->id,
            'timestamp' => now()->toISOString()
        ];
    }
}
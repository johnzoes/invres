<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NotificacionReserva extends Notification implements ShouldQueue
{
    use Queueable;

    private $notificationData;

    public function __construct($notificationData)
    {
        $this->notificationData = $notificationData;
    }

    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'mensaje' => $this->notificationData['mensaje'] ?? 'Mensaje no disponible',
            'reserva_id' => $this->notificationData['reserva_id'] ?? null,
            'usuario_remitente' => $this->notificationData['usuario_remitente'] ?? 'Sistema',
            'usuario_destinatario' => $this->notificationData['usuario_destinatario'] ?? 'Usuario'
        ];
    }
    

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from('santos31zorrilla@gmail.com', 'Invres System')
            ->subject('Actualización de Estado de Reserva')
            ->greeting('Hola, ' . $notifiable->nombre)
            ->line($this->notificationData['mensaje'])
            ->action('Ver Reserva', url('/reservas/' . $this->notificationData['reserva_id']))
            ->line('Gracias por usar nuestro sistema.');
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'mensaje' => $this->notificationData['mensaje'],
            'reserva_id' => $this->notificationData['reserva_id'],
            'usuario_remitente' => $this->notificationData['usuario_remitente'],
            'usuario_destinatario' => $this->notificationData['usuario_destinatario'],
        ]);
    }
}

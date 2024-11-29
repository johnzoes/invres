import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import * as jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

window.Pusher = Pusher;

Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '5763113ca40d07fbd4c0',
    cluster: 'us2', // Ajusta el cluster a tu configuración
    forceTLS: true,

});




window.Echo.channel('notifications')

    .listen('.NotificationEvent', (data) => {
        console.log('Evento recibido en el canal:', data);
    })
    .on('pusher:subscription_succeeded', () => {
        console.log('Suscripción exitosa al canal notifications');
    })
    .on('pusher:subscription_error', (status) => {
        console.error('Error al suscribirse al canal notifications:', status);
    });

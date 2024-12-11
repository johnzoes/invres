import Echo from 'laravel-echo'; 
import Pusher from 'pusher-js';
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';


window.Pusher = Pusher;
Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '5763113ca40d07fbd4c0',
    cluster: 'us2',
    forceTLS: true
});

const notyf = new Notyf({
    position: {
        x: 'right',
        y: 'top', 
    },
    duration: 5000,
    types: [
        {
            type: 'success',
            background: '#28a745',
        }
    ]
 });

window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('✅ Conectado a Pusher');
});

window.Echo.channel('notifications')
   .listen('.NotificationEvent', (data) => {
       console.log(data); // Debug
       notyf.success(typeof data === 'string' ? data : data.mensaje);
   });
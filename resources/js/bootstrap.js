import Echo from 'laravel-echo';
import axios from 'axios';

window.axios = axios;

// Configurar CSRF Token para solicitudes de Axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Inicializar Pusher y Laravel Echo
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY, // Asegúrate de que este valor esté en tu .env
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, // Asegúrate de que este valor esté en tu .env
    wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

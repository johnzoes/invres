    import Echo from 'laravel-echo';
    import Pusher from 'pusher-js';
    import $ from 'jquery';
    import { Notyf } from 'notyf';
    import 'notyf/notyf.min.css'; // Importa el archivo CSS de Notyf
// resources/js/app.js
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()


    window.Notyf = Notyf; // Agrega Notyf a la ventana global (si es necesario)

    document.addEventListener('DOMContentLoaded', () => {
        const notyf = new Notyf();

        // Escuchar si hay un mensaje de éxito y mostrar la notificación
        if (window.Laravel && window.Laravel.successMessage) {
            notyf.success(window.Laravel.successMessage);
        }
    });


    window.$ = window.jQuery = $;

    window.Pusher = Pusher;

    // Inicializa Laravel Echo
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '5763113ca40d07fbd4c0',
        cluster: 'us2',
        encrypted: true,  // Asegúrate de que esté habilitado si es necesario

    });

    
    window.Echo.channel('notifications')
    .listen('.NotificationEvent', (event) => {
        console.log('Evento recibido');
        console.error('Datos del evento:', event);
    });

// Agregar log de conexión
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('Pusher conectado correctamente');
});

// Manejar errores de conexión
window.Echo.connector.pusher.connection.bind('error', (err) => {
    console.error('Error de conexión Pusher:', err);
});

    // Escuchar el canal global de notificaciones
    window.Echo.channel('notifications')  // Canal global 'notifications'
        .listen('.NotificationEvent', (event) => {  // Asegúrate de usar el evento correcto
            console.log('Nueva notificación de reserva:', event);

            // Aquí puedes manejar las notificaciones en el frontend
            // Actualizar el contador de notificaciones (si estás usando una interfaz con badge)
            const notificationBadge = document.querySelector('#notificationButton span');
            if (notificationBadge) {
                let currentCount = parseInt(notificationBadge.textContent) || 0;
                notificationBadge.textContent = currentCount + 1;
            } else {
                const newBadge = document.createElement('span');
                newBadge.className = 'absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 rounded-full bg-red-500 px-2 text-white text-xs';
                newBadge.textContent = 1;
                document.getElementById('notificationButton').appendChild(newBadge);
            }

            // Mostrar la alerta con el mensaje de la nueva reserva
            alert(`Nueva reserva: ${event.mensaje}`);
        });




        function fetchAndUpdateContent() {
            // Realizar la solicitud AJAX para obtener el contenido actualizado
            $.ajax({
                url: window.location.href,  // Mantener la misma URL
                method: 'GET',
                success: function(response) {
                    // Suponiendo que la estructura de tu página tiene un contenedor principal que se debe actualizar
                    var newContent = $(response).find('#main-content'); // Asumiendo que el id de tu contenido principal es 'main-content'
                    
                    // Actualizar solo la parte relevante de la página con el nuevo contenido
                    $('#main-content').html(newContent.html());
        
                    // También puedes cambiar el título de la página si lo deseas
                    document.title = $(response).find('title').text();
        
                    // Actualizar el historial sin recargar la página
                    history.pushState(null, '', window.location.href);
                },
                error: function(xhr, status, error) {
                    console.error("Error al recargar contenido:", error);
                }
            });
        }
        
        // Ejecutar la recarga cada 10 segundos
        setInterval(fetchAndUpdateContent, 10000);
        
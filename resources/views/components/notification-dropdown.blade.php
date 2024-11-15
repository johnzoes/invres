<!-- resources/views/components/notification-dropdown.blade.php -->
<div id="notificationDropdown" class="hidden fixed left-64 top-10 w-96 bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden z-50">
    <!-- Encabezado -->
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-200">Notificaciones</h2>
    </div>
    
    <!-- Lista de Notificaciones -->
    <div class="max-h-64 overflow-y-auto">
        @forelse($notificaciones as $notificacion)
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                <p class="font-semibold text-gray-900 dark:text-gray-200">{{ $notificacion->data['mensaje'] ?? 'Mensaje no disponible' }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Enviado por: {{ $notificacion->data['usuario_remitente'] ?? 'No disponible' }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Para: {{ $notificacion->data['usuario_destinatario'] ?? 'No disponible' }}</p>
                <div class="mt-2 flex justify-between items-center">
                    @if($notificacion->read_at)
                        <span class="text-xs text-green-500">Leído</span>
                    @else
                        <form action="{{ route('notificaciones.marcarLeida', $notificacion->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded">
                                Marcar como leída
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('reservas.show', ['reserva' => $notificacion->data['reserva_id'] ?? 0]) }}" 
                       class="text-xs bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded ml-2">
                        Ver Reserva
                    </a>
                </div>
            </div>
        @empty
            <p class="p-4 text-gray-500 dark:text-gray-400">No hay notificaciones</p>
        @endforelse
    </div>
</div>

<!-- JavaScript para manejar el dropdown -->
<script>
    const notificationButton = document.getElementById('notificationButton');
    const notificationDropdown = document.getElementById('notificationDropdown');

    notificationButton.addEventListener('click', function(event) {
        event.stopPropagation();
        notificationDropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', function(event) {
        if (!notificationButton.contains(event.target) && !notificationDropdown.contains(event.target)) {
            notificationDropdown.classList.add('hidden');
        }
    });
</script>

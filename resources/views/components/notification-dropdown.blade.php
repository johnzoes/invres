<!-- Dropdown de notificaciones -->
<div id="notificationDropdown" class="hidden fixed z-[60]
    md:w-96 w-full 
    md:top-10 top-auto
    md:left-64 left-0
    md:h-auto h-[90vh]
    bottom-0">
    
    <!-- Overlay para móviles -->
    <div class="fixed inset-0 bg-black bg-opacity-50 md:hidden"></div>
    
    <!-- Contenedor principal -->
    <div class="relative h-full md:h-auto">
        <!-- Panel de notificaciones -->
        <div class="absolute w-full md:relative bottom-0 
            bg-gray-900 
            rounded-t-2xl md:rounded-lg 
            shadow-xl">
            
            <!-- Indicador de arrastre para móviles -->
            <div class="h-1.5 w-12 bg-gray-600 rounded-full mx-auto my-2 md:hidden"></div>
            
            <!-- Encabezado -->
            <div class="flex items-center justify-between p-4 border-b border-gray-700">
                <h2 class="text-lg font-semibold text-white">Notificaciones</h2>
                <button id="closeNotifications" class="md:hidden text-gray-400 hover:text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Lista de notificaciones -->
            <div class="overflow-y-auto max-h-[calc(90vh-10rem)] md:max-h-96">
                @forelse($notificaciones->sortByDesc('created_at') as $notificacion)
                    <div class="p-4 border-b border-gray-700 hover:bg-gray-800">
                        <p class="font-semibold text-gray-200">{{ $notificacion->data['mensaje'] ?? 'Mensaje no disponible' }}</p>
                        <p class="text-sm text-gray-400">Enviado por: {{ $notificacion->data['usuario_remitente'] ?? 'No disponible' }}</p>
                        <p class="text-sm text-gray-400">Para: {{ $notificacion->data['usuario_destinatario'] ?? 'No disponible' }}</p>
                        <div class="mt-2 flex flex-col sm:flex-row gap-2">
                            @if($notificacion->read_at)
                                <span class="text-xs text-green-500">Leído</span>
                            @else
                                <form action="{{ route('notificaciones.marcarLeida', $notificacion->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full sm:w-auto text-xs bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                                        Marcar como leída
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('reservas.show', ['reserva' => $notificacion->data['reserva_id'] ?? 0]) }}" 
                               class="text-center text-xs bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">
                                Ver Reserva
                            </a>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">
                            {{ $notificacion->created_at->diffForHumans() }}
                        </p>
                    </div>
                @empty
                    <p class="p-4 text-gray-400">No hay notificaciones</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationButton = document.getElementById('notificationButton');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const closeNotifications = document.getElementById('closeNotifications');

    function toggleNotifications(e) {
        e?.preventDefault();
        e?.stopPropagation();
        
        const isHidden = notificationDropdown.classList.contains('hidden');
        
        if (isHidden) {
            // Abrir notificaciones
            notificationDropdown.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            // Cerrar notificaciones
            notificationDropdown.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    // Event listeners
    if (notificationButton) {
        notificationButton.addEventListener('click', toggleNotifications);
    }

    if (closeNotifications) {
        closeNotifications.addEventListener('click', toggleNotifications);
    }

    // Cerrar al hacer click fuera
    document.addEventListener('click', function(e) {
        if (!notificationDropdown.classList.contains('hidden') && 
            !notificationButton.contains(e.target) && 
            !notificationDropdown.contains(e.target)) {
            toggleNotifications(e);
        }
    });

    // Cerrar con la tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !notificationDropdown.classList.contains('hidden')) {
            toggleNotifications(e);
        }
    });
});
</script>
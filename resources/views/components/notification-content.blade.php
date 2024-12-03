{{-- components/notification-content.blade.php --}}
@if($notificaciones->count() > 0)
    @foreach($notificaciones->sortByDesc('created_at') as $notificacion)
        <div class="group border-b border-gray-700/30 hover:bg-gray-800/50 transition-all duration-200">
            <div class="p-4">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-2 h-2 rounded-full {{ $notificacion->read_at ? 'bg-green-500' : 'bg-amber-500' }}"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-gray-300 break-words leading-relaxed">
                            {{ $notificacion->data['mensaje'] ?? 'Mensaje no disponible' }}
                        </p>
                        
                        <div class="flex flex-wrap items-center gap-2 mt-3 text-sm text-gray-500">
                            <span>{{ $notificacion->created_at->diffForHumans() }}</span>
                            <span>•</span>
                            <span>{{ $notificacion->data['usuario_remitente'] ?? 'No disponible' }}</span>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-4 mt-4">
                            <a href="{{ route('reservas.show', ['reserva' => $notificacion->data['reserva_id'] ?? 0]) }}" 
                               class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors">
                                Ver Reserva
                            </a>
                            @unless($notificacion->read_at)
                                <form action="{{ route('notificaciones.marcarLeida', $notificacion->id) }}" 
                                      method="POST" 
                                      class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="text-sm text-gray-400 hover:text-white transition-colors">
                                        Marcar como leída
                                    </button>
                                </form>
                            @endunless
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="flex flex-col items-center justify-center py-12 px-4">
        <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <p class="mt-4 text-gray-400 text-center">No hay notificaciones nuevas</p>
    </div>
@endif
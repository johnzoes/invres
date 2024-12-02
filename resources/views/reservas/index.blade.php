<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-white leading-tight">
                {{ __('Historial de Reservas') }}
            </h2>
            <span class="text-sm text-gray-400">
                Total: {{ $reservas->count() }} reservas
            </span>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Vista móvil: Cards -->
            <div class="grid gap-4 sm:hidden">
                @foreach($reservas as $reserva)
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg border border-gray-700/50">
                        <div class="p-5 space-y-4">
                            <!-- Header con ID y Estado -->
                            <div class="flex justify-between items-center">
                                <span class="bg-gray-700 text-gray-300 px-3 py-1 rounded-lg text-sm">
                                    ID: {{ $reserva->id }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-sm {{ 
                                    $reserva->estado === 'pendiente' ? 'bg-yellow-500/20 text-yellow-300' :
                                    ($reserva->estado === 'aceptado' ? 'bg-green-500/20 text-green-300' :
                                    'bg-red-500/20 text-red-300') }}">
                                    {{ ucfirst($reserva->estado) }}
                                </span>
                            </div>
                            
                            <!-- Información Principal -->
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-400">Profesor</p>
                                        <p class="text-white">{{ $reserva->profesor->usuario->nombre }} {{ $reserva->profesor->usuario->apellidos }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-400">Unidad Didáctica</p>
                                        <p class="text-white">{{ $reserva->unidadDidactica->nombre }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="pt-3 border-t border-gray-700">
                                <button onclick="verDetalles({{ $reserva->id }})" 
                                        class="w-full text-blue-400 hover:text-blue-300 transition-colors text-sm flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Ver detalles
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Vista tablet/desktop: Tabla -->
            <div class="hidden sm:block">
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg border border-gray-700/50">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">ID</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Profesor</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Unidad Didáctica</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Estado</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($reservas as $reserva)
                                <tr class="hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-white">{{ $reserva->id }}</td>
                                    <td class="px-6 py-4 text-sm text-white">
                                        {{ $reserva->profesor->usuario->nombre }} {{ $reserva->profesor->usuario->apellidos }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-white">
                                        {{ $reserva->unidadDidactica->nombre }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-sm {{ 
                                            $reserva->estado === 'pendiente' ? 'bg-yellow-500/20 text-yellow-300' :
                                            ($reserva->estado === 'aceptado' ? 'bg-green-500/20 text-green-300' :
                                            'bg-red-500/20 text-red-300') }}">
                                            {{ ucfirst($reserva->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button onclick="verDetalles({{ $reserva->id }})" 
                                                class="text-blue-400 hover:text-blue-300 transition-colors text-sm flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Ver detalles
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mensaje cuando no hay reservas -->
            @if ($reservas->isEmpty())
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-8 text-center">
                    <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-gray-400">No hay reservas registradas.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Notyf para notificaciones -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const notyf = new Notyf({
                    duration: 5000,
                    position: {
                        x: 'right',
                        y: 'top',
                    },
                    types: [
                        {
                            type: 'success',
                            background: '#10B981',
                            icon: {
                                className: 'fas fa-check-circle',
                                tagName: 'span',
                                color: '#fff'
                            }
                        }
                    ]
                });
                notyf.success("{{ session('success') }}");
            });
        </script>
    @endif

    <script>
        function verDetalles(reservaId) {
            // Implementar lógica para ver detalles
            // Podría ser una redirección o un modal
            window.location.href = `/reservas/${reservaId}`;
        }
    </script>
</x-app-layout>
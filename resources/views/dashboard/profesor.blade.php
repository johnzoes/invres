<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Botón Nueva Reserva -->
            <div class="mb-8">
                <a href="{{ route('reservas.create') }}" 
                   class="block bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl overflow-hidden group relative">
                    <div class="absolute inset-0 bg-[url('{{ asset('images/bg-new-rv.jpg') }}')] bg-cover bg-center opacity-20"></div>
                    <div class="relative p-8 flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-medium text-white mb-2">Nueva Reserva</h3>
                            <p class="text-blue-100/80">Solicita nuevos equipos o materiales</p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Panel Principal -->
            <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 shadow-xl p-6">
                <!-- Filtro y Título -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                    <h2 class="text-2xl font-light text-white">Historial de Reservas</h2>
                    
                    <div class="flex items-center gap-3">
                        <!-- Contadores de estado -->
                        <div class="flex gap-2">
                        <div class="flex gap-2">
    <span class="px-3 py-1 rounded-lg bg-yellow-500/10 text-yellow-400 text-sm border border-yellow-500/20">
        {{ sizeof($reservasPendientes) }} pendientes
    </span>
    <span class="px-3 py-1 rounded-lg bg-blue-500/10 text-blue-400 text-sm border border-blue-500/20">
        {{ sizeof($reservasPrestadas) }} prestadas
    </span>
</div>

                        </div>
                    </div>
                </div>

                <!-- Timeline de Reservas -->
                <div class="space-y-6">
                    @php
                        $allReservas = collect()->merge($reservasPendientes)
                                               ->merge($reservasAprobadas)
                                               ->merge($reservasPrestadas)
                                               ->merge($reservasDevueltas)
                                               ->sortByDesc('created_at');
                        $currentDate = null;
                    @endphp

                    @foreach($allReservas as $reserva)
                        @php
                            $reservaDate = $reserva->created_at->format('Y-m-d');
                            $estado = $reserva->detalles->first()->estado ?? 'pendiente';
                            $colorClasses = match($estado) {
                                'pendiente' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                                'aprobado' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                'prestado' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                'devuelto' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                default => 'bg-gray-500/10 text-gray-400 border-gray-500/20'
                            };
                        @endphp

                        @if($currentDate !== $reservaDate)
                            <div class="flex items-center gap-4 pt-6">
                                <div class="h-px flex-grow bg-gray-700"></div>
                                <span class="text-gray-400 text-sm">{{ $reserva->created_at->translatedFormat('d \d\e F, Y') }}</span>
                                <div class="h-px flex-grow bg-gray-700"></div>
                            </div>
                            @php $currentDate = $reservaDate; @endphp
                        @endif

                        <a href="{{ route('reservas.show', $reserva->id) }}" 
                           class="block bg-gray-700/30 hover:bg-gray-700/50 rounded-xl border border-gray-700/50 transition-all duration-200">
                            <div class="p-4 sm:p-6">
                                <div class="flex flex-col sm:flex-row justify-between gap-4">
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-3">
                                            <span class="text-gray-400">#{{ $reserva->id }}</span>
                                            <span class="{{ $colorClasses }} text-sm px-3 py-1 rounded-full border">
                                                {{ ucfirst($estado) }}
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-medium text-white">
                                            {{ $reserva->unidadDidactica->nombre }}
                                        </h3>
                                        <p class="text-gray-400 text-sm">
                                            {{ $reserva->created_at->format('h:i A') }}
                                        </p>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-400">Ver detalles</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach

                    @if($allReservas->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-gray-400 text-lg">No hay reservas registradas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <div class="min-h-screen bg-gray-900">
        <div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-light text-white text-center mb-16">Historial de Estados</h1>

            <!-- Timeline container -->
            <div class="relative">
                <!-- Línea vertical del timeline -->
                <div class="absolute left-8 top-0 bottom-0 w-px bg-gray-700 rounded-full"></div>

                <!-- Estado Inicial: Solicitado -->
                <div class="mb-12 pl-24 relative">
                    <div class="absolute left-6 w-6 h-6 bg-gradient-to-br from-green-400 to-green-600 rounded-full border-4 border-gray-900 z-10"></div>
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 shadow-lg">
                        <h3 class="text-lg font-medium text-white mb-2">
                            Solicitado
                        </h3>
                        <div class="text-gray-400 space-y-1">
                            <p>{{ \Carbon\Carbon::parse($fechaPrestamo)->translatedFormat('d \d\e F \d\e Y') }}</p>
                            <p>{{ \Carbon\Carbon::parse($fechaPrestamo)->format('h:i a') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Estados del historial -->
                @foreach ($historial as $evento)
                    <div class="mb-12 pl-24 relative">
                        @php
                            $colorClasses = match($evento->estado) {
                                'aprobado' => 'from-blue-400 to-blue-600',
                                'rechazado' => 'from-red-400 to-red-600',
                                'prestado' => 'from-purple-400 to-purple-600',
                                'devuelto' => 'from-teal-400 to-teal-600',
                                default => 'from-gray-400 to-gray-600'
                            };
                        @endphp

                        <div class="absolute left-6 w-6 h-6 bg-gradient-to-br {{ $colorClasses }} rounded-full border-4 border-gray-900 z-10"></div>
                        
                        <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 shadow-lg transition-all hover:bg-gray-700/50">
                            <h3 class="text-lg font-medium text-white mb-2">
                                {{ ucfirst($evento->estado) }}
                            </h3>
                            <div class="text-gray-400 space-y-1">
                                <p>{{ \Carbon\Carbon::parse($evento->fecha_estado)->translatedFormat('d \d\e F \d\e Y') }}</p>
                                <p>{{ \Carbon\Carbon::parse($evento->fecha_estado)->format('h:i a') }}</p>
                            </div>
                            
                            @if ($evento->estado === 'rechazado' && $evento->motivo_rechazo)
                                <div class="mt-4 p-4 bg-red-500/10 rounded-xl border border-red-500/20">
                                    <p class="text-red-400">
                                        <span class="font-medium">Motivo del rechazo:</span><br>
                                        {{ $evento->motivo_rechazo }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-16 text-center">
                <a href="{{ route('reservas.index') }}" 
                   class="inline-flex items-center text-blue-400 hover:text-blue-300 transition-colors gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Volver a la lista de reservas
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
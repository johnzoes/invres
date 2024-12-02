<x-app-layout>
    <div class="min-h-screen bg-gray-900">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Encabezado con botón de regreso -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-semibold text-white">Detalles de Reserva #{{ $reserva->id }}</h1>
                </div>
                <a href="{{ route('reservas.pdf', $reserva->id) }}" 
                   class="flex items-center gap-2 bg-blue-500/10 text-blue-400 px-4 py-2 rounded-xl hover:bg-blue-500/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    PDF
                </a>
            </div>

            <!-- Tarjeta de información principal -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 mb-8 border border-gray-700/50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-500/10 rounded-xl">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Profesor</p>
                                <p class="text-white">{{ $reserva->profesor->usuario->nombre }} {{ $reserva->profesor->usuario->apellidos }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-purple-500/10 rounded-xl">
                                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Unidad Didáctica</p>
                                <p class="text-white">{{ $reserva->unidadDidactica->nombre }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-green-500/10 rounded-xl">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Fecha de Préstamo</p>
                                <p class="text-white">{{ \Carbon\Carbon::parse($reserva->fecha_prestamo)->format('d/m/Y H:i') }}</p>                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de ítems -->
            <div class="space-y-6">
                <h2 class="text-xl font-medium text-white">Ítems Reservados</h2>
                @if($detallesFiltrados->isEmpty())
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-8 text-center border border-gray-700/50">
                        <svg class="w-12 h-12 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-gray-400">No hay ítems bajo tu control para esta reserva</p>
                    </div>
                @else
                    <div class="grid gap-4">
                        @foreach($detallesFiltrados as $detalle)
                            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-gray-700/50">
                                <div class="flex flex-col sm:flex-row justify-between gap-6">
                                    <div class="space-y-3">
                                        <h3 class="font-medium text-white">{{ $detalle->item->descripcion }}</h3>
                                        <div class="flex items-center gap-4">
                                            <span class="text-sm bg-gray-700 text-gray-300 px-3 py-1 rounded-lg">
                                                {{ $detalle->cantidad_reservada }} unidades
                                            </span>
                                            <span class="text-sm {{ 
                                                $detalle->estado === 'pendiente' ? 'bg-yellow-500/20 text-yellow-300' :
                                                ($detalle->estado === 'aceptado' ? 'bg-green-500/20 text-green-300' :
                                                ($detalle->estado === 'prestado' ? 'bg-blue-500/20 text-blue-300' :
                                                'bg-red-500/20 text-red-300')) }} px-3 py-1 rounded-lg">
                                                {{ ucfirst($detalle->estado) }}
                                            </span>
                                        </div>
                                        <a href="{{ route('historial.show', $detalle->id) }}" 
                                           class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            Ver Historial
                                        </a>
                                    </div>

                                    <!-- Botones de acción -->
                                    <div class="flex flex-wrap gap-3 items-start">
                                        @if($detalle->estado == 'pendiente')
                                            <form action="{{ route('reservas.approve', $detalle->id) }}" method="POST" class="flex-shrink-0">
                                                @csrf
                                                <button type="submit" class="bg-green-500/10 text-green-400 px-4 py-2 rounded-xl hover:bg-green-500/20 transition-all">
                                                    Aprobar
                                                </button>
                                            </form>

                                            <form action="{{ route('reservas.reject', $detalle->id) }}" method="POST" class="flex gap-2">
                                                @csrf
                                                <input type="text" 
                                                       name="motivo_rechazo" 
                                                       placeholder="Motivo" 
                                                       required 
                                                       class="bg-gray-700 text-white rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 border-0">
                                                <button type="submit" class="bg-red-500/10 text-red-400 px-4 py-2 rounded-xl hover:bg-red-500/20 transition-all flex-shrink-0">
                                                    Rechazar
                                                </button>
                                            </form>

                                        @elseif($detalle->estado == 'aceptado')
                                            <form action="{{ route('reservas.lend', $detalle->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-blue-500/10 text-blue-400 px-4 py-2 rounded-xl hover:bg-blue-500/20 transition-all">
                                                    Prestar
                                                </button>
                                            </form>

                                        @elseif($detalle->estado == 'prestado')
                                            <form action="{{ route('reservas.return', $detalle->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-purple-500/10 text-purple-400 px-4 py-2 rounded-xl hover:bg-purple-500/20 transition-all">
                                                    Devolver
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
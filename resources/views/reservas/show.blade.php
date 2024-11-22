<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles de la Reserva') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 text-white">
                <h1 class="text-2xl font-semibold mb-6">Detalles de la Reserva</h1>
                
                <!-- Información de la reserva -->
                <div class="mb-6">
                    <p><strong>ID:</strong> {{ $reserva->id }}</p>
                    <p><strong>Profesor:</strong> {{ $reserva->profesor->usuario->nombre }} {{ $reserva->profesor->usuario->apellidos }}</p>
                    <p><strong>Unidad Didáctica:</strong> {{ $reserva->unidadDidactica->nombre }}</p>
                    <p><strong>Fecha de Préstamo:</strong> {{ $reserva->fecha_prestamo }}</p>
                </div>

                <!-- Ítems reservados bajo el control del asistente -->
                <h3 class="text-xl font-semibold mb-4">Ítems Reservados (Bajo tu Control)</h3>
                @if($detallesFiltrados->isEmpty())
                    <p class="text-red-400">No tienes ítems bajo tu control para esta reserva.</p>
                @else
                    <ul class="space-y-4">
                        @foreach($detallesFiltrados as $detalle)
                            <li class="bg-gray-900 p-4 rounded-lg shadow-sm">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p><strong>Ítem:</strong> {{ $detalle->item->descripcion }}</p>
                                        <p><strong>Cantidad:</strong> {{ $detalle->cantidad_reservada }} unidades</p>
                                        <p><strong>Estado:</strong> {{ ucfirst($detalle->estado) }}</p>
                                        <a href="{{ route('historial.show', $detalle->id) }}" class="bg-black bold text-sm px-2 py-1 rounded-lg text-white">
                                            Ver Historial
                                        </a>
                                    </div>

                                    <!-- Mostrar botones para aprobar, rechazar, prestar o devolver -->
                                    <div class="space-x-2">
                                        @if($detalle->estado == 'pendiente')
                                            <!-- Botones de aprobación y rechazo -->
                                            <form action="{{ route('reservas.approve', $detalle->id) }}" method="POST" class="inline" onsubmit="this.querySelector('button').disabled = true;">
                                                @csrf
                                                <x-primary-button type="submit" class="bg-green-500 hover:bg-green-700">
                                                    Aprobar
                                                </x-primary-button>
                                            </form>

                                            <form action="{{ route('reservas.reject', $detalle->id) }}" method="POST" class="inline" onsubmit="this.querySelector('button').disabled = true;">
                                                @csrf
                                                <x-text-input type="text" name="motivo_rechazo" placeholder="Motivo de rechazo" required class="mr-2" />
                                                <x-primary-button type="submit" class="bg-red-500 hover:bg-red-700">
                                                    Rechazar
                                                </x-primary-button>
                                            </form>

                                        @elseif($detalle->estado == 'aceptado')
                                            <!-- Botón para prestar el ítem -->
                                            <form action="{{ route('reservas.lend', $detalle->id) }}" method="POST" onsubmit="this.querySelector('button').disabled = true;">
                                                @csrf
                                                <x-primary-button type="submit" class="bg-blue-500 hover:bg-blue-700">
                                                    Prestar Ítem
                                                </x-primary-button>
                                            </form>

                                        @elseif($detalle->estado == 'prestado')
                                            <!-- Botón para devolver el ítem -->
                                            <form action="{{ route('reservas.return', $detalle->id) }}" method="POST" onsubmit="this.querySelector('button').disabled = true;">
                                                @csrf
                                                <x-primary-button type="submit" class="bg-purple-500 hover:bg-purple-700">
                                                    Devolver Ítem
                                                </x-primary-button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <!-- Botón para descargar PDF -->
                <div class="mt-8">
                    <a href="{{ route('reservas.pdf', $reserva->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Descargar PDF
                    </a>
                </div>

                <!-- Botón para regresar a la lista de reservas -->
                <div class="mt-4">
                    <a href="{{ route('reservas.index') }}" class="text-blue-400 hover:underline">
                        Volver a la lista de reservas
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

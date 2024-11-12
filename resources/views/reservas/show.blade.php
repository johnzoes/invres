<!-- resources/views/reservas/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles de la Reserva') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1 class="text-2xl font-semibold mb-4">Detalles de la Reserva</h1>
                
                <p><strong>ID:</strong> {{ $reserva->id }}</p>
                <p><strong>Profesor:</strong> {{ $reserva->profesor->usuario->nombre }} {{ $reserva->profesor->usuario->apellidos }}</p>
                <p><strong>Unidad Didáctica:</strong> {{ $reserva->unidadDidactica->nombre }}</p>
                <p><strong>Fecha de Préstamo:</strong> {{ $reserva->fecha_prestamo }}</p>

                <h3 class="text-xl font-semibold mt-4">Ítems Reservados</h3>
                <ul class="mt-3">
                    @foreach($reserva->detalles as $detalle)
                        <li class="mb-3">
                            <p>{{ $detalle->item->descripcion }} - {{ $detalle->cantidad_reservada }} unidades - Estado: {{ ucfirst($detalle->estado) }}</p>
                            
                            @if($detalle->estado == 'pendiente')
                                <!-- Formulario de Aprobación -->
                                <form action="{{ route('reservas.approve', $detalle->id) }}" method="POST" class="inline" onsubmit="this.querySelector('button').disabled = true;">
                                    @csrf
                                    <x-primary-button type="submit" class="bg-green-500 hover:bg-green-700">Aprobar</x-primary-button>
                                </form>

                                <!-- Formulario de Rechazo -->
                                <form action="{{ route('reservas.reject', $detalle->id) }}" method="POST" class="inline" onsubmit="this.querySelector('button').disabled = true;">
                                    @csrf
                                    <x-text-input type="text" name="motivo_rechazo" placeholder="Motivo de rechazo" required class="mr-2" />
                                    <x-primary-button type="submit" class="bg-red-500 hover:bg-red-700">Rechazar</x-primary-button>
                                </form>
                            
                            @elseif($detalle->estado == 'aceptado')
                                <!-- Botón para prestar el ítem -->
                                <form action="{{ route('reservas.lend', $detalle->id) }}" method="POST" onsubmit="this.querySelector('button').disabled = true;">
                                    @csrf
                                    <x-primary-button type="submit" class="bg-blue-500 hover:bg-blue-700">Prestar Ítem</x-primary-button>
                                </form>
                            
                            @elseif($detalle->estado == 'prestado')
                                <!-- Botón para devolver el ítem -->
                                <form action="{{ route('reservas.return', $detalle->id) }}" method="POST" onsubmit="this.querySelector('button').disabled = true;">
                                    @csrf
                                    <x-primary-button type="submit" class="bg-purple-500 hover:bg-purple-700">Devolver Ítem</x-primary-button>
                                </form>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <div class="mt-4">
                    <a href="{{ route('reservas.index') }}" class="text-blue-500 hover:underline">Volver a la lista de reservas</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

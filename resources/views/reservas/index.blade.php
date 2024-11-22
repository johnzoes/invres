<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Reservas') }}
        </h2>
    </x-slot>

    <div class="flex flex-col py-12">
        <div class="w-full max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                
                <!-- Botón para Crear Reserva -->
                <div class="flex justify-end mb-6">
                    <a href="{{ route('reservas.create') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-5 rounded-lg">
                        Crear Reserva
                    </a>
                </div>

                <h1 class="text-2xl font-bold text-white mb-6">Listado de Reservas</h1>

                <!-- Mensaje de éxito -->
                @if (session('success'))
                    <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tabla de Reservas -->
                <table class="w-full text-left text-sm bg-gray-900 rounded-lg overflow-hidden">
                    <thead class="bg-gray-700 text-gray-200 uppercase">
                        <tr>
                            <th class="py-3 px-6 text-left">ID</th>
                            <th class="py-3 px-6 text-left">Profesor</th>
                            <th class="py-3 px-6 text-left">Unidad Didáctica</th>
                            <th class="py-3 px-6 text-left">Fecha de Préstamo</th>
                            <th class="py-3 px-6 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-300">
                        @foreach($reservas as $reserva)
                            <tr class="border-b border-gray-700 hover:bg-gray-700 transition duration-200">
                                <td class="py-3 px-6">{{ $reserva->id }}</td>
                                <td class="py-3 px-6">
                                    <span class="font-bold">{{ $reserva->profesor->usuario->nombre }} {{ $reserva->profesor->usuario->apellidos }}</span>
                                </td>
                                <td class="py-3 px-6">{{ $reserva->unidadDidactica->nombre }}</td>
                                <td class="py-3 px-6">
                                    {{ \Carbon\Carbon::parse($reserva->fecha_prestamo)->translatedFormat('d \d\e F \d\e Y') }}
                                </td>
                                <td class="py-3 px-6 flex items-center space-x-4">
                                    <!-- Botón para Ver Detalles -->
                                    <a href="{{ route('reservas.show', $reserva->id) }}" class="text-blue-500 hover:text-blue-700 font-semibold">
                                        Ver Detalles
                                    </a>

                                    <!-- Botón para Eliminar -->
                                    <a href="#" class="text-red-500 hover:text-red-700 font-semibold"
                                       onclick="event.preventDefault(); 
                                       if (confirm('¿Estás seguro de que deseas eliminar esta reserva?')) {
                                           document.getElementById('delete-form-{{ $reserva->id }}').submit();
                                       }">
                                       Eliminar
                                    </a>
                                    
                                    <!-- Formulario para eliminar la reserva -->
                                    <form id="delete-form-{{ $reserva->id }}" action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Mensaje cuando no hay reservas -->
                @if ($reservas->isEmpty())
                    <div class="text-center text-gray-400 mt-6">
                        No hay reservas registradas.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

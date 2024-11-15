<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Reservas') }}
        </h2>
    </x-slot>

    <div class="flex flex-col ml-64 py-12">
        <div class="w-full max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Botón para Crear Reserva -->
                    <div class="flex justify-end mb-6">
                        <a href="{{ route('reservas.create') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-5 rounded-lg">
                            Crear Reserva
                        </a>
                    </div>

                    <h1 class="text-lg font-bold mb-4">Listado de Reservas</h1>

                    <!-- Mensaje de éxito -->
                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Tabla de Reservas -->
                    <table class="w-full table-auto text-left bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-center rounded-l-lg">ID</th>
                                <th class="py-3 px-6 text-center">Profesor</th>
                                <th class="py-3 px-6 text-center">Unidad Didáctica</th>
                                <th class="py-3 px-6 text-center">Fecha de Préstamo</th>
                                <th class="py-3 px-6 text-center rounded-r-lg">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 dark:text-gray-100 text-sm font-light">
                            @foreach($reservas as $reserva)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="py-3 px-6 text-center rounded-l-lg">{{ $reserva->id }}</td>
                                    <td class="py-3 px-6 text-center">{{ $reserva->profesor->usuario->nombre }} {{ $reserva->profesor->usuario->apellidos }}</td>
                                    <td class="py-3 px-6 text-center">{{ $reserva->unidadDidactica->nombre }}</td>
                                    <td class="py-3 px-6 text-center">{{ $reserva->fecha_prestamo }}</td>
                                    <td class="py-3 px-6 flex justify-center space-x-4 rounded-r-lg">
                                        <!-- Botón para Ver Detalles -->
                                        <a href="{{ route('reservas.show', $reserva->id) }}" class="text-blue-500 hover:text-blue-700">
                                            Ver Detalles
                                        </a>

                                        <!-- Botón para Eliminar -->
                                        <a href="#" class="text-red-500 hover:text-red-700"
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reservas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <a href="{{ route('reservas.create') }}" class="bg-yellow-500 text-white p-2 rounded">Crear Reserva</a>

                    <h1 class="text-lg font-bold mt-4 mb-2">Listado de Reservas</h1>

                    @if (session('success'))
                        <div class="bg-green-500 text-white p-2 mb-4 rounded">{{ session('success') }}</div>
                    @endif

                    <table class="table-auto w-full text-left">
                        <thead>
                            <tr class="bg-gray-700 text-white">
                                <th class="px-4 py-2">ID</th>
                                <th class="px-4 py-2">Profesor</th>
                                <th class="px-4 py-2">Unidad Didáctica</th>
                                <th class="px-4 py-2">Fecha de Préstamo</th>
                                <th class="px-4 py-2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-200">
                            @foreach($reservas as $reserva)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $reserva->id }}</td>
                                    <td class="px-4 py-2">{{ $reserva->profesor->usuario->nombre }} {{ $reserva->profesor->usuario->apellidos }}</td>
                                    <td class="px-4 py-2">{{ $reserva->unidadDidactica->nombre }}</td>
                                    <td class="px-4 py-2">{{ $reserva->fecha_prestamo }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('reservas.show', $reserva->id) }}" class="text-blue-500 hover:text-blue-700">Ver Detalles</a> |
                                        <a href="#" class="text-red-500 hover:text-red-700" 
                                           onclick="event.preventDefault(); document.getElementById('delete-form-{{ $reserva->id }}').submit();">
                                           Eliminar
                                        </a>
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

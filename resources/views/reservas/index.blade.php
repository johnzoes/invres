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

                <a href="{{ route('reservas.create') }}" class="text-yellow-500 p-6">crear</a>

                    <h1>Reservas</h1>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Profesor</th>
                                <th>Unidad Didáctica</th>
                                <th>Fecha de Préstamo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservas as $reserva)
                                <tr>
                                    <td>{{ $reserva->id_reserva }}</td>
                                    <td>{{ $reserva->profesor->usuario->nombre }} {{ $reserva->profesor->usuario->apellidos }}</td>
                                    <td>{{ $reserva->unidadDidactica->nombre }}</td>
                                    <td>{{ $reserva->fecha_prestamo }}</td>
                                    <td>
                                        <a href="{{ route('reservas.show', $reserva->id_reserva) }}">Ver Detalles</a> |
                                        <a href="{{ route('reservas.destroy', $reserva->id_reserva) }}"
                                           onclick="event.preventDefault(); document.getElementById('delete-form-{{ $reserva->id_reserva }}').submit();">
                                           Eliminar
                                        </a>
                                        <form id="delete-form-{{ $reserva->id_reserva }}" action="{{ route('reservas.destroy', $reserva->id_reserva) }}" method="POST" style="display: none;">
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

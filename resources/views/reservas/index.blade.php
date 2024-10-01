<!-- resources/views/reservas/index.blade.php -->
@extends('layouts.app')

@section('content')
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
@endsection

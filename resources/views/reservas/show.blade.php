<!-- resources/views/reservas/show.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Detalles de la Reserva</h1>

    <p>ID: {{ $reserva->id_reserva }}</p>
    <p>Profesor: {{ $reserva->profesor->usuario->nombre }} {{ $reserva->profesor->usuario->apellidos }}</p>
    <p>Unidad Didáctica: {{ $reserva->unidadDidactica->nombre }}</p>
    <p>Fecha de Préstamo: {{ $reserva->fecha_prestamo }}</p>

    <h3>Ítems Reservados</h3>
    <ul>
        @foreach($reserva->detalles as $detalle)
            <li>{{ $detalle->item->descripcion }} - {{ $detalle->cantidad_reservada }} unidades</li>
        @endforeach
    </ul>

    <a href="{{ route('reservas.index') }}">Volver a la lista de reservas</a>
@endsection

<!-- resources/views/reservas/create.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Crear Nueva Reserva</h1>

    <form action="{{ route('reservas.store') }}" method="POST">
        @csrf

        <label for="profesor">Profesor:</label>
        <select name="id_profesor" id="profesor">
            @foreach($profesores as $profesor)
                <option value="{{ $profesor->id_profesor }}">{{ $profesor->usuario->nombre }} {{ $profesor->usuario->apellidos }}</option>
            @endforeach
        </select>

        <label for="unidad_didactica">Unidad Didáctica:</label>
        <select name="id_unidad_didactica" id="unidad_didactica">
            @foreach($unidades_didacticas as $unidad)
                <option value="{{ $unidad->id_unidad_didactica }}">{{ $unidad->nombre }}</option>
            @endforeach
        </select>

        <h3>Ítems</h3>
        @foreach($items as $item)
            <label for="item_{{ $item->id_item }}">{{ $item->descripcion }}</label>
            <input type="checkbox" name="items[{{ $item->id_item }}][id_item]" value="{{ $item->id_item }}">
            <input type="number" name="items[{{ $item->id_item }}][cantidad_reservada]" min="1" placeholder="Cantidad">
        @endforeach

        <button type="submit">Crear Reserva</button>
    </form>

    <a href="{{ route('reservas.index') }}">Volver a la lista de reservas</a>
@endsection
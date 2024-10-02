<!-- resources/views/items/index.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Ítems</h1>

    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <!-- Botón para agregar un nuevo ítem -->
    <a href="{{ route('items.create') }}">Agregar Ítem</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->descripcion }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>{{ $item->tipo }}</td>
                    <td>{{ $item->marca }}</td>
                    <td>
                        <a href="{{ route('items.show', $item->id) }}">Ver</a> |
                        <a href="{{ route('items.destroy', $item->id) }}" 
                           onclick="event.preventDefault(); document.getElementById('delete-form-{{ $item->id }}').submit();">
                            Eliminar
                        </a>
                        <form id="delete-form-{{ $item->id }}" action="{{ route('items.destroy', $item->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

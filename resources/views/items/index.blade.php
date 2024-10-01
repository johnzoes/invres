<!-- resources/views/items/index.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Ítems</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
                    <td>{{ $item->id_item }}</td>
                    <td>{{ $item->descripcion }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>{{ $item->tipo }}</td>
                    <td>{{ $item->marca }}</td>
                    <td>
                        <a href="{{ route('items.show', $item->id_item) }}">Ver</a> |
                        <a href="{{ route('items.destroy', $item->id_item) }}" 
                           onclick="event.preventDefault(); document.getElementById('delete-form-{{ $item->id_item }}').submit();">
                            Eliminar
                        </a>
                        <form id="delete-form-{{ $item->id_item }}" action="{{ route('items.destroy', $item->id_item) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

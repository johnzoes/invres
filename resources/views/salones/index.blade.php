<!-- resources/views/salones/index.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Lista de Salones</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('salones.create') }}" class="btn btn-success mb-3">Agregar Nuevo Salón</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salones as $salon)
                <tr>
                    <td>{{ $salon->id }}</td>
                    <td>{{ $salon->nombre_salon }}</td>
                    <td>
                        <a href="{{ route('salones.edit', $salon->id) }}" class="btn btn-info btn-sm">Editar</a>
                        <form action="{{ route('salones.destroy', $salon->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

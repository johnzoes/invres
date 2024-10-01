@extends('layouts.app')

@section('content')
    <h1>Lista de Armarios</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('armarios.create') }}" class="btn btn-success mb-3">Agregar Nuevo Armario</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Salón</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($armarios as $armario)
                <tr>
                    <td>{{ $armario->id_armario }}</td>
                    <td>{{ $armario->nombre_armario }}</td>
                    <td>{{ $armario->salon->nombre_salon }}</td>
                    <td>
                        <a href="{{ route('armarios.edit', $armario->id_armario) }}" class="btn btn-info btn-sm">Editar</a>
                        <form action="{{ route('armarios.destroy', $armario->id_armario) }}" method="POST" style="display:inline;">
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

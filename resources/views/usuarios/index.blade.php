@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lista de Usuarios</h2>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre de Usuario</th>
                <th>Correo Electrónico</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->id }}</td>
                <td>{{ $usuario->nombre_usuario }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ implode(', ', $usuario->roles->pluck('name')->toArray()) }}</td>
                <td>
                    <!-- Botones de edición/eliminación -->
                    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-warning">Editar</a>
                    <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

<!-- resources/views/usuarios/create.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Crear Nuevo Usuario</h1>

    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        <div>
            <label for="nombre_usuario">Nombre de Usuario:</label>
            <input type="text" name="nombre_usuario" id="nombre_usuario" value="{{ old('nombre_usuario') }}" required>
        </div>

        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required>
        </div>

        <div>
            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" id="apellidos" value="{{ old('apellidos') }}" required>
        </div>

        <div>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div>
            <label for="rol">Rol:</label>
            <select name="rol" id="rol" required>
                <option value="">Seleccione un rol</option>
                <option value="admin">Admin</option>
                <option value="profesor">Profesor</option>
                <option value="asistente">Asistente</option>
            </select>
        </div>

        <div id="asistente-info" style="display: none;">
            <label for="id_salon">Salón:</label>
            <select name="id_salon" id="id_salon">
                @foreach($salones as $salon)
                    <option value="{{ $salon->id }}">{{ $salon->nombre_salon }}</option>
                @endforeach
            </select>

            <label for="turno">Turno:</label>
            <select name="turno" id="turno">
                <option value="mañana">Mañana</option>
                <option value="noche">Noche</option>
            </select>
        </div>

        <button type="submit">Crear Usuario</button>
    </form>

    <a href="{{ route('usuarios.index') }}">Volver a la lista de usuarios</a>

    <script>
        // Mostrar el formulario de asistente si el rol seleccionado es 'asistente'
        document.getElementById('rol').addEventListener('change', function() {
            var asistenteInfo = document.getElementById('asistente-info');
            if (this.value === 'asistente') {
                asistenteInfo.style.display = 'block';
            } else {
                asistenteInfo.style.display = 'none';
            }
        });
    </script>
@endsection

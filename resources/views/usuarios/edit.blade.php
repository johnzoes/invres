<!-- resources/views/usuarios/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Editar Usuario</h1>

    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="nombre_usuario">Nombre de Usuario:</label>
            <input type="text" name="nombre_usuario" id="nombre_usuario" value="{{ old('nombre_usuario', $usuario->nombre_usuario) }}" required>
        </div>

        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
        </div>

        <div>
            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" id="apellidos" value="{{ old('apellidos', $usuario->apellidos) }}" required>
        </div>

        <div>
            <label for="rol">Rol:</label>
            <select name="rol" id="rol" required>
                @foreach($roles as $rol)
                    <option value="{{ $rol->name }}" {{ $usuario->roles->pluck('name')->contains($rol->name) ? 'selected' : '' }}>{{ $rol->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="asistente-info" style="display: none;">
            <label for="id_salon">Salón:</label>
            <select name="id_salon" id="id_salon">
                @foreach($salones as $salon)
                    <option value="{{ $salon->id }}" {{ isset($usuario->asistente) && $usuario->asistente->id_salon == $salon->id ? 'selected' : '' }}>
                        {{ $salon->nombre_salon }}
                    </option>
                @endforeach
            </select>

            <label for="turno">Turno:</label>
            <select name="turno" id="turno">
                <option value="mañana" {{ isset($usuario->asistente) && $usuario->asistente->turno == 'mañana' ? 'selected' : '' }}>Mañana</option>
                <option value="noche" {{ isset($usuario->asistente) && $usuario->asistente->turno == 'noche' ? 'selected' : '' }}>Noche</option>
            </select>
        </div>

        <button type="submit">Actualizar Usuario</button>
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

        // Mostrar el formulario de asistente si ya está seleccionado
        window.onload = function() {
            if (document.getElementById('rol').value === 'asistente') {
                document.getElementById('asistente-info').style.display = 'block';
            }
        }
    </script>
@endsection

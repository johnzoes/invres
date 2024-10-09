@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Usuario</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        <!-- Nombre de usuario -->
        <div class="form-group">
            <label for="nombre_usuario">Nombre de Usuario</label>
            <input type="text" name="nombre_usuario" class="form-control" id="nombre_usuario" value="{{ old('nombre_usuario') }}" required>
        </div>

        <!-- Nombre -->
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" class="form-control" id="nombre" value="{{ old('nombre') }}" required>
        </div>

        <!-- Apellidos -->
        <div class="form-group">
            <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" class="form-control" id="apellidos" value="{{ old('apellidos') }}" required>
        </div>

        <!-- Correo Electrónico -->
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required>
        </div>

        <!-- Contraseña -->
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" name="password" class="form-control" id="password" required>
        </div>

        <!-- Rol -->
        <div class="form-group">
            <label for="rol">Rol</label>
            <select name="rol" id="rol" class="form-control" required>
                <option value="">Selecciona un rol</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol->name }}" {{ old('rol') == $rol->name ? 'selected' : '' }}>
                        {{ ucfirst($rol->name) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Campos para Asistentes -->
        <div id="asistente-fields" style="display: none;">
            <div class="form-group">
                <label for="id_salon">Salón</label>
                <select name="id_salon" id="id_salon" class="form-control">
                    <option value="">Selecciona un salón</option>
                    @foreach($salones as $salon)
                        <option value="{{ $salon->id }}" {{ old('id_salon') == $salon->id ? 'selected' : '' }}>
                            {{ $salon->nombre_salon }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="turno">Turno</label>
                <select name="turno" id="turno" class="form-control">
                    <option value="">Selecciona un turno</option>
                    <option value="mañana" {{ old('turno') == 'mañana' ? 'selected' : '' }}>Mañana</option>
                    <option value="noche" {{ old('turno') == 'noche' ? 'selected' : '' }}>Noche</option>
                </select>
            </div>
        </div>

        <!-- Botón de enviar -->
        <button type="submit" class="btn btn-primary">Crear Usuario</button>
    </form>
</div>

<script>
    document.getElementById('rol').addEventListener('change', function () {
        var asistenteFields = document.getElementById('asistente-fields');
        if (this.value === 'asistente') {
            asistenteFields.style.display = 'block';
        } else {
            asistenteFields.style.display = 'none';
        }
    });
</script>
@endsection

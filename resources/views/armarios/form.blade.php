@extends('layouts.app')

@section('content')
    <h1>{{ isset($armario) ? 'Editar Armario' : 'Crear Nuevo Armario' }}</h1>

    <form action="{{ isset($armario) ? route('armarios.update', $armario->id_armario) : route('armarios.store') }}" method="POST">
        @csrf
        @if(isset($armario))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="nombre_armario">Nombre del Armario:</label>
            <input type="text" name="nombre_armario" id="nombre_armario" class="form-control" value="{{ old('nombre_armario', $armario->nombre_armario ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="id_salon">Salón:</label>
            <select name="id_salon" id="id_salon" class="form-control" required>
                @foreach($salones as $salon)
                    <option value="{{ $salon->id_salon }}" {{ isset($armario) && $armario->id_salon == $salon->id_salon ? 'selected' : '' }}>
                        {{ $salon->nombre_salon }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($armario) ? 'Actualizar Armario' : 'Crear Armario' }}</button>
    </form>

    <a href="{{ route('armarios.index') }}" class="btn btn-secondary mt-3">Volver a la lista de armarios</a>
@endsection

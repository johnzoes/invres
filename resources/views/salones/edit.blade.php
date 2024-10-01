@extends('layouts.app')

@section('content')
    <h1>{{ isset($salon) ? 'Editar Salón' : 'Crear Nuevo Salón' }}</h1>

    <form action="{{ isset($salon) ? route('salones.update', $salon->id_salon) : route('salones.store') }}" method="POST">
        @csrf
        @if(isset($salon))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="nombre_salon">Nombre del Salón:</label>
            <input type="text" name="nombre_salon" id="nombre_salon" class="form-control" value="{{ old('nombre_salon', $salon->nombre_salon ?? '') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($salon) ? 'Actualizar Salón' : 'Crear Salón' }}</button>
    </form>

    <a href="{{ route('salones.index') }}" class="btn btn-secondary mt-3">Volver a la lista de salones</a>
@endsection

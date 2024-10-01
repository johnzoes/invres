<!-- resources/views/salones/create.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Crear Nuevo Salón</h1>

    <form action="{{ route('salones.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nombre_salon">Nombre del Salón:</label>
            <input type="text" name="nombre_salon" id="nombre_salon" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Crear Salón</button>
    </form>

    <a href="{{ route('salones.index') }}" class="btn btn-secondary mt-3">Volver a la lista de salones</a>
@endsection

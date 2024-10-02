@extends('layouts.app')

@section('content')
    <h1>{{ isset($categoria) ? 'Editar Categoría' : 'Nueva Categoría' }}</h1>

    <form action="{{ isset($categoria) ? route('categorias.update', $categoria->id) : route('categorias.store') }}" method="POST">
        @csrf
        @if(isset($categoria))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="nombre_categoria">Nombre de la Categoría:</label>
            <input type="text" name="nombre_categoria" id="nombre_categoria" class="form-control" value="{{ old('nombre_categoria', $categoria->nombre_categoria ?? '') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($categoria) ? 'Actualizar' : 'Crear' }}</button>
    </form>

    <a href="{{ route('categorias.index') }}" class="btn btn-secondary mt-3">Volver a la lista de categorías</a>
@endsection

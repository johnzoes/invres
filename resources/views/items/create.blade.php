<!-- resources/views/items/create.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>{{ isset($item) ? 'Editar Ítem' : 'Agregar Nuevo Ítem' }}</h1>

    <form action="{{ isset($item) ? route('items.update', $item->id_item) : route('items.store') }}" method="POST">
        @csrf
        @if(isset($item))
            @method('PUT')
        @endif

        <!-- Código BCI (Opcional) -->
        <label for="codigo_bci">Código BCI (Opcional):</label>
        <input type="text" name="codigo_bci" id="codigo_bci" value="{{ old('codigo_bci', $item->codigo_bci ?? '') }}">

        <!-- Descripción -->
        <label for="descripcion">Descripción:</label>
        <input type="text" name="descripcion" id="descripcion" required value="{{ old('descripcion', $item->descripcion ?? '') }}">

        <!-- Cantidad -->
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" required value="{{ old('cantidad', $item->cantidad ?? 1) }}">

        <!-- Tipo (Unidad o Paquete) -->
        <label for="tipo">Tipo:</label>
        <select name="tipo" id="tipo" required>
            <option value="unidad" {{ old('tipo', $item->tipo ?? '') == 'unidad' ? 'selected' : '' }}>Unidad</option>
            <option value="paquete" {{ old('tipo', $item->tipo ?? '') == 'paquete' ? 'selected' : '' }}>Paquete</option>
        </select>

        <!-- Marca -->
        <label for="marca">Marca (Opcional):</label>
        <input type="text" name="marca" id="marca" value="{{ old('marca', $item->marca ?? '') }}">

        <!-- Modelo -->
        <label for="modelo">Modelo (Opcional):</label>
        <input type="text" name="modelo" id="modelo" value="{{ old('modelo', $item->modelo ?? '') }}">

        <!-- Imagen (URL o Nombre del Archivo) -->
        <label for="imagen">Imagen (URL o Nombre del Archivo) (Opcional):</label>
        <input type="text" name="imagen" id="imagen" value="{{ old('imagen', $item->imagen ?? '') }}">

        <!-- Número Inventariado (Opcional) -->
        <label for="nro_inventariado">Número Inventariado (Opcional):</label>
        <input type="text" name="nro_inventariado" id="nro_inventariado" value="{{ old('nro_inventariado', $item->nro_inventariado ?? '') }}">

        <!-- Categoría -->
        <label for="id_categoria">Categoría:</label>
        <select name="id_categoria" id="id_categoria" required>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria', $item->id_categoria ?? '') == $categoria->id_categoria ? 'selected' : '' }}>
                    {{ $categoria->nombre_categoria }}
                </option>
            @endforeach
        </select>

        <!-- Armario -->
        <label for="id_armario">Armario:</label>
        <select name="id_armario" id="id_armario" required>
            @foreach($armarios as $armario)
                <option value="{{ $armario->id_armario }}" {{ old('id_armario', $item->id_armario ?? '') == $armario->id_armario ? 'selected' : '' }}>
                    {{ $armario->nombre_armario }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">
            {{ isset($item) ? 'Actualizar Ítem' : 'Agregar Ítem' }}
        </button>
    </form>

    <a href="{{ route('items.index') }}" class="btn btn-secondary">Volver a la lista de ítems</a>
@endsection

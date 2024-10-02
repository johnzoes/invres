<!-- resources/views/items/form.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>{{ isset($item) ? 'Editar Ítem' : 'Agregar Nuevo Ítem' }}</h1>

    <form action="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}" method="POST">
        @csrf
        @if(isset($item))
            @method('PUT')
        @endif

        <div>
            <label for="descripcion">Descripción:</label>
            <input type="text" name="descripcion" id="descripcion" value="{{ old('descripcion', $item->descripcion ?? '') }}" required>
        </div>

        <div>
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" value="{{ old('cantidad', $item->cantidad ?? '') }}" required>
        </div>

        <div>
            <label for="tipo">Tipo:</label>
            <select name="tipo" id="tipo" required>
                <option value="unidad" {{ (old('tipo', $item->tipo ?? '') == 'unidad') ? 'selected' : '' }}>Unidad</option>
                <option value="paquete" {{ (old('tipo', $item->tipo ?? '') == 'paquete') ? 'selected' : '' }}>Paquete</option>
            </select>
        </div>

        <div>
            <label for="marca">Marca:</label>
            <input type="text" name="marca" id="marca" value="{{ old('marca', $item->marca ?? '') }}">
        </div>

        <div>
            <label for="modelo">Modelo:</label>
            <input type="text" name="modelo" id="modelo" value="{{ old('modelo', $item->modelo ?? '') }}">
        </div>

        <div>
            <label for="id_categoria">Categoría:</label>
            <select name="id_categoria" id="id_categoria" required>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ (old('id_categoria', $item->id_categoria ?? '') == $categoria->id) ? 'selected' : '' }}>
                        {{ $categoria->nombre_categoria }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="id_armario">Armario:</label>
            <select name="id_armario" id="id_armario" required>
                @foreach($armarios as $armario)
                    <option value="{{ $armario->id }}" {{ (old('id_armario', $item->id_armario ?? '') == $armario->id) ? 'selected' : '' }}>
                        {{ $armario->nombre_armario }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit">{{ isset($item) ? 'Actualizar Ítem' : 'Agregar Ítem' }}</button>
    </form>

    <a href="{{ route('items.index') }}">Volver a la lista de ítems</a>
@endsection

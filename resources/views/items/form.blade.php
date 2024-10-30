<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ isset($item) ? 'Editar Ítem' : 'Agregar Nuevo Ítem' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h1 class="mb-6 text-lg font-bold">{{ isset($item) ? 'Editar Ítem' : 'Agregar Nuevo Ítem' }}</h1>

                    <form action="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}" method="POST">
                        @csrf
                        @if(isset($item))
                            @method('PUT')
                        @endif

                        <!-- Código BCI -->
                        <div class="mb-4">
                            <label for="codigo_bci" class="block text-sm font-medium text-gray-700">Código BCI:</label>
                            <input type="text" name="codigo_bci" id="codigo_bci" class="form-input mt-1 block w-full" value="{{ old('codigo_bci', $item->codigo_bci ?? '') }}" required>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción:</label>
                            <input type="text" name="descripcion" id="descripcion" class="form-input mt-1 block w-full" value="{{ old('descripcion', $item->descripcion ?? '') }}" required>
                        </div>

                        <!-- Cantidad -->
                        <div class="mb-4">
                            <label for="cantidad" class="block text-sm font-medium text-gray-700">Cantidad:</label>
                            <input type="number" name="cantidad" id="cantidad" class="form-input mt-1 block w-full" value="{{ old('cantidad', $item->cantidad ?? '') }}" required>
                        </div>

                        <!-- Tipo -->
                        <div class="mb-4">
                            <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo:</label>
                            <select name="tipo" id="tipo" class="form-select mt-1 block w-full" required>
                                <option value="unidad" {{ (old('tipo', $item->tipo ?? '') == 'unidad') ? 'selected' : '' }}>Unidad</option>
                                <option value="paquete" {{ (old('tipo', $item->tipo ?? '') == 'paquete') ? 'selected' : '' }}>Paquete</option>
                            </select>
                        </div>

                        <!-- Marca -->
                        <div class="mb-4">
                            <label for="marca" class="block text-sm font-medium text-gray-700">Marca:</label>
                            <input type="text" name="marca" id="marca" class="form-input mt-1 block w-full" value="{{ old('marca', $item->marca ?? '') }}">
                        </div>

                        <!-- Modelo -->
                        <div class="mb-4">
                            <label for="modelo" class="block text-sm font-medium text-gray-700">Modelo:</label>
                            <input type="text" name="modelo" id="modelo" class="form-input mt-1 block w-full" value="{{ old('modelo', $item->modelo ?? '') }}">
                        </div>

                        <!-- Imagen -->
                        <div class="mb-4">
                            <label for="imagen" class="block text-sm font-medium text-gray-700">Imagen:</label>
                            <input type="file" name="imagen" id="imagen" class="form-input mt-1 block w-full">
                        </div>

                        <!-- Número inventariado -->
                        <div class="mb-4">
                            <label for="nro_inventariado" class="block text-sm font-medium text-gray-700">Número Inventariado:</label>
                            <input type="text" name="nro_inventariado" id="nro_inventariado" class="form-input mt-1 block w-full" value="{{ old('nro_inventariado', $item->nro_inventariado ?? '') }}">
                        </div>

                        <!-- Categoría -->
                        <div class="mb-4">
                            <label for="id_categoria" class="block text-sm font-medium text-gray-700">Categoría:</label>
                            <select name="id_categoria" id="id_categoria" class="form-select mt-1 block w-full" required>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ (old('id_categoria', $item->id_categoria ?? '') == $categoria->id) ? 'selected' : '' }}>{{ $categoria->nombre_categoria }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Salón -->
                        <div class="mb-4">
                            <label for="id_salon" class="block text-sm font-medium text-gray-700">Salón:</label>
                            <select name="id_salon" id="id_salon" class="form-select mt-1 block w-full" required>
                                <option value="">Seleccione un salón</option>
                                @foreach($salones as $salon)
                                    <option value="{{ $salon->id }}" {{ (old('id_salon', $item->id_salon ?? '') == $salon->id) ? 'selected' : '' }}>{{ $salon->nombre_salon }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Armario (aparece después de seleccionar un salón) -->
                        <div class="mb-4" id="armario-container" style="display: none;">
                            <label for="id_armario" class="block text-sm font-medium text-gray-700">Armario:</label>
                            <select name="id_armario" id="id_armario" class="form-select mt-1 block w-full">
                                <option value="">Seleccione un armario</option>
                                <!-- Los armarios se cargarán dinámicamente -->
                            </select>
                        </div>

                        <!-- Botón para enviar -->
                        <div class="mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ isset($item) ? 'Actualizar Ítem' : 'Agregar Ítem' }}
                            </button>
                        </div>
                    </form>

                    <!-- Enlace para regresar a la lista de ítems -->
                    <div class="mt-4">
                        <a href="{{ route('items.index') }}" class="text-blue-500 hover:text-blue-700">Volver a la lista de ítems</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para manejar la selección de salones y cargar los armarios dinámicamente -->
    <script>
document.getElementById('id_salon').addEventListener('change', function () {
    var salonId = this.value;
    var armarioContainer = document.getElementById('armario-container');
    var armarioSelect = document.getElementById('id_armario');

    if (salonId) {
        // Mostrar el campo de armarios
        armarioContainer.style.display = 'block';

        // Limpiar el campo de armarios
        armarioSelect.innerHTML = '<option value="">Seleccione un armario</option>';

        // Hacer una petición AJAX para obtener los armarios del salón seleccionado
        fetch('/salones/' + salonId + '/armarios')
            .then(response => response.json())
            .then(data => {
                data.forEach(armario => {
                    var option = document.createElement('option');
                    option.value = armario.id;
                    option.text = armario.nombre_armario;
                    armarioSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error al cargar los armarios:', error));
    } else {
        // Ocultar el contenedor si no se selecciona un salón
        armarioContainer.style.display = 'none';
    }
});

    </script>
</x-app-layout>

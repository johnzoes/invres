<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 dark:text-gray-200 leading-tight text-center">
            {{ isset($item) ? 'Editar Ítem' : 'Agregar Nuevo Ítem' }}
        </h2>
    </x-slot>

    <div class="flex flex-col py-12">
        <div class="w-full max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 rounded-lg shadow-lg p-8">
                
                <h1 class="text-2xl font-bold text-white mb-6">{{ isset($item) ? 'Editar Ítem' : 'Agregar Nuevo Ítem' }}</h1>

                <form action="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($item))
                        @method('PUT')
                    @endif

                    <!-- Código BCI -->
                    <div class="mb-6">
                        <label for="codigo_bci" class="block text-sm font-medium text-gray-300">Código BCI:</label>
                        <input type="text" name="codigo_bci" id="codigo_bci" class="block w-full bg-gray-700 text-white border-gray-600 rounded-lg mt-2 p-3" value="{{ old('codigo_bci', $item->codigo_bci ?? '') }}" required>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-6">
                        <label for="descripcion" class="block text-sm font-medium text-gray-300">Descripción:</label>
                        <input type="text" name="descripcion" id="descripcion" class="block w-full bg-gray-700 text-white border-gray-600 rounded-lg mt-2 p-3" value="{{ old('descripcion', $item->descripcion ?? '') }}" required>
                    </div>

        <!-- Tipo -->
        <div class="mb-6">
            <label for="tipo" class="block text-sm font-medium text-gray-300">Tipo:</label>
            <select name="tipo" id="tipo" class="block w-full bg-gray-700 text-white border-gray-600 rounded-lg mt-2 p-3">
                <option value="unidad" {{ (old('tipo', $item->tipo ?? '') == 'unidad') ? 'selected' : '' }}>Unidad</option>
                <option value="paquete" {{ (old('tipo', $item->tipo ?? '') == 'paquete') ? 'selected' : '' }}>Paquete</option>
            </select>
        </div>

        <!-- Cantidad -->
        <div class="mb-6">
            <label for="cantidad" class="block text-sm font-medium text-gray-300">Cantidad:</label>
            <input 
                type="number" 
                name="cantidad" 
                id="cantidad" 
                class="block w-full bg-gray-700 text-white border-gray-600 rounded-lg mt-2 p-3" 
                value="{{ old('cantidad', $item->cantidad ?? '') }}" 
                min="1" 
                required>
        </div>

                    <!-- Marca -->
                    <div class="mb-6">
                        <label for="marca" class="block text-sm font-medium text-gray-300">Marca:</label>
                        <input type="text" name="marca" id="marca" class="block w-full bg-gray-700 text-white border-gray-600 rounded-lg mt-2 p-3" value="{{ old('marca', $item->marca ?? '') }}">
                    </div>

                    <!-- Imagen -->
                    <div class="mb-6">
                        <label for="imagen" class="block text-sm font-medium text-gray-300">Imagen:</label>
                        @if(isset($item) && $item->imagen)
                            <!-- Mostrar la imagen actual si está editando -->
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $item->imagen) }}" alt="Imagen del Ítem" class="w-32 h-32 object-cover rounded-lg">
                            </div>
                        @endif
                        <input type="file" name="imagen" id="imagen" class="block w-full bg-gray-700 text-white border-gray-600 rounded-lg mt-2 p-3" accept="image/*">
                    </div>


                    <!-- Modelo -->
                    <div class="mb-6">
                        <label for="modelo" class="block text-sm font-medium text-gray-300">Modelo:</label>
                        <input type="text" name="modelo" id="modelo" class="block w-full bg-gray-700 text-white border-gray-600 rounded-lg mt-2 p-3" value="{{ old('modelo', $item->modelo ?? '') }}">
                    </div>

                    <!-- Categoría -->
                    <div class="mb-6">
                        <label for="id_categoria" class="block text-sm font-medium text-gray-300">Categoría:</label>
                        <select name="id_categoria" id="id_categoria" class="block w-full bg-gray-700 text-white border-gray-600 rounded-lg mt-2 p-3">
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" 
                                {{ (old('id_categoria', $item->id_categoria ?? $categoriaPreseleccionada ?? '') == $categoria->id) ? 'selected' : '' }}>
                                {{ $categoria->nombre_categoria }}
                            </option>
    @endforeach
                    </div>

                    <!-- Salón -->
                    <div class="mb-6">
                        <label for="id_salon" class="block text-sm font-medium text-gray-300">Salón:</label>
                        <select name="id_salon" id="id_salon" class="block w-full bg-gray-700 text-white border-gray-600 rounded-lg mt-2 p-3">
                            <option value="">Seleccione un salón</option>
                            @foreach($salones as $salon)
                                <option value="{{ $salon->id }}" {{ (old('id_salon', $item->id_salon ?? '') == $salon->id) ? 'selected' : '' }}>{{ $salon->nombre_salon }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Armario (dinámico) -->
                    <div class="mb-6" id="armario-container" style="display: none;">
                        <label for="id_armario" class="block text-sm font-medium text-gray-300">Armario:</label>
                        <select name="id_armario" id="id_armario" class="block w-full bg-gray-700 text-white border-gray-600 rounded-lg mt-2 p-3">
                            <option value="">Seleccione un armario</option>
                        </select>
                    </div>

                    @if($errors->any())
                        <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <!-- Botón para enviar -->
                    <div class="mt-8">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg">
                            {{ isset($item) ? 'Actualizar Ítem' : 'Agregar Ítem' }}
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <a href="{{ route('items.index') }}" class="text-blue-500 hover:text-blue-700">Volver a la lista de ítems</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para manejar la selección de armarios -->
    <script>
        document.getElementById('id_salon').addEventListener('change', function () {
            const salonId = this.value;
            const armarioContainer = document.getElementById('armario-container');
            const armarioSelect = document.getElementById('id_armario');

            if (salonId) {
                armarioContainer.style.display = 'block';
                armarioSelect.innerHTML = '<option value="">Seleccione un armario</option>';

                fetch(`/salones/${salonId}/armarios`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(armario => {
                            const option = document.createElement('option');
                            option.value = armario.id;
                            option.textContent = armario.nombre_armario;
                            armarioSelect.appendChild(option);
                        });
                    });
            } else {
                armarioContainer.style.display = 'none';
            }
        });


        document.addEventListener('DOMContentLoaded', () => {
        const tipoSelect = document.getElementById('tipo');
        const cantidadInput = document.getElementById('cantidad');

        // Escuchar cambios en el tipo
        tipoSelect.addEventListener('change', () => {
            if (tipoSelect.value === 'unidad') {
                cantidadInput.value = 1; // Por defecto, poner 1 si es "unidad"
            } else {
                cantidadInput.value = ''; // Limpiar el valor si es "paquete"
            }
        });

        // Establecer el valor por defecto al cargar la página
        if (tipoSelect.value === 'unidad') {
            cantidadInput.value = 1;
        }
    });
    </script>
</x-app-layout>

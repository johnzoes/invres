<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-200 tracking-wide">
                {{ isset($item) ? 'Editar Ítem' : 'Crear Nuevo Ítem' }}
            </h2>
            <a href="{{ route('items.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 rounded-lg font-semibold text-sm text-white hover:bg-gray-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <!-- Formulario Principal -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}" 
                  method="POST" 
                  class="bg-gray-800 rounded-xl shadow-xl border border-gray-700 p-6 space-y-8">
                @csrf
                @if(isset($item)) @method('PUT') @endif

                <!-- Información básica -->
                <div class="border-b border-gray-700 pb-6">
                    <h3 class="text-lg font-medium text-gray-300 mb-4">Información Básica</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Descripción *</label>
                            <input type="text" name="descripcion" value="{{ old('descripcion', $item->descripcion ?? '') }}" 
                                   class="w-full bg-gray-700 border-gray-600 text-white rounded-lg" required>
                            @error('descripcion')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Código BCI</label>
                            <input type="text" name="codigo_bci" value="{{ old('codigo_bci', $item->codigo_bci ?? '') }}" 
                                   class="w-full bg-gray-700 border-gray-600 text-white rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Ubicación -->
                <div class="border-b border-gray-700 pb-6">
                    <h3 class="text-lg font-medium text-gray-300 mb-4">Ubicación</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-400">Categoría *</label>
                                <a href="{{ route('categorias.create') }}" 
                                   class="text-sm text-indigo-400 hover:text-indigo-300 inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Nueva categoría
                                </a>
                            </div>
                            <select name="id_categoria" id="categoria" class="w-full bg-gray-700 border-gray-600 text-white rounded-lg" required>
                                <option value="">Seleccionar categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" 
                                            {{ old('id_categoria', isset($item) ? $item->id_categoria : '') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre_categoria }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_categoria')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-400">Salón *</label>
                                <a href="{{ route('salones.create') }}" 
                                   class="text-sm text-indigo-400 hover:text-indigo-300 inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Nuevo salón
                                </a>
                            </div>
                            <select id="salon" class="w-full bg-gray-700 border-gray-600 text-white rounded-lg" required>
                                <option value="">Seleccionar salón</option>
                                @foreach($salones as $salon)
                                    <option value="{{ $salon->id }}" 
                                            {{ isset($item) && $item->armario->salon->id == $salon->id ? 'selected' : '' }}>
                                        {{ $salon->nombre_salon }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-400">Armario *</label>
                                <a href="{{ route('armarios.create') }}" 
                                   class="text-sm text-indigo-400 hover:text-indigo-300 inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Nuevo armario
                                </a>
                            </div>
                            <select name="id_armario" id="armario" class="w-full bg-gray-700 border-gray-600 text-white rounded-lg" required>
                                <option value="">Primero seleccione un salón</option>
                            </select>
                            @error('id_armario')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Detalles -->
                <div>
                    <h3 class="text-lg font-medium text-gray-300 mb-4">Detalles del Ítem</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">
                                Tipo *
                            </label>
                            <select name="tipo" 
                                    id="tipo"
                                    class="w-full bg-gray-700 border-gray-600 text-white rounded-lg" 
                                    required>
                                <option value="unidad" {{ old('tipo', $item->tipo ?? '') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                                <option value="paquete" {{ old('tipo', $item->tipo ?? '') == 'paquete' ? 'selected' : '' }}>Paquete</option>
                            </select>
                            @error('tipo')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">
                                Cantidad *
                            </label>
                            <input type="number" 
                                   id="cantidad"
                                   name="cantidad" 
                                   value="{{ old('cantidad', $item->cantidad ?? 1) }}" 
                                   min="1" 
                                   class="w-full bg-gray-700 border-gray-600 text-white rounded-lg" 
                                   required>
                            @error('cantidad')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Marca</label>
                            <input type="text" name="marca" value="{{ old('marca', $item->marca ?? '') }}" 
                                   class="w-full bg-gray-700 border-gray-600 text-white rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Modelo</label>
                            <input type="text" name="modelo" value="{{ old('modelo', $item->modelo ?? '') }}" 
                                   class="w-full bg-gray-700 border-gray-600 text-white rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end gap-4 pt-6">
                    <a href="{{ route('items.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                        {{ isset($item) ? 'Actualizar' : 'Crear' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const salonSelect = document.getElementById('salon');
            const armarioSelect = document.getElementById('armario');
            const tipoSelect = document.getElementById('tipo');
            const cantidadInput = document.getElementById('cantidad');
            
            // Manejo de cantidad para tipo unidad
            function handleTipoChange() {
                if (tipoSelect.value === 'unidad') {
                    cantidadInput.value = '1';
                    cantidadInput.setAttribute('readonly', true);
                    cantidadInput.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    cantidadInput.removeAttribute('readonly');
                    cantidadInput.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            tipoSelect.addEventListener('change', handleTipoChange);
            handleTipoChange(); // Ejecutar al cargar para establecer estado inicial
            
            // Carga de armarios
            async function cargarArmarios(salonId, armarioIdSeleccionado = null) {
                if (!salonId) {
                    armarioSelect.innerHTML = '<option value="">Primero seleccione un salón</option>';
                    return;
                }

                try {
                    const response = await fetch(`/items/armarios/${salonId}`);
                    const armarios = await response.json();
                    
                    armarioSelect.innerHTML = '<option value="">Seleccionar armario</option>';
                    armarios.forEach(armario => {
                        const option = document.createElement('option');
                        option.value = armario.id;
                        option.textContent = armario.nombre_armario;
                        if (armarioIdSeleccionado && armarioIdSeleccionado == armario.id) {
                            option.selected = true;
                        }
                        armarioSelect.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error al cargar armarios:', error);
                    armarioSelect.innerHTML = '<option value="">Error al cargar armarios</option>';
                }
            }

            salonSelect.addEventListener('change', (e) => {
                cargarArmarios(e.target.value);
            });

            // Cargar armarios si hay un item existente
            @if(isset($item))
                cargarArmarios({{ $item->armario->salon->id }}, {{ $item->id_armario }});
            @endif
        });
    </script>
    @endpush
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Crear Reserva') }}
        </h2>
    </x-slot>

    <div class="flex flex-col ml-64 py-12">
        <div class="w-full max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Formulario para enviar al controlador -->
            <form action="{{ route('reservas.createtwo') }}" method="POST">
                @csrf
                
                <!-- Campo de Búsqueda y Filtro -->
                <div class="mb-6">
                    <label for="search" class="block text-sm font-medium text-gray-300">Buscar Ítem:</label>
                    <input type="text" id="search" class="mt-2 block w-full bg-gray-700 text-white border-gray-600 rounded-lg shadow-sm p-2" placeholder="Buscar por descripción..." oninput="filterItems()">
                </div>

                <!-- Selección de Categoría -->
                <div class="mb-6">
                    <label for="categoria" class="block text-sm font-medium text-gray-300">Filtrar por Categoría:</label>
                    <select id="categoria" class="mt-2 block w-full bg-gray-700 text-white border-gray-600 rounded-lg shadow-sm" onchange="filterItems()">
                        <option value="all">Todas las Categorías</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre_categoria }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown para seleccionar ítems -->
                <div id="dropdown" class="relative bg-gray-800 text-white w-full mt-2 rounded-lg shadow-lg max-h-96 overflow-y-auto z-10">
                    @foreach ($items as $item)
                        <div class="p-4 hover:bg-gray-600 cursor-pointer item flex items-center gap-4" 
                            data-categoria="{{ $item->id_categoria }}" 
                            data-descripcion="{{ strtolower($item->descripcion) }}"
                            onclick="selectItem('{{ $item->id }}', '{{ $item->descripcion }}', '{{ $item->cantidad }}')">
                            <img src="{{ Storage::url($item->imagen) }}" alt="{{ $item->descripcion }}" class="w-12 h-12 object-cover rounded">
                            <div>
                                <p>{{ $item->descripcion }}</p>
                                <p class="text-sm text-gray-400">Cantidad: {{ $item->cantidad }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Contenedor para los ítems seleccionados -->
                <div id="selected-items-container" class="mt-6 flex flex-wrap gap-4"></div>

                <!-- Botón para enviar el formulario -->
                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                        Siguiente: Seleccionar Unidad Didáctica
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Función para seleccionar un ítem y agregarlo al contenedor
        function selectItem(id, description, maxQuantity) {
            const container = document.getElementById('selected-items-container');

            // Verificar si el ítem ya está agregado
            if (document.getElementById('item-' + id)) return;

            const chipHtml = `
                <div id="item-${id}" class="chip flex items-center bg-gray-700 border border-gray-600 p-3 rounded-lg shadow-sm mb-2 w-full">
                    <span class="text-white font-semibold mr-2">${description}</span>
                    <input type="hidden" name="items[]" value="${id}">
                    <input type="number" name="cantidad[${id}]" min="1" max="${maxQuantity}" class="form-input w-24 text-center bg-gray-800 text-white border-gray-600 rounded-lg mx-2" placeholder="Cantidad" required>
                    <button type="button" class="ml-2 text-red-500 hover:text-red-700 font-bold" onclick="removeItem(${id})">&times;</button>
                </div>`;
            
            container.insertAdjacentHTML('beforeend', chipHtml);
        }

        // Función para filtrar ítems por categoría y descripción
        function filterItems() {
            const searchInput = document.getElementById('search').value.toLowerCase();
            const selectedCategory = document.getElementById('categoria').value;
            const items = document.querySelectorAll('#dropdown .item');

            items.forEach(item => {
                const itemCategory = item.getAttribute('data-categoria');
                const itemDescription = item.getAttribute('data-descripcion');

                // Mostrar el ítem si coincide con la categoría o la búsqueda
                const matchesCategory = selectedCategory === 'all' || itemCategory === selectedCategory;
                const matchesSearch = itemDescription.includes(searchInput);

                if (matchesCategory && matchesSearch) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Función para eliminar un ítem seleccionado
        function removeItem(id) {
            const itemElement = document.getElementById('item-' + id);
            if (itemElement) itemElement.remove();
        }
    </script>
</x-app-layout>

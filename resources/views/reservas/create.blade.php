<x-app-layout>
    <div class="min-h-screen bg-gray-900">
        <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-light text-white text-center mb-12">Crear Nueva Reserva</h1>

            <form action="{{ route('reservas.createtwo') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Barra de búsqueda y filtros -->
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <!-- Campo de Búsqueda -->
                    <div class="flex-1">
                        <input type="text" 
                               id="search" 
                               class="w-full bg-gray-800/50 text-white border-0 rounded-xl px-6 py-4 focus:ring-2 focus:ring-blue-500 transition-all placeholder-gray-500 backdrop-blur-sm" 
                               placeholder="Buscar ítem..."
                               oninput="filterItems()">
                    </div>

                    <!-- Selector de Categoría -->
                    <select id="categoria" 
                            class="bg-gray-800/50 text-white border-0 rounded-xl px-6 py-4 focus:ring-2 focus:ring-blue-500 transition-all backdrop-blur-sm"
                            onchange="filterItems()">
                        <option value="all">Todas las categorías</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre_categoria }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">


    @foreach ($categorias as $categoria)
        @if($categoria->items->count() > 0)
            <div class="item bg-gray-800/50 rounded-2xl p-4 hover:bg-gray-700/50 transition-all cursor-pointer backdrop-blur-sm"
                 data-categoria="{{ $categoria->id }}" 
                 data-descripcion="{{ strtolower($categoria->nombre_categoria) }}"
                 onclick="selectItem(
                    '{{ $categoria->id }}', 
                    '{{ $categoria->nombre_categoria }}', 
                    '{{ $categoria->items->sum('cantidad') }}'
                 )">
                
                <div class="p-4">
                    <h3 class="text-white font-medium mb-2">{{ $categoria->nombre_categoria }}</h3>
                    <div class="text-gray-400 text-sm">
                        @foreach($categoria->items->groupBy('tipo') as $tipo => $items)
                            <p class="mb-1">
                                {{ $tipo === 'unidad' ? 'Unidades' : 'En stock' }}: 
                                {{ $items->sum('cantidad') }}
                            </p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>



                <!-- Items seleccionados -->
                <div class="space-y-4 mb-8">
                    <h2 class="text-xl font-light text-white mb-4">Items Seleccionados</h2>
                    <div id="selected-items-container" class="space-y-3"></div>
                </div>

                <!-- Botón de envío -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-blue-500 text-white px-8 py-3 rounded-xl hover:bg-blue-600 transition-all font-medium">
                        Continuar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function selectItem(id, description, maxQuantity) {
            const container = document.getElementById('selected-items-container');
            if (document.getElementById('item-' + id)) return;

            const chipHtml = `
                <div id="item-${id}" class="flex items-center justify-between bg-gray-800/50 rounded-xl p-4 backdrop-blur-sm">
                    <span class="text-white font-medium">${description}</span>
                    <div class="flex items-center gap-4">
                        <input type="hidden" name="items[]" value="${id}">
                        <input type="number" 
                               name="cantidad[${id}]" 
                               min="1" 
                               max="${maxQuantity}" 
                               class="w-20 bg-gray-700 text-white border-0 rounded-lg px-3 py-2 text-center" 
                               placeholder="Cant."
                               required>
                        <button type="button" 
                                class="text-gray-400 hover:text-red-500 transition-colors" 
                                onclick="removeItem(${id})">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>`;
            
            container.insertAdjacentHTML('beforeend', chipHtml);
        }

        function filterItems() {
            const searchInput = document.getElementById('search').value.toLowerCase();
            const selectedCategory = document.getElementById('categoria').value;
            const items = document.querySelectorAll('.item');

            items.forEach(item => {
                const itemCategory = item.getAttribute('data-categoria');
                const itemDescription = item.getAttribute('data-descripcion');

                const matchesCategory = selectedCategory === 'all' || itemCategory === selectedCategory;
                const matchesSearch = itemDescription.includes(searchInput);

                item.style.display = matchesCategory && matchesSearch ? 'block' : 'none';
            });
        }

        function removeItem(id) {
            document.getElementById('item-' + id)?.remove();
        }
    </script>
</x-app-layout>
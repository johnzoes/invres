<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 shadow-xl p-6">
                
                <!-- Vista Selector -->
                @if(auth()->user()->hasAnyRole(['admin', 'asistente', 'profesor']))
                <div class="mb-6">
                    <nav class="flex space-x-4 border-b border-gray-700/50">
                        <a href="{{ route('items.index', ['view' => 'all']) }}" 
                           class="px-4 py-3 text-sm font-medium {{ !$showMyItems ? 'text-white border-b-2 border-green-500' : 'text-gray-400 hover:text-white' }} transition-colors duration-200">
                            Todos los Items
                        </a>
                        <a href="{{ route('items.index', ['view' => 'my']) }}" 
                           class="px-4 py-3 text-sm font-medium {{ $showMyItems ? 'text-white border-b-2 border-green-500' : 'text-gray-400 hover:text-white' }} transition-colors duration-200">
                            @if(auth()->user()->hasRole('admin'))
                                Mis Items
                            @elseif(auth()->user()->hasRole('asistente'))
                                Items de Mis Salones
                            @else
                                Items de Mi Salón
                            @endif
                        </a>
                    </nav>
                </div>
                @endif

                <!-- Breadcrumb y Título -->
                <div class="mb-6">
                    @if(isset($categoriaActual))
                        <nav class="mb-4 flex items-center text-gray-400 text-sm">
                            <a href="{{ route('items.index') }}" class="hover:text-white transition-colors">Items</a>
                            <svg class="h-4 w-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-white">{{ $categoriaActual->nombre_categoria }}</span>
                        </nav>
                    @endif

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h1 class="text-2xl font-light text-white">
                            @if(isset($categoriaActual))
                                Items de {{ $categoriaActual->nombre_categoria }}
                            @elseif($showMyItems)
                                @if(auth()->user()->hasRole('admin'))
                                    Mis Items
                                @elseif(auth()->user()->hasRole('asistente'))
                                    Items de Mis Salones
                                @else
                                    Items de Mi Salón
                                @endif
                            @else
                                Todos los Items
                            @endif
                        </h1>
                        
                        @can('crear items')
                            <a href="{{ isset($categoriaActual) ? route('items.create', ['categoria' => $categoriaActual->id]) : route('items.create') }}" 
                               class="inline-flex items-center gap-2 bg-green-500/90 hover:bg-green-500 text-white font-medium px-6 py-2.5 rounded-xl transition-all duration-200 shadow-lg shadow-green-500/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Nuevo Item
                            </a>
                        @endcan
                    </div>
                </div>

                <!-- Componente de búsqueda -->
                <x-search-input />

                <!-- Tabla Desktop/Tablet -->
                <div class="hidden md:block overflow-x-auto rounded-xl">
                    <table class="w-full text-left text-sm" id="itemsTable">
                        <thead class="bg-gray-700/50 text-gray-200">
                            <tr>
                                <th class="py-4 px-6 rounded-tl-xl">Descripción</th>
                                <th class="py-4 px-6">Cantidad</th>
                                <th class="py-4 px-6">Tipo</th>
                                <th class="py-4 px-6">Marca</th>
                                <th class="py-4 px-6">Ubicación</th>
                                <th class="py-4 px-6">Estado</th>
                                <th class="py-4 px-6 rounded-tr-xl text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50">
                            @forelse($items as $item)
                                <tr class="hover:bg-gray-700/30 transition-all duration-200">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <span class="font-medium text-white">{{ $item->descripcion }}</span>
                                            <span class="text-xs text-gray-400">#{{ $item->id }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-gray-300">{{ $item->cantidad }}</td>
                                    <td class="py-4 px-6 text-gray-300">{{ $item->tipo }}</td>
                                    <td class="py-4 px-6 text-gray-300">{{ $item->marca }}</td>
                                    <td class="py-4 px-6 text-gray-300">
                                        {{ $item->armario->salon->nombre ?? 'Sin ubicación' }}
                                    </td>
                                    <td class="py-4 px-6">
                                        @php
                                            $estadoColor = match($item->estado) {
                                                'disponible' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                                'ocupado' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                                default => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20'
                                            };
                                        @endphp
                                        <span class="{{ $estadoColor }} border px-3 py-1 rounded-full text-xs font-medium">
                                            {{ ucfirst($item->estado) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('items.show', $item->id) }}" 
                                               class="p-2 text-blue-400 hover:bg-blue-500/10 rounded-full transition-all">
                                                <x-icon name="view" />
                                            </a>
                                            @can('editar items')
                                            <a href="{{ route('items.edit', $item->id) }}" 
                                               class="p-2 text-yellow-400 hover:bg-yellow-500/10 rounded-full transition-all">
                                                <x-icon name="edit" />
                                            </a>
                                            @endcan
                                            @can('eliminar items')
                                            <form id="delete-form-{{ $item->id }}" 
                                                  action="{{ route('items.destroy', $item->id) }}" 
                                                  method="POST" 
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        onclick="if(confirm('¿Estás seguro de eliminar este item?')) this.closest('form').submit();"
                                                        class="p-2 text-red-400 hover:bg-red-500/10 rounded-full transition-all">
                                                    <x-icon name="delete" />
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-gray-400">
                                        No se encontraron ítems
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Vista Móvil -->
                <div class="md:hidden space-y-4" id="itemsGridMobile">
                    @forelse($items as $item)
                        <div class="bg-gray-700/30 rounded-xl p-4 border border-gray-700/50">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-white font-medium">{{ $item->descripcion }}</h3>
                                    <p class="text-gray-400 text-xs mt-1">#{{ $item->id }}</p>
                                </div>
                                @php
                                    $estadoColor = match($item->estado) {
                                        'disponible' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                        'ocupado' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                        default => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20'
                                    };
                                @endphp
                                <span class="{{ $estadoColor }} border px-3 py-1 rounded-full text-xs font-medium">
                                    {{ ucfirst($item->estado) }}
                                </span>
                            </div>
                            
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between text-gray-400">
                                    <span>Cantidad</span>
                                    <span class="text-gray-300">{{ $item->cantidad }}</span>
                                </div>
                                <div class="flex justify-between text-gray-400">
                                    <span>Tipo</span>
                                    <span class="text-gray-300">{{ $item->tipo }}</span>
                                </div>
                                <div class="flex justify-between text-gray-400">
                                    <span>Marca</span>
                                    <span class="text-gray-300">{{ $item->marca }}</span>
                                </div>
                                <div class="flex justify-between text-gray-400">
                                    <span>Ubicación</span>
                                    <span class="text-gray-300">{{ $item->armario->salon->nombre ?? 'Sin ubicación' }}</span>
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end gap-2">
                                <a href="{{ route('items.show', $item->id) }}" 
                                   class="p-2 text-blue-400 hover:bg-blue-500/10 rounded-full transition-all">
                                    <x-icon name="view" />
                                </a>
                                @can('editar items')
                                <a href="{{ route('items.edit', $item->id) }}" 
                                   class="p-2 text-yellow-400 hover:bg-yellow-500/10 rounded-full transition-all">
                                    <x-icon name="edit" />
                                </a>
                                @endcan
                                @can('eliminar items')
                                <form id="delete-form-mobile-{{ $item->id }}" 
                                      action="{{ route('items.destroy', $item->id) }}" 
                                      method="POST" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            onclick="if(confirm('¿Estás seguro de eliminar este item?')) this.closest('form').submit();"
                                            class="p-2 text-red-400 hover:bg-red-500/10 rounded-full transition-all">
                                        <x-icon name="delete" />
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-400 py-8">
                            No se encontraron ítems
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;

    searchInput.addEventListener('input', function(e) {
        const searchText = e.target.value.toLowerCase();
        
        // Filtrar tabla desktop
        const tableBody = document.querySelector('#itemsTable tbody');
        if (tableBody) {
            const rows = tableBody.getElementsByTagName('tr');
            let visibleCount = 0;

            Array.from(rows).forEach(row => {
                // Skip the "no results" row if it exists
                if (row.classList.contains('no-results')) return;

                const cells = row.getElementsByTagName('td');
                let shouldShow = false;

                Array.from(cells).forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(searchText)) {
                        shouldShow = true;
                    }
                });

                row.classList.toggle('hidden', !shouldShow);
                if (shouldShow) visibleCount++;
            });

            // Manejar mensaje de "no resultados"
            let noResultsRow = tableBody.querySelector('.no-results');
            if (visibleCount === 0) {
                if (!noResultsRow) {
                    noResultsRow = document.createElement('tr');
                    noResultsRow.className = 'no-results';
                    noResultsRow.innerHTML = `
                        <td colspan="7" class="py-8 text-center text-gray-400">
                            No se encontraron resultados para "${searchText}"
                        </td>
                    `;
                    tableBody.appendChild(noResultsRow);
                }
            } else if (noResultsRow) {
                noResultsRow.remove();
            }
        }

        // Filtrar vista móvil
        const mobileGrid = document.getElementById('itemsGridMobile');
        if (mobileGrid) {
            const cards = mobileGrid.querySelectorAll('.bg-gray-700\\/30');
            let visibleCount = 0;

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                const shouldShow = text.includes(searchText);
                card.classList.toggle('hidden', !shouldShow);
                if (shouldShow) visibleCount++;
            });

            // Manejar mensaje de "no resultados" en móvil
            let noResultsDiv = mobileGrid.querySelector('.no-results');
            if (visibleCount === 0) {
                if (!noResultsDiv) {
                    noResultsDiv = document.createElement('div');
                    noResultsDiv.className = 'no-results text-center text-gray-400 py-8';
                    noResultsDiv.textContent = `No se encontraron resultados para "${searchText}"`;
                    mobileGrid.appendChild(noResultsDiv);
                }
            } else if (noResultsDiv) {
                noResultsDiv.remove();
            }
        }
    });

    // Limpiar búsqueda con tecla Escape
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            this.dispatchEvent(new Event('input'));
            this.blur(); // Quitar el foco del input
        }
    });
});
</script>
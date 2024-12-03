<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 shadow-xl p-6">
                <!-- Header y Búsqueda -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <h1 class="text-2xl font-light text-white">
                        Gestión de Usuarios
                    </h1>
                    
                    <a href="{{ route('usuarios.create') }}" 
                       class="inline-flex items-center gap-2 bg-green-500/90 hover:bg-green-500 text-white font-medium px-6 py-2.5 rounded-xl transition-all duration-200 shadow-lg shadow-green-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Nuevo Usuario
                    </a>
                </div>

                <!-- Componente de búsqueda -->
                <div class="mb-6">
                    <x-search-input />
                </div>

                <!-- Tabla Desktop -->
                <div class="hidden md:block overflow-x-auto rounded-xl">
                    <table class="w-full text-left text-sm" id="usersTable">
                        <thead class="bg-gray-700/50 text-gray-200">
                            <tr>
                                <th class="py-4 px-6 rounded-tl-xl">ID</th>
                                <th class="py-4 px-6">Nombre Completo</th>
                                <th class="py-4 px-6">Usuario</th>
                                <th class="py-4 px-6">Correo</th>
                                <th class="py-4 px-6">Roles</th>
                                <th class="py-4 px-6 rounded-tr-xl text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50">
                            @forelse($usuarios as $usuario)
                                <tr class="hover:bg-gray-700/30 transition-all duration-200">
                                    <td class="py-4 px-6 text-gray-400">#{{ $usuario->id }}</td>
                                    <td class="py-4 px-6">
                                        <a href="{{ route('usuarios.show', $usuario->id) }}" 
                                           class="font-medium text-white hover:text-green-400 transition-colors">
                                            {{ $usuario->nombre . " " . $usuario->apellidos }}
                                        </a>
                                    </td>
                                    <td class="py-4 px-6 text-gray-300">{{ $usuario->nombre_usuario }}</td>
                                    <td class="py-4 px-6 text-gray-300">{{ $usuario->email }}</td>
                                    <td class="py-4 px-6">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($usuario->roles as $rol)
                                                <span class="px-2.5 py-1 bg-gray-700/50 text-gray-300 rounded-lg text-xs">
                                                    {{ $rol->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('usuarios.show', $usuario->id) }}" 
                                               class="p-2 text-blue-400 hover:bg-blue-500/10 rounded-full transition-all">
                                                <x-icon name="view" />
                                            </a>
                                            <a href="{{ route('usuarios.edit', $usuario->id) }}" 
                                               class="p-2 text-yellow-400 hover:bg-yellow-500/10 rounded-full transition-all">
                                                <x-icon name="edit" />
                                            </a>
                                            <form action="{{ route('usuarios.destroy', $usuario->id) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="p-2 text-red-400 hover:bg-red-500/10 rounded-full transition-all">
                                                    <x-icon name="delete" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-400">
                                        No se encontraron usuarios
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Vista Móvil -->
                <div class="md:hidden space-y-4" id="usersGridMobile">
                    @forelse($usuarios as $usuario)
                        <div class="bg-gray-700/30 rounded-xl p-4 border border-gray-700/50">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <a href="{{ route('usuarios.show', $usuario->id) }}" 
                                       class="text-white hover:text-green-400 font-medium transition-colors">
                                        {{ $usuario->nombre . " " . $usuario->apellidos }}
                                    </a>
                                    <p class="text-gray-400 text-xs mt-1">ID: #{{ $usuario->id }}</p>
                                </div>
                                <div class="flex gap-2">
                                    @foreach($usuario->roles as $rol)
                                        <span class="px-2.5 py-1 bg-gray-700/50 text-gray-300 rounded-lg text-xs">
                                            {{ $rol->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between text-gray-400">
                                    <span>Usuario</span>
                                    <span class="text-gray-300">{{ $usuario->nombre_usuario }}</span>
                                </div>
                                <div class="flex justify-between text-gray-400">
                                    <span>Correo</span>
                                    <span class="text-gray-300">{{ $usuario->email }}</span>
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end gap-2">
                                <a href="{{ route('usuarios.show', $usuario->id) }}" 
                                   class="p-2 text-blue-400 hover:bg-blue-500/10 rounded-full transition-all">
                                    <x-icon name="view" />
                                </a>
                                <a href="{{ route('usuarios.edit', $usuario->id) }}" 
                                   class="p-2 text-yellow-400 hover:bg-yellow-500/10 rounded-full transition-all">
                                    <x-icon name="edit" />
                                </a>
                                <form action="{{ route('usuarios.destroy', $usuario->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-red-400 hover:bg-red-500/10 rounded-full transition-all">
                                        <x-icon name="delete" />
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-400 py-8">
                            No se encontraron usuarios
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
        const tableBody = document.querySelector('#usersTable tbody');
        if (tableBody) {
            const rows = tableBody.getElementsByTagName('tr');
            let visibleCount = 0;

            Array.from(rows).forEach(row => {
                if (row.classList.contains('no-results')) return;

                const searchableContent = [
                    row.querySelector('td:nth-child(1)')?.textContent, // ID
                    row.querySelector('td:nth-child(2)')?.textContent, // Nombre
                    row.querySelector('td:nth-child(3)')?.textContent, // Usuario
                    row.querySelector('td:nth-child(4)')?.textContent, // Email
                    row.querySelector('td:nth-child(5)')?.textContent  // Roles
                ].join(' ').toLowerCase();

                const shouldShow = searchableContent.includes(searchText);
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
                        <td colspan="6" class="py-8 text-center text-gray-400">
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
        const mobileGrid = document.getElementById('usersGridMobile');
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
            this.blur();
        }
    });

    // Highlight del texto que coincide
    function highlightText(text, searchText) {
        if (!searchText) return text;
        const regex = new RegExp(`(${searchText})`, 'gi');
        return text.replace(regex, '<mark class="bg-yellow-200/20 text-white">$1</mark>');
    }
});
</script>
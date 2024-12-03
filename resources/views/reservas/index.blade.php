<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 shadow-xl">
                <!-- Header con estadísticas -->
                <div class="p-6 border-b border-gray-700/50">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <div>
                            <h1 class="text-2xl font-light text-white">
                                @if(auth()->user()->hasRole('profesor'))
                                    Mis Reservas
                                @elseif(auth()->user()->hasRole('asistente'))
                                    Reservas de mis Salones
                                @else
                                    Historial de Reservas
                                @endif
                            </h1>
                            <p class="text-sm text-gray-400 mt-1">Total: {{ $reservas->count() }} reservas</p>
                        </div>

                        <!-- Buscador -->
                        <div class="relative">
                            <input 
                                type="text" 
                                id="searchInput"
                                class="w-full sm:w-64 bg-gray-700/50 border border-gray-600/50 text-gray-200 text-sm rounded-xl px-4 py-2.5 pl-10 focus:border-green-500/50 focus:ring-1 focus:ring-green-500/50 focus:outline-none transition-colors duration-200"
                                placeholder="Buscar reservas..."
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Cards de Estadísticas -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-gray-700/30 rounded-xl p-4 border border-gray-700/50">
                            <div class="flex items-center gap-3">

                                <div class="p-2 bg-green-500/10 rounded-lg">
                                    <svg class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Aceptadas</p>
                                    <p class="text-xl text-white">{{ $stats['aceptados'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-700/30 rounded-xl p-4 border border-gray-700/50">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-yellow-500/10 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Pendientes</p>
                                    <p class="text-xl text-white">{{$stats['pendientes']}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-700/30 rounded-xl p-4 border border-gray-700/50">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-red-500/10 rounded-lg">
                                    <svg class="w-6 h-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Rechazadas</p>
                                    <p class="text-xl text-white">{{ $stats['rechazados'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vista móvil: Cards -->
                <div class="grid gap-4 sm:hidden p-4" id="reservasMobile">
                    @foreach($reservas as $reserva)
                        <div class="bg-gray-700/30 rounded-xl overflow-hidden border border-gray-700/50">
                            <div class="p-4 space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-400">ID: {{ $reserva->id }}</span>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ 
                                        $reserva->estado === 'pendiente' ? 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/20' :
                                        ($reserva->estado === 'aceptado' ? 'bg-green-500/20 text-green-300 border border-green-500/20' :
                                        'bg-red-500/20 text-red-300 border border-red-500/20') }}">
                                        {{ ucfirst($reserva->estado) }}
                                    </span>
                                </div>
                                
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-400">Profesor</p>
                                        <p class="text-white">{{ $reserva->profesor->usuario->nombre }} {{ $reserva->profesor->usuario->apellidos }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-400">Unidad Didáctica</p>
                                        <p class="text-white">{{ $reserva->unidadDidactica->nombre }}</p>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-gray-700/50">
                                    <button onclick="verDetalles({{ $reserva->id }})" 
                                            class="w-full text-blue-400 hover:text-blue-300 transition-colors text-sm flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver detalles
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Vista desktop: Tabla -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full" id="reservasTable">
                        <thead class="bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">ID</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Profesor</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Unidad Didáctica</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Estado</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50">
                            @foreach($reservas as $reserva)
                                <tr class="hover:bg-gray-700/30 transition-all duration-200">
                                    <td class="px-6 py-4 text-sm text-gray-300">{{ $reserva->id }}</td>
                                    <td class="px-6 py-4 text-sm text-white">
                                        {{ $reserva->profesor->usuario->nombre }} {{ $reserva->profesor->usuario->apellidos }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-300">
                                        {{ $reserva->unidadDidactica->nombre }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ 
                                            $reserva->estado === 'pendiente' ? 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/20' :
                                            ($reserva->estado === 'aceptado' ? 'bg-green-500/20 text-green-300 border border-green-500/20' :
                                            'bg-red-500/20 text-red-300 border border-red-500/20') }}">
                                            {{ ucfirst($reserva->estado) }}
                                        </span>
                                    </td>
                                             <td class="px-6 py-4">
                                <a href="{{ route('reservas.show', $reserva->id) }}" 
                                class="text-blue-400 hover:text-blue-300 transition-colors text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Ver detalles
                                </a>
                            </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mensaje cuando no hay reservas -->
                @if($reservas->isEmpty())
                    <div class="p-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-700/30 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <p class="text-gray-400">No hay reservas registradas</p>
                    </div>
                @endif
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
        const tableBody = document.querySelector('#reservasTable tbody');
        if (tableBody) {
            const rows = tableBody.getElementsByTagName('tr');
            let visibleCount = 0;

            Array.from(rows).forEach(row => {
                if (row.classList.contains('no-results')) return;

                const searchableContent = [
                    row.querySelector('td:nth-child(1)')?.textContent, // ID
                    row.querySelector('td:nth-child(2)')?.textContent, // Profesor
                    row.querySelector('td:nth-child(3)')?.textContent, // Unidad Didáctica
                    row.querySelector('td:nth-child(4)')?.textContent  // Estado
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
                        <td colspan="5" class="py-8 text-center text-gray-400">
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
        const mobileContainer = document.getElementById('reservasMobile');
        if (mobileContainer) {
            const cards = mobileContainer.querySelectorAll('.bg-gray-700\\/30');
            let visibleCount = 0;

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                const shouldShow = text.includes(searchText);
                card.classList.toggle('hidden', !shouldShow);
                if (shouldShow) visibleCount++;
            });

            // Manejar mensaje de "no resultados" en móvil
            let noResultsDiv = mobileContainer.querySelector('.no-results');
            if (visibleCount === 0) {
                if (!noResultsDiv) {
                    noResultsDiv = document.createElement('div');
                    noResultsDiv.className = 'no-results text-center text-gray-400 py-8';
                    noResultsDiv.textContent = `No se encontraron resultados para "${searchText}"`;
                    mobileContainer.appendChild(noResultsDiv);
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

    // Restaurar visibilidad al limpiar la búsqueda
    searchInput.addEventListener('search', function() {
        if (this.value === '') {
            this.dispatchEvent(new Event('input'));
        }
    });
});
</script>
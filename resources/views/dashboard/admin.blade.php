<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-8">
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Main Content Area -->
                <div class="xl:col-span-2 space-y-6">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Stats cards se mantienen igual -->
                    </div>

                    <!-- Reservations Table -->
                    <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 overflow-hidden">
                        <div class="p-6 border-b border-gray-700/50">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <h2 class="text-xl font-light text-white">Historial de Reservas</h2>
                                <input 
                                        type="text" 
                                        id="searchReservas"
                                        class="bg-gray-700/50 border border-gray-600/50 text-gray-200 text-sm rounded-xl px-4 py-2 w-64 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 focus:outline-none"
                                        placeholder="Buscar reservas..."
                                    >
                            </div>
                        </div>
                        
                        <!-- Lista de Reservas Responsive -->
                        <div class="divide-y divide-gray-700/50">
                            @foreach ($reservas as $reserva)
                                <div class="reservation-item" x-data="{ open: false }">
                                    <!-- Cabecera de Reserva -->
                                    <div class="p-4 sm:p-6 hover:bg-gray-700/30 transition-all cursor-pointer"
                                         @click="open = !open">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-center">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 bg-blue-500/10 rounded-xl">
                                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                    </svg>
                                                </div>
                                                <span class="text-white font-medium">Reserva #{{ $reserva->id }}</span>
                                            </div>
                                            
                                            <div class="text-gray-300">
                                                {{ $reserva->profesor->usuario->nombre }}
                                            </div>
                                            
                                            <div class="text-gray-300 hidden lg:block">
                                                {{ $reserva->unidadDidactica->nombre }}
                                            </div>

                                            <!-- Multi-estado para items -->
                                            <div class="flex flex-wrap gap-2">
                                                @php
                                                    $estados = $reserva->detalles->groupBy('estado');
                                                @endphp
                                                @foreach($estados as $estado => $items)
                                                    <span class="px-3 py-1 rounded-lg text-xs font-medium border {{ 
                                                        match($estado) {
                                                            'aprobado' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                                            'rechazado' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                                            'pendiente' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                                                            'prestado' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                                            'devuelto' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                                            default => 'bg-gray-500/10 text-gray-400 border-gray-500/20'
                                                        }
                                                    }}">
                                                        {{ ucfirst($estado) }} ({{ $items->count() }})
                                                    </span>
                                                @endforeach
                                            </div>

                                            <div class="text-right text-gray-300">
                                                <div class="text-sm">{{ $reserva->created_at->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-400">{{ $reserva->created_at->format('h:i A') }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Detalles de Items (Expandible) -->
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                                         x-transition:enter-end="opacity-100 transform translate-y-0"
                                         class="bg-gray-700/30 border-t border-gray-700/50">
                                        <div class="p-4 sm:p-6 space-y-4">
                                            @foreach($reserva->detalles as $detalle)
                                                <div class="bg-gray-800/50 rounded-xl p-4 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="text-white font-medium truncate">
                                                            {{ $detalle->item->descripcion }}
                                                        </h4>
                                                        <div class="mt-1 flex flex-wrap gap-3 text-sm text-gray-400">
                                                            <span>Código: {{ $detalle->item->codigo_bci }}</span>
                                                            <span>Cantidad: {{ $detalle->cantidad_reservada }}</span>
                                                            <span>{{ $detalle->item->armario->salon->nombre_salon }} - {{ $detalle->item->armario->nombre_armario }}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex items-center gap-3">
                                                        <span class="px-3 py-1 rounded-lg text-xs font-medium border {{ 
                                                            match($detalle->estado) {
                                                                'aprobado' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                                                'rechazado' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                                                'pendiente' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                                                                'prestado' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                                                'devuelto' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                                                default => 'bg-gray-500/10 text-gray-400 border-gray-500/20'
                                                            }
                                                        }}">
                                                            {{ ucfirst($detalle->estado) }}
                                                        </span>

                                                        <a href="{{ route('historial.show', $detalle->id) }}" 
                                                           class="text-gray-400 hover:text-white transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Side Charts -->
                <div class="space-y-6">
                    <!-- Categories Chart -->
                    <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 p-6">
                        <h2 class="text-xl font-light text-white mb-6">Reservas por Categoría</h2>
                        <div class="relative aspect-square">
                            <canvas id="categoriasChart"></canvas>
                        </div>
                    </div>

                    <!-- Teachers Chart -->
                    <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 p-6">
                        <h2 class="text-xl font-light text-white mb-6">Top 10 Profesores</h2>
                        <div class="relative" style="height: 300px;">
                            <canvas id="profesoresChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Categorías Chart
        document.addEventListener("DOMContentLoaded", () => {
            const categorias = @json($categorias->pluck('nombre_categoria'));
            const reservas = @json($categorias->pluck('total_reservas'));

            if (categorias.length > 0 && reservas.length > 0) {
                new Chart(document.getElementById('categoriasChart'), {
                    type: 'doughnut',
                    data: {
                        labels: categorias,
                        datasets: [{
                            data: reservas,
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.7)',
                                'rgba(16, 185, 129, 0.7)',
                                'rgba(245, 158, 11, 0.7)',
                                'rgba(239, 68, 68, 0.7)',
                                'rgba(139, 92, 246, 0.7)',
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: '#9CA3AF',
                                    padding: 20,
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Profesores Chart
            const profesores = @json($profesoresTop->pluck('usuario.nombre'));
            const reservasProfesores = @json($profesoresTop->pluck('reservas_count'));

            if (profesores.length > 0 && reservasProfesores.length > 0) {
                new Chart(document.getElementById('profesoresChart'), {
                    type: 'bar',
                    data: {
                        labels: profesores,
                        datasets: [{
                            data: reservasProfesores,
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                            borderRadius: 6,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#9CA3AF',
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            y: {
                                grid: {
                                    color: 'rgba(75, 85, 99, 0.2)'
                                },
                                ticks: {
                                    color: '#9CA3AF',
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            }

  // Funcionalidad de búsqueda en tiempo real
  const searchInput = document.getElementById('searchReservas');
        let debounceTimer;

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const searchText = normalizeText(e.target.value);
                    const reservationItems = document.querySelectorAll('.reservation-item');
                    
                    reservationItems.forEach(item => {
                        const searchableContent = [
                            item.querySelector('.text-white.font-medium')?.textContent, // ID de reserva
                            item.querySelector('.text-gray-300')?.textContent, // Nombre profesor
                            item.querySelector('.hidden.lg\\:block')?.textContent, // Unidad didáctica
                            ...Array.from(item.querySelectorAll('.text-xs.font-medium')).map(el => el.textContent), // Estados
                            item.querySelector('.text-sm')?.textContent, // Fecha
                            ...Array.from(item.querySelectorAll('.text-white.font-medium.truncate')).map(el => el.textContent), // Descripción items
                            ...Array.from(item.querySelectorAll('.text-sm.text-gray-400')).map(el => el.textContent), // Códigos y detalles
                        ].filter(Boolean).join(' ').toLowerCase();

                        const normalized = normalizeText(searchableContent);
                        item.style.display = normalized.includes(searchText) ? '' : 'none';
                    });

                    // Actualizar visibilidad de resultados vacíos
                    const hasVisibleItems = Array.from(reservationItems)
                        .some(item => item.style.display !== 'none');

                    // Mostrar mensaje si no hay resultados
                    updateNoResultsMessage(hasVisibleItems);
                }, 300); // Debounce de 300ms
            });
        }

        // Función para normalizar texto (remover acentos y convertir a minúsculas)
        function normalizeText(text) {
            return text
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .trim();
        }

        // Función para actualizar mensaje de no resultados
        function updateNoResultsMessage(hasResults) {
            let noResultsMsg = document.getElementById('noResultsMessage');
            
            if (!hasResults) {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.id = 'noResultsMessage';
                    noResultsMsg.className = 'p-6 text-center text-gray-400';
                    noResultsMsg.innerHTML = 'No se encontraron resultados';
                    
                    const reservationContainer = document.querySelector('.divide-y.divide-gray-700\\/50');
                    if (reservationContainer) {
                        reservationContainer.appendChild(noResultsMsg);
                    }
                }
                noResultsMsg.style.display = 'block';
            } else if (noResultsMsg) {
                noResultsMsg.style.display = 'none';
            }
        }
        });
    </script>
</x-app-layout>
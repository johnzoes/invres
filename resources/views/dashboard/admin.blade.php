<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-8">
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content Area (Reservations) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 p-6">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-blue-500/10 rounded-xl">
                                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Total Reservas</p>
                                    <h3 class="text-2xl font-semibold text-white">{{ $reservas->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 p-6">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-green-500/10 rounded-xl">
                                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Aprobadas</p>
                                    <h3 class="text-2xl font-semibold text-white">
                                        {{ $reservas->filter(fn($r) => optional($r->detalles->first())->estado === 'aprobado')->count() }}
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 p-6">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-yellow-500/10 rounded-xl">
                                    <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Pendientes</p>
                                    <h3 class="text-2xl font-semibold text-white">
                                        {{ $reservas->filter(fn($r) => optional($r->detalles->first())->estado === 'pendiente')->count() }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reservations Table -->
                    <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 overflow-hidden">
                        <div class="p-6 border-b border-gray-700/50">
                            <div class="flex justify-between items-center">
                                <h2 class="text-xl font-light text-white">Historial de Reservas</h2>
                                <div class="flex items-center gap-2">
                                    <input 
                                        type="text" 
                                        id="searchReservas"
                                        class="bg-gray-700/50 border border-gray-600/50 text-gray-200 text-sm rounded-xl px-4 py-2 w-64 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 focus:outline-none"
                                        placeholder="Buscar reservas..."
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-700/50 text-gray-200">
                                    <tr>
                                        <th class="px-6 py-4 font-medium">ID</th>
                                        <th class="px-6 py-4 font-medium">Profesor</th>
                                        <th class="px-6 py-4 font-medium">Unidad Didáctica</th>
                                        <th class="px-6 py-4 font-medium">Estado</th>
                                        <th class="px-6 py-4 font-medium">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700/50">
                                    @foreach ($reservas as $reserva)
                                    @php
                                        $estado = ucfirst($reserva->detalles->first()->estado ?? 'No definido');
                                        $colorEstado = match($estado) {
                                            'Aprobado' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                            'Rechazado' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                            'Pendiente' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                                            'Prestado' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                            'Devuelto' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                            default => 'bg-gray-500/10 text-gray-400 border-gray-500/20'
                                        };
                                    @endphp
                                    <tr class="hover:bg-gray-700/30 transition-all duration-200">
                                        <td class="px-6 py-4 text-gray-300">#{{ $reserva->id }}</td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-white">
                                                {{ $reserva->profesor->usuario->nombre }} {{ $reserva->profesor->usuario->apellidos }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-300">{{ $reserva->unidadDidactica->nombre }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-lg text-xs font-medium border {{ $colorEstado }}">
                                                {{ $estado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-300">
                                            <div class="text-sm">{{ $reserva->created_at->translatedFormat('M d, Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ $reserva->created_at->format('h:i A') }}</div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

            // Search functionality
            const searchInput = document.getElementById('searchReservas');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchText = e.target.value.toLowerCase();
                    const rows = document.querySelectorAll('tbody tr');
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchText) ? '' : 'none';
                    });
                });
            }
        });
    </script>
</x-app-layout>
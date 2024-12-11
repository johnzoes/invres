<x-app-layout>
    <div class="container mx-auto px-2 sm:px-4 py-6">
        <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 overflow-hidden">
            <div class="p-6 border-b border-gray-700/50">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h2 class="text-xl font-light text-white">Historial de Reservas</h2>
                    @include('components.search-input')
                </div>
            </div>
            
            <!-- Lista de Reservas Agrupada por Fecha -->
            <div class="divide-y divide-gray-700/50">
                @php 
                    $currentDate = null;
                    $mesActualNombre = now()->translatedFormat('F Y');
                @endphp

                @foreach ($reservas as $reserva)
                    @php
                        $reservaDate = $reserva->created_at->translatedFormat('F Y');
                    @endphp

                    @if ($currentDate !== $reservaDate)
                        <div class="bg-gray-700/30 px-6 py-3 flex items-center justify-between">
                            <span class="font-medium text-white">{{ $reservaDate }}</span>
                            <button 
                                onclick="toggleMonth('{{ str_replace(' ', '-', strtolower($reservaDate)) }}')"
                                class="p-1 hover:bg-gray-600/30 rounded-lg transition-colors focus:outline-none">
                                <svg class="w-5 h-5 text-gray-400"
                                     id="icon-{{ str_replace(' ', '-', strtolower($reservaDate)) }}"
                                     viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path d="M5.23 7.21a.75.75 0 011.06.02L10 11.11l3.7-3.88a.75.75 0 111.08 1.04l-4 4.2a.75.75 0 01-1.08 0l-4-4.2a.75.75 0 01.02-1.06z"/>
                                </svg>
                            </button>
                        </div>
                        @php
                            $currentDate = $reservaDate;
                        @endphp
                    @endif

                    <div class="month-group-{{ str_replace(' ', '-', strtolower($reservaDate)) }}">
                        <div class="reservation-item" x-data="{ open: false }">
                            <!-- Cabecera de Reserva -->
                            <div class="p-4 sm:p-6 hover:bg-gray-700/30 transition-all cursor-pointer relative group"
                                 @click="open = !open">
                                <!-- Botón Ver Detalle (Visible en hover) -->
                                <a href="{{ route('reservas.show', $reserva->id) }}" 
                                   class="absolute right-4 top-1/2 -translate-y-1/2 p-2 bg-gray-700/50 rounded-lg opacity-0 group-hover:opacity-100 transition-all hover:bg-gray-600/50"
                                   @click.stop>
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

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

                                    <!-- Estados -->
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($reserva->detalles->groupBy('estado') as $estado => $items)
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
                                <td >
                                    <div class="text-xs">  {{ $reserva->created_at->translatedFormat('M d, Y') }}<br></div>
                                 <div class="text-xs">{{ $reserva->created_at->format('h:i A') }}
                                 </div>  
                                </td>
                                    </div>
                                </div>
                            </div>

                            <!-- Detalles de Items (Expandible) -->
                            <div x-show="open" 
                                 x-cloak
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
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
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchInput');
        let debounceTimer;

        // Función para normalizar texto (remover acentos y convertir a minúsculas)
        function normalizeText(text) {
            return text.toString().toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .trim();
        }

        // Función de búsqueda en tiempo real
        function handleSearch(searchTerm) {
            const normalizedSearch = normalizeText(searchTerm);
            const monthHeaders = document.querySelectorAll('.bg-gray-700\\/30.px-6.py-3');
            const reservationItems = document.querySelectorAll('.reservation-item');
            
            let hasVisibleItems = false;
            const visibleMonths = new Set();

            // Filtrar los items de reserva
            reservationItems.forEach(item => {
                const searchableContent = [
                    item.querySelector('.text-white.font-medium')?.textContent, // ID Reserva
                    item.querySelector('.text-gray-300')?.textContent, // Profesor
                    item.querySelector('.hidden.lg\\:block')?.textContent, // Unidad
                    ...Array.from(item.querySelectorAll('.text-xs.font-medium')).map(el => el.textContent), // Estados
                    ...Array.from(item.querySelectorAll('.text-white.font-medium.truncate')).map(el => el.textContent), // Descripción items
                    ...Array.from(item.querySelectorAll('.text-sm.text-gray-400')).map(el => el.textContent) // Detalles
                ].filter(Boolean).join(' ');

                const isMatch = normalizeText(searchableContent).includes(normalizedSearch);
                const monthGroup = item.closest('[class*="month-group-"]');
                
                if (isMatch) {
                    item.style.display = '';
                    hasVisibleItems = true;
                    if (monthGroup) {
                        visibleMonths.add(monthGroup.className.match(/month-group-[\w-]+/)[0]);
                    }
                } else {
                    item.style.display = 'none';
                }
            });

            // Actualizar visibilidad de los headers de mes
            monthHeaders.forEach(header => {
                const nextElement = header.nextElementSibling;
                if (nextElement) {
                    const monthClass = nextElement.className.match(/month-group-[\w-]+/)?.[0];
                    if (monthClass) {
                        header.style.display = visibleMonths.has(monthClass) ? '' : 'none';
                    }
                }
            });

            // Mostrar/ocultar mensaje de no resultados
            let noResultsMessage = document.getElementById('noResultsMessage');
            if (!hasVisibleItems) {
                if (!noResultsMessage) {
                    noResultsMessage = document.createElement('div');
                    noResultsMessage.id = 'noResultsMessage';
                    noResultsMessage.className = 'p-8 text-center text-gray-400';
                    noResultsMessage.textContent = 'No se encontraron resultados';
                    document.querySelector('.divide-y.divide-gray-700\\/50').appendChild(noResultsMessage);
                }
                noResultsMessage.style.display = 'block';
            } else if (noResultsMessage) {
                noResultsMessage.style.display = 'none';
            }
        }

        // Eventos del buscador
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    handleSearch(e.target.value);
                }, 300);
            });

            // Placeholders dinámicos
            const placeholders = [
                'Buscar por ID de reserva...',
                'Buscar por nombre de profesor...',
                'Buscar por unidad didáctica...',
                'Buscar por código BCI...',
                'Buscar por estado...'
            ];
            let currentPlaceholder = 0;

            function updatePlaceholder() {
                searchInput.placeholder = placeholders[currentPlaceholder];
                currentPlaceholder = (currentPlaceholder + 1) % placeholders.length;
            }

            setInterval(updatePlaceholder, 3000);
            updatePlaceholder();
        }
    });


    function toggleMonth(mesIdentificador) {
            const rows = document.querySelectorAll(`.month-group-${mesIdentificador}`);
            const icon = document.getElementById(`icon-${mesIdentificador}`);
            
            rows.forEach(row => {
                row.classList.toggle('hidden');
            });
            
            icon?.classList.toggle('rotate-180');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const mesActual = '{{ strtolower(str_replace(" ", "-", $mesActualNombre)) }}';
            
            document.querySelectorAll('[class*="month-group-"]').forEach(row => {
                if (!row.classList.contains(`month-group-${mesActual}`)) {
                    row.classList.add('hidden');
                }
            });

            document.querySelectorAll(`[id^="icon-"]:not([id$="${mesActual}"])`).forEach(icon => {
                icon.classList.add('rotate-180');
            });
        });
</script>
@endpush
</x-app-layout>
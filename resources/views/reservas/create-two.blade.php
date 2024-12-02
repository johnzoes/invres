<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Paso 2: Configurar Detalles de Reserva') }}
        </h2>
    </x-slot>

    <div class="flex flex-col items-center py-12">
        <div class="w-full max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 rounded-lg shadow-lg p-8">
                <!-- Resumen de Items Seleccionados -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-white mb-4">Items Seleccionados:</h3>
                    <div class="bg-gray-700 rounded-lg p-4 space-y-3">
                        @if (session('items'))
                            @foreach (session('items') as $categoriaId => $cantidad)
                                @php
                                    $categoria = \App\Models\Categoria::find($categoriaId);
                                @endphp
                                <div class="flex justify-between items-center text-white">
                                    <span>{{ $categoria->nombre_categoria }}</span>
                                    <span class="bg-gray-600 px-3 py-1 rounded-full text-sm">
                                        Cantidad: {{ $cantidad }}
                                    </span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <form action="{{ route('reservas.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Campo de búsqueda para Unidad Didáctica -->
                    <div class="mb-8">
                        <label for="searchUnidad" class="block text-sm font-medium text-gray-300 mb-2">
                            Buscar Unidad Didáctica:
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="searchUnidad" 
                                   class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm p-3 pl-10" 
                                   placeholder="Buscar por nombre..."
                                   oninput="filterUnidades()">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Lista de Unidades Didácticas -->
                    <div class="mb-8">
                        <div id="unidades-container" 
                             class="bg-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto divide-y divide-gray-600">
                            @foreach ($unidades_didacticas as $unidad)
                                <div class="p-4 hover:bg-gray-600 cursor-pointer unidad-item transition-all"
                                     data-nombre="{{ strtolower($unidad->nombre) }}"
                                     onclick="selectUnidad('{{ $unidad->id }}', '{{ $unidad->nombre }}')">
                                    <p class="text-white">
                                        {{ $unidad->nombre }}
                                        <span class="text-sm text-gray-400 ml-2">
                                            (Ciclo: {{ $unidad->ciclo }})
                                        </span>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                        <div id="selected-unidad-container" class="mt-4"></div>
                        <input type="hidden" id="selected-unidad-id" name="id_unidad_didactica">
                    </div>

                    <!-- Selección de Turno -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-300 mb-4">
                            Seleccionar Turno:
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div id="turno-manana" 
                                 class="turno-box flex flex-col items-center justify-center bg-gray-700 cursor-pointer text-white p-6 rounded-lg shadow-md transition-all duration-300"
                                 onclick="selectTurno('mañana')">
                                <x-icon name="day" class="w-12 h-12 mb-3" />
                                <span class="text-lg">Mañana</span>
                            </div>
                            <div id="turno-noche"
                                 class="turno-box flex flex-col items-center justify-center bg-gray-700 cursor-pointer text-white p-6 rounded-lg shadow-md transition-all duration-300"
                                 onclick="selectTurno('noche')">
                                <x-icon name="night" class="w-12 h-12 mb-3" />
                                <span class="text-lg">Noche</span>
                            </div>
                        </div>
                        <input type="hidden" id="selected-turno" name="turno">
                    </div>

                    <!-- Paso los datos de las categorías seleccionadas -->
                    @if (session('items'))
                        @foreach (session('items') as $categoriaId => $cantidad)
                            <input type="hidden" name="categorias[]" value="{{ $categoriaId }}">
                            <input type="hidden" name="cantidad[{{ $categoriaId }}]" value="{{ $cantidad }}">
                        @endforeach
                    @endif

                    <!-- Botón de envío -->
                    <div class="flex justify-end mt-8">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-8 rounded-lg shadow-lg transition-all duration-300">
                            Confirmar Reserva
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function normalizeText(text) {
            return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
        }

        function filterUnidades() {
            const searchInput = normalizeText(document.getElementById('searchUnidad').value);
            const unidades = document.querySelectorAll('.unidad-item');

            unidades.forEach(unidad => {
                const nombre = normalizeText(unidad.getAttribute('data-nombre'));
                unidad.style.display = nombre.includes(searchInput) ? '' : 'none';
            });
        }

        function selectUnidad(id, nombre) {
            const selectedContainer = document.getElementById('selected-unidad-container');
            selectedContainer.innerHTML = `
                <div class="bg-blue-600 text-white p-3 rounded-lg shadow-sm animate-fade-in">
                    <p class="font-medium">Unidad Didáctica Seleccionada: ${nombre}</p>
                </div>`;
            document.getElementById('selected-unidad-id').value = id;
        }

        function selectTurno(turno) {
            document.getElementById('selected-turno').value = turno;
            const mananaBox = document.getElementById('turno-manana');
            const nocheBox = document.getElementById('turno-noche');

            [mananaBox, nocheBox].forEach(box => {
                box.classList.remove('bg-blue-600', 'scale-105');
                box.classList.add('bg-gray-700');
            });

            const selectedBox = turno === 'mañana' ? mananaBox : nocheBox;
            selectedBox.classList.remove('bg-gray-700');
            selectedBox.classList.add('bg-blue-600', 'scale-105');
        }
    </script>
    @endpush
</x-app-layout>
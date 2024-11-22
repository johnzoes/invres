<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Paso 2: Seleccionar Unidad Didáctica y Turno') }}
        </h2>
    </x-slot>


    <div class="flex flex-col items-center py-12">
        <div class="w-full max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 rounded-lg shadow-lg p-8">

                <h1 class="mb-8 text-2xl font-bold text-white text-center">Selecciona Unidad Didáctica y Turno</h1>

                <!-- Formulario para enviar la Unidad Didáctica, Turno e Ítems -->
                <form action="{{ route('reservas.store') }}" method="POST">
                    @csrf

                    <!-- Campo de búsqueda para Unidad Didáctica -->
                    <div class="mb-8">
                        <label for="searchUnidad" class="block text-sm font-medium text-gray-300 mb-2">Buscar Unidad Didáctica:</label>
                        <input type="text" id="searchUnidad" class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm p-3" placeholder="Buscar por nombre..." oninput="filterUnidades()">
                    </div>

                    <!-- Lista de Unidades Didácticas -->
                    <div id="unidades-container" class="bg-gray-800 text-white w-full rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        @foreach ($unidades_didacticas as $unidad)
                            <div class="p-4 hover:bg-gray-600 cursor-pointer unidad-item transition-all" 
                                 data-nombre="{{ strtolower($unidad->nombre) }}" 
                                 onclick="selectUnidad('{{ $unidad->id }}', '{{ $unidad->nombre }}')">
                                <p>{{ $unidad->nombre }} <span class="text-sm text-gray-400">(Ciclo: {{ $unidad->ciclo }})</span></p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Contenedor para la Unidad Didáctica seleccionada -->
                    <div id="selected-unidad-container" class="mt-6"></div>
                    <input type="hidden" id="selected-unidad-id" name="id_unidad_didactica">

                    <!-- Selección de Turno -->
                    <div class="mb-8 mt-10">
                        <label class="block text-sm font-medium text-gray-300 mb-4">Seleccionar Turno:</label>
                        <div class="flex justify-around space-x-6">
                            <div id="turno-manana" class="turno-box flex items-center justify-center bg-gray-700 cursor-pointer text-white p-8 rounded-lg shadow-md w-1/3 transition-all duration-300" onclick="selectTurno('mañana')">
                                <x-icon name="day" class="w-16 h-16 mb-4"></x-icon>
                                <span class="text-lg">Mañana</span>
                            </div>
                            <div id="turno-noche" class="turno-box flex items-center justify-center bg-gray-700 cursor-pointer text-white p-8 rounded-lg shadow-md w-1/3 transition-all duration-300" onclick="selectTurno('noche')">
                                <x-icon name="night" class="w-16 h-16 mb-4"></x-icon>
                                <span class="text-lg">Noche</span>
                            </div>
                        </div>
                        <input type="hidden" id="selected-turno" name="turno">
                    </div>

                    <!-- Ítems Seleccionados -->
                    @if (session('items'))
                        @foreach (session('items') as $itemId => $cantidad)
                            <input type="hidden" name="items[]" value="{{ $itemId }}">
                            <input type="hidden" name="cantidad[{{ $itemId }}]" value="{{ $cantidad }}">
                        @endforeach
                    @endif

                    <!-- Botón para enviar el formulario -->
                    <div class="mt-8 text-center">
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg">
                            Confirmar y Crear Reserva
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Función para normalizar texto eliminando tildes
    function normalizeText(text) {
        return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
    }

    // Función para buscar y filtrar Unidades Didácticas ignorando tildes
    function filterUnidades() {
        const searchInput = normalizeText(document.getElementById('searchUnidad').value);
        const unidades = document.querySelectorAll('.unidad-item');

        unidades.forEach(unidad => {
            const nombre = normalizeText(unidad.getAttribute('data-nombre'));
            unidad.style.display = nombre.includes(searchInput) ? '' : 'none';
        });
    }

    // Función para seleccionar una Unidad Didáctica
    function selectUnidad(id, nombre) {
        const selectedContainer = document.getElementById('selected-unidad-container');
        selectedContainer.innerHTML = `
            <div class="bg-gray-700 border border-gray-600 p-4 rounded-lg shadow-sm mb-6">
                <p class="text-white">Unidad Didáctica Seleccionada: ${nombre}</p>
            </div>`;
        
        // Guardar la unidad seleccionada en un campo oculto
        document.getElementById('selected-unidad-id').value = id;
    }

   // Función para seleccionar el turno y mantener el color azul seleccionado
   function selectTurno(turno) {
        // Guardar el turno seleccionado en el campo oculto
        document.getElementById('selected-turno').value = turno;

        // Obtener los elementos de los botones
        const mananaBox = document.getElementById('turno-manana');
        const nocheBox = document.getElementById('turno-noche');

        // Restablecer ambos botones al estado original
        mananaBox.classList.remove('bg-blue-600', 'scale-105');
        mananaBox.classList.add('bg-gray-700');

        nocheBox.classList.remove('bg-blue-600', 'scale-105');
        nocheBox.classList.add('bg-gray-700');

        // Aplicar la clase activa al botón seleccionado
        if (turno === 'mañana') {
            mananaBox.classList.remove('bg-gray-700');
            mananaBox.classList.add('bg-blue-600', 'scale-105');
        } else if (turno === 'noche') {
            nocheBox.classList.remove('bg-gray-700');
            nocheBox.classList.add('bg-blue-600', 'scale-105');
        }
    }
</script>

</x-app-layout>

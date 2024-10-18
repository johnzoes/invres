<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Reserva') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h1 class="mb-6 text-lg font-bold">Detalles de la Reserva</h1>

                    <!-- Formulario -->
                    <form action="{{ route('reservas.store') }}" method="POST">
                        @csrf

                        <!-- Unidad Didáctica -->
                        <div class="mb-4">
                            <label for="unidad_didactica" class="block text-sm font-medium text-gray-700">Unidad Didáctica:</label>
                            <select name="id_unidad_didactica" id="unidad_didactica" class="form-select mt-1 block w-full">
                                @foreach ($unidades_didacticas as $unidad)
                                    <option value="{{ $unidad->id }}">{{ $unidad->nombre }} (Ciclo: {{ $unidad->ciclo }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Select de Ítems -->
                        <div class="mb-4">
                            <label for="items" class="block text-sm font-medium text-gray-700">Buscar y Seleccionar Ítems:</label>
                            <select id="items" name="items[]" class="form-select mt-1 block w-full select2" multiple="multiple">
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->descripcion }} (Cantidad: {{ $item->cantidad }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Contenedor para las cantidades dinámicas -->
                        <div id="cantidad-container" class="mb-4">
                            <!-- Aquí se insertarán los campos de cantidad dinámicamente -->
                        </div>

                        <!-- Botón para enviar -->
                        <div class="mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Crear Reserva
                            </button>
                        </div>
                    </form>

                    <!-- Enlace para regresar a la lista de reservas -->
                    <div class="mt-4">
                        <a href="{{ route('reservas.index') }}" class="text-blue-500 hover:text-blue-700">Volver a la lista de reservas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Inicializa Select2
        $('#items').select2();

        // Controla los campos dinámicos para cantidades
        $('#items').on('change', function() {
            var selectedItems = $(this).val(); // Obtiene los ítems seleccionados
            var itemsData = @json($items); // Convertir datos de PHP a JS
            var container = $('#cantidad-container'); // Contenedor para los campos de cantidad
            container.empty(); // Limpiar contenedor

            // Por cada ítem seleccionado, crear un input para cantidad
            selectedItems.forEach(function(itemId) {
                var item = itemsData.find(i => i.id == itemId); // Buscar el ítem por ID

                if (item) {
                    // Crear HTML para el input de cantidad
                    var cantidadInput = `
                        <div class="mb-4">
                            <label for="cantidad_${itemId}" class="block text-sm font-medium text-gray-700">Cantidad para ${item.descripcion}:</label>
                            <input type="number" name="cantidad[${itemId}]" id="cantidad_${itemId}" min="1" max="${item.cantidad}" class="form-input mt-1 block w-full" placeholder="Máximo: ${item.cantidad}">
                        </div>
                    `;
                    container.append(cantidadInput); // Añadir input al contenedor
                }
            });
        });
    </script>
</x-app-layout>

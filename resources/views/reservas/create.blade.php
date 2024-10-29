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
                                    <option value="{{ $item->id }}" data-description="{{ $item->descripcion }}" data-max="{{ $item->cantidad }}">
                                        {{ $item->descripcion }} (Cantidad: {{ $item->cantidad }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Contenedor para las cantidades dinámicas -->
                        <div id="selected-items-container" class="mt-4">
                            <!-- Aquí se insertarán los chips dinámicamente -->
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

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Inicializa Select2
            $('#items').select2();

            // Manejar cambios en el selector de items
            $('#items').on('change', function() {
                var selectedItems = $(this).select2('data'); // Obtiene los datos seleccionados
                var container = $('#selected-items-container'); // Contenedor para los chips
                container.empty(); // Limpiar el contenedor

                // Crear un chip y un campo de cantidad para cada ítem seleccionado
                selectedItems.forEach(function(item) {
                    // Obtener los datos adicionales del ítem
                    var description = item.element.getAttribute('data-description');
                    var maxQuantity = item.element.getAttribute('data-max');

                    // Crear el chip con un campo de entrada para cantidad
                    var chipHtml = `
                        <div class="chip flex items-center bg-gray-200 p-2 rounded-lg mb-2 mr-2">
                            <span class="mr-2">${description}</span>
                            <input type="number" name="cantidad[${item.id}]" min="1" max="${maxQuantity}" class="form-input w-20" placeholder="Cantidad" required>
                            <button type="button" class="ml-2 text-red-500 remove-chip" data-id="${item.id}">&times;</button>
                        </div>
                    `;

                    // Agregar el chip al contenedor
                    container.append(chipHtml);
                });

                // Agregar evento para eliminar chips
                $('.remove-chip').click(function() {
                    var itemId = $(this).data('id');
                    // Remueve el chip visualmente
                    $(this).parent().remove();
                    // Desmarcar el ítem en el select2
                    var selectedItems = $('#items').val();
                    selectedItems = selectedItems.filter(value => value != itemId);
                    $('#items').val(selectedItems).trigger('change');
                });
            });
        });
    </script>
    @endpush

</x-app-layout>

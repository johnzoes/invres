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
                            <select name="id_unidad_didactica" id="unidad_didactica" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @foreach ($unidades_didacticas as $unidad)
                                    <option value="{{ $unidad->id }}">{{ $unidad->nombre }} (Ciclo: {{ $unidad->ciclo }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Select de Ítems -->
                        <div class="mb-4">
                            <label for="items" class="block text-sm font-medium text-gray-700">Buscar y Seleccionar Ítems:</label>
                            <select id="items" name="items[]" class="form-select mt-1 block w-full select2 border-gray-300 rounded-md shadow-sm" multiple="multiple">
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}" data-description="{{ $item->descripcion }}" data-max="{{ $item->cantidad }}">
                                        {{ $item->descripcion }} (Cantidad: {{ $item->cantidad }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Contenedor para las cantidades dinámicas -->
                        <div id="selected-items-container" class="mt-4 flex flex-wrap gap-4">
                            <!-- Aquí se insertarán los chips dinámicamente -->
                        </div>

                        <!-- Campo para seleccionar profesor, visible solo para administradores -->
                         @if(auth()->user()->hasRole('admin'))
                            <div class="mb-4">
                                <label for="id_profesor" class="block text-sm font-medium text-gray-700">Seleccione el Profesor:</label>
                                <select name="id_profesor" id="id_profesor" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Seleccione un profesor</option>
                                    @foreach ($profesores as $profesor)
                                        <option value="{{ $profesor->id }}">{{ $profesor->usuario->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif


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

                // Crear un chip y un campo de entrada para cantidad
                selectedItems.forEach(function(item) {
                    var description = item.element.getAttribute('data-description');
                    var maxQuantity = item.element.getAttribute('data-max');

                    var chipHtml = `
                        <div class="chip flex items-center bg-gray-100 border border-gray-300 p-2 rounded-md shadow-sm mb-2 mr-2 w-full md:w-auto">
                            <span class="text-gray-700 font-semibold mr-2">${description}</span>
                            <input type="number" name="cantidad[${item.id}]" min="1" max="${maxQuantity}" class="form-input w-20 text-center border border-gray-300 rounded-md mx-2" placeholder="Cantidad" required>
                            <button type="button" class="ml-2 text-red-500 font-bold remove-chip" data-id="${item.id}">&times;</button>
                        </div>
                    `;

                    container.append(chipHtml);
                });

                // Agregar evento para eliminar chips
                $('.remove-chip').click(function() {
                    var itemId = $(this).data('id');
                    $(this).parent().remove();
                    var selectedItems = $('#items').val();
                    selectedItems = selectedItems.filter(value => value != itemId);
                    $('#items').val(selectedItems).trigger('change');
                });
            });
        });
    </script>
    @endpush
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Nueva Reserva') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="mb-6 text-lg font-bold">Crear Nueva Reserva</h1>

                    <form action="{{ route('reservas.store') }}" method="POST">
                        @csrf

            <!-- Unidad Didáctica -->
            <div class="mb-4">
    <label for="unidad_didactica" class="block text-sm font-medium text-gray-700">Unidad Didáctica:</label>
    <select name="id_unidad_didactica" id="unidad_didactica" class="form-select mt-1 block w-full" required>
        <option value="">Selecciona una unidad</option> <!-- Opción vacía para forzar selección -->
        @foreach($unidades_didacticas as $unidad)
            <option value="{{ $unidad->id_unidad_didactica }}" {{ old('id_unidad_didactica') == $unidad->id_unidad_didactica ? 'selected' : '' }}>
                {{ $unidad->nombre }}
            </option>
        @endforeach
    </select>
</div>


                        <!-- Ítems -->
<!-- Ítems -->
<h3 class="mb-4 text-md font-semibold">Seleccionar Ítems</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach($items as $item)
        <div class="flex items-center">
            <input type="checkbox" id="item_{{ $item->id }}" name="items[{{ $item->id }}][id_item]" value="{{ $item->id }}" class="form-checkbox" onchange="toggleQuantityField({{ $item->id }})">
            <label for="item_{{ $item->id }}" class="ml-2">{{ $item->descripcion }}</label>

            <input type="number" name="items[{{ $item->id }}][cantidad_reservada]" min="1" placeholder="Cantidad" class="form-input ml-4 w-24" id="cantidad_{{ $item->id }}" style="display:none;">
        </div>
    @endforeach
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

    <!-- JavaScript para mostrar/ocultar el campo de cantidad -->
    <script>
        function toggleQuantityField(itemId) {
            var checkbox = document.getElementById('item_' + itemId);
            var cantidadField = document.getElementById('cantidad_' + itemId);

            if (checkbox.checked) {
                cantidadField.style.display = 'block';  // Muestra el campo de cantidad
            } else {
                cantidadField.style.display = 'none';  // Oculta el campo de cantidad si el checkbox no está seleccionado
                cantidadField.value = '';  // Resetea el valor del campo de cantidad
            }
        }
    </script>
</x-app-layout>

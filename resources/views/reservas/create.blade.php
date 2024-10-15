<!-- resources/views/reservas/create.blade.php -->
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

                        <!-- Profesor -->
                        @if(auth()->user()->hasRole('admin'))
                            <div class="mb-4">
                                <label for="profesor" class="block text-sm font-medium text-gray-700">Profesor:</label>
                                <select name="id_profesor" id="profesor" class="form-select mt-1 block w-full">
                                    @foreach($profesores as $profesor)
                                        <option value="{{ $profesor->id_profesor }}" {{ old('id_profesor') == $profesor->id_profesor ? 'selected' : '' }}>
                                            {{ $profesor->usuario->nombre }} {{ $profesor->usuario->apellidos }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <!-- Si el usuario es profesor, automáticamente selecciona su id -->
                            <input type="hidden" name="id_profesor" value="{{ auth()->user()->profesor->id_profesor }}">
                        @endif

                        <!-- Unidad Didáctica -->
                        <div class="mb-4">
                            <label for="unidad_didactica" class="block text-sm font-medium text-gray-700">Unidad Didáctica:</label>
                            <select name="id_unidad_didactica" id="unidad_didactica" class="form-select mt-1 block w-full">
                                @foreach($unidades_didacticas as $unidad)
                                    <option value="{{ $unidad->id_unidad_didactica }}" {{ old('id_unidad_didactica') == $unidad->id_unidad_didactica ? 'selected' : '' }}>
                                        {{ $unidad->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Ítems -->
                        <h3 class="mb-4 text-md font-semibold">Seleccionar Ítems</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($items as $item)
                                <div class="flex items-center">
                                    <input type="checkbox" id="item_{{ $item->id_item }}" name="items[{{ $item->id_item }}][id_item]" value="{{ $item->id_item }}" class="form-checkbox">
                                    <label for="item_{{ $item->id_item }}" class="ml-2">{{ $item->descripcion }}</label>

                                    <input type="number" name="items[{{ $item->id_item }}][cantidad_reservada]" min="1" placeholder="Cantidad" class="form-input ml-4 w-24" value="{{ old('items.'.$item->id_item.'.cantidad_reservada') }}">
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
</x-app-layout>

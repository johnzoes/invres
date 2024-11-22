<!-- resources/views/items/show.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles del Ítem') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h1 class="text-lg font-bold mb-6">Detalles del Ítem</h1>

                    <div class="mb-4">
                        <strong>Descripción:</strong>
                        <p>{{ $item->descripcion }}</p>
                    </div>

                    @if(isset($item) && $item->imagen)
    <img src="{{ asset('storage/' . $item->imagen) }}" alt="Imagen del Ítem" class="w-32 h-32 object-cover rounded-lg">
@endif


                    <div class="mb-4">
                        <strong>Código BCI:</strong>
                        <p>{{ $item->codigo_bci }}</p>
                    </div>

                    <div class="mb-4">
                        <strong>Cantidad:</strong>
                        <p>{{ $item->cantidad }}</p>
                    </div>

                    <div class="mb-4">
                        <strong>Tipo:</strong>
                        <p>{{ ucfirst($item->tipo) }}</p>
                    </div>

                    <div class="mb-4">
                        <strong>Marca:</strong>
                        <p>{{ $item->marca ?? 'No especificada' }}</p>
                    </div>

                    <div class="mb-4">
                        <strong>Modelo:</strong>
                        <p>{{ $item->modelo ?? 'No especificado' }}</p>
                    </div>

                    <div class="mb-4">
                        <strong>Categoría:</strong>
                        <p>{{ $item->categoria->nombre_categoria }}</p>
                    </div>

                    <div class="mb-4">
                        <strong>Armario:</strong>
                        <p>{{ $item->armario->nombre_armario }} ({{ $item->armario->salon->nombre_salon }})</p>
                    </div>

                    <div class="mb-4">
                        <strong>Número de Inventario:</strong>
                        <p>{{ $item->nro_inventariado }}</p>
                    </div>

                    <div class="mb-4">
                        <strong>Imagen:</strong>
                        @if($item->imagen)
                            <img src="{{ asset('storage/' . $item->imagen) }}" alt="Imagen del Ítem" class="w-1/4 h-auto">
                        @else
                            <p>No hay imagen disponible</p>
                        @endif
                    </div>



                    <div class="mt-6">
                        <a href="{{ route('items.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Volver a la lista de Ítems
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- resources/views/items/index.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Ítems') }}
        </h2>
    </x-slot>

    <div class="flex flex-col ml-64 py-12">
        <div class="w-full max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Botón para agregar un nuevo ítem -->
                    <div class="flex justify-end mb-6">
                        <a href="{{ route('items.create') }}" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-5 rounded-lg">
                            Agregar Nuevo Ítem
                        </a>
                    </div>

                    <h1 class="text-2xl font-semibold mb-6">Listado de Ítems</h1>

                    <!-- Mensaje de éxito -->
                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Tabla de Ítems -->
                    <table class="w-full table-auto text-left bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-center rounded-l-lg">ID</th>
                                <th class="py-3 px-6 text-center">Descripción</th>
                                <th class="py-3 px-6 text-center">Cantidad</th>
                                <th class="py-3 px-6 text-center">Tipo</th>
                                <th class="py-3 px-6 text-center">Marca</th>
                                <th class="py-3 px-6 text-center rounded-r-lg">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 dark:text-gray-100 text-sm font-light">
                            @foreach($items as $item)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="py-3 px-6 text-center rounded-l-lg">{{ $item->id }}</td>
                                    <td class="py-3 px-6 text-center">{{ $item->descripcion }}</td>
                                    <td class="py-3 px-6 text-center">{{ $item->cantidad }}</td>
                                    <td class="py-3 px-6 text-center">{{ $item->tipo }}</td>
                                    <td class="py-3 px-6 text-center">{{ $item->marca }}</td>
                                    <td class="py-3 px-6 flex justify-center space-x-4 rounded-r-lg">
                                        <a href="{{ route('items.show', $item->id) }}" class="text-blue-500 hover:text-blue-700">
                                            <x-icon name="view"></x-icon>
                                        </a>
                                        <a href="{{ route('items.edit', $item->id) }}" class="text-yellow-500 hover:text-yellow-700">
                                            <x-icon name="edit"></x-icon>
                                        </a>
                                        <a href="#" class="text-red-500 hover:text-red-700" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $item->id }}').submit();">
                                            <x-icon name="delete"></x-icon>
                                        </a>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('items.destroy', $item->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

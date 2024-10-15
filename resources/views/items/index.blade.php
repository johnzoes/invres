<!-- resources/views/items/index.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ítems') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Botón para agregar un nuevo ítem -->
                    <a href="{{ route('items.create') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                        Agregar Nuevo Ítem
                    </a>

                    <h1 class="text-2xl font-semibold mb-6">Listado de Ítems</h1>

                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                <th class="py-2 px-4">ID</th>
                                <th class="py-2 px-4">Descripción</th>
                                <th class="py-2 px-4">Cantidad</th>
                                <th class="py-2 px-4">Tipo</th>
                                <th class="py-2 px-4">Marca</th>
                                <th class="py-2 px-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr class="border-t dark:border-gray-600">
                                    <td class="py-2 px-4">{{ $item->id }}</td>
                                    <td class="py-2 px-4">{{ $item->descripcion }}</td>
                                    <td class="py-2 px-4">{{ $item->cantidad }}</td>
                                    <td class="py-2 px-4">{{ $item->tipo }}</td>
                                    <td class="py-2 px-4">{{ $item->marca }}</td>
                                    <td class="py-2 px-4">
                                        <a href="{{ route('items.show', $item->id) }}" class="text-blue-500 hover:underline">Ver</a> |
                                        <a href="{{ route('items.edit', $item->id) }}" class="text-yellow-500 hover:underline">Editar</a> |
                                        <a href="#" class="text-red-500 hover:underline" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $item->id }}').submit();">Eliminar</a>
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

<!-- resources/views/salones/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lista de Salones') }}
        </h2>
    </x-slot>

    @if (session('success'))
    <div class="bg-green-100 text-green-800 border border-green-200 p-4 rounded-lg mb-4">
        {{ session('success') }}
    </div>
@endif

    @if (session('error'))
        <div class="bg-red-100 text-red-800 border border-red-200 p-4 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Botones para filtrar -->
                    <div class="mb-4">
                        <a href="{{ route('salones.index', ['filter' => 'my-items']) }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            Mis Ítems
                        </a>
                        <a href="{{ route('salones.index', ['filter' => 'all-items']) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                            Todos los Ítems
                        </a>
                    </div>

                    <!-- Tabla de salones -->
                    <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                <th class="py-2 text-left px-4">ID</th>
                                <th class="py-2 text-left px-4">Nombre</th>
                                <th class="py-2 text-left px-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salones as $salon)
                                <tr class="border-t dark:border-gray-600">
                                    <td class="py-2 px-4">{{ $salon->id }}</td>
                                    <td class="py-2 px-4">{{ $salon->nombre_salon }}</td>
                                    <td class="py-2 px-4">
                                        <!-- Botón para editar -->
                                        <a href="{{ route('salones.edit', $salon->id) }}" class="text-blue-500 hover:underline">Editar</a> |
                                        
                                        <!-- Botón para eliminar -->
                                        <a href="#" class="text-red-500 hover:underline"
                                           onclick="event.preventDefault(); 
                                           if (confirm('¿Estás seguro de que deseas eliminar este salón?')) {
                                               document.getElementById('delete-form-{{ $salon->id }}').submit();
                                           }">
                                           Eliminar
                                        </a>
                                        <!-- Formulario de eliminación -->
                                        <form id="delete-form-{{ $salon->id }}" action="{{ route('salones.destroy', $salon->id) }}" method="POST" style="display: none;">
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

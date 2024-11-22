<!-- resources/views/armarios/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lista de Armarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Mensaje de éxito -->
                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Botón para agregar un nuevo armario -->
                    <a href="{{ route('armarios.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                        Agregar Nuevo Armario
                    </a>

                    <!-- Tabla de armarios -->
                    <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                <th class="py-2 text-left px-4">ID</th>
                                <th class="py-2 text-left px-4">Nombre</th>
                                <th class="py-2 text-left px-4">Salón</th>
                                <th class="py-2 text-left px-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($armarios as $armario)
                                <tr class="border-t dark:border-gray-600">
                                    <td class="py-2 text-left px-4">{{ $armario->id }}</td>
                                    <td class="py-2 text-left px-4">{{ $armario->nombre_armario }}</td>
                                    <td class="py-2 text-left  px-4">{{ $armario->salon->nombre_salon }}</td>
                                    <td class="py-2 text-left px-4">
                                        <!-- Botón para editar -->
                                        <a href="{{ route('armarios.edit', $armario->id) }}" class="text-blue-500 hover:underline">Editar</a> |
                                        
                                        <!-- Botón para eliminar -->
                                        <a href="#" class="text-red-500 hover:underline"
                                           onclick="event.preventDefault(); 
                                           if (confirm('¿Estás seguro de que deseas eliminar este armario?')) {
                                               document.getElementById('delete-form-{{ $armario->id }}').submit();
                                           }">
                                           Eliminar
                                        </a>
                                        <!-- Formulario de eliminación -->
                                        <form id="delete-form-{{ $armario->id }}" action="{{ route('armarios.destroy', $armario->id) }}" method="POST" style="display: none;">
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

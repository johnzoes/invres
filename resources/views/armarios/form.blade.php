<!-- resources/views/armarios/form.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ isset($armario) ? 'Editar Armario' : 'Crear Nuevo Armario' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h1 class="mb-6 text-lg font-bold">{{ isset($armario) ? 'Editar Armario' : 'Crear Nuevo Armario' }}</h1>

                    <form action="{{ isset($armario) ? route('armarios.update', $armario->id) : route('armarios.store') }}" method="POST">
                        @csrf
                        @if(isset($armario))
                            @method('PUT')
                        @endif

                        <!-- Nombre del armario -->
                        <div class="mb-4">
                            <label for="nombre_armario" class="block text-sm font-medium text-gray-700">Nombre del Armario:</label>
                            <input type="text" name="nombre_armario" id="nombre_armario" class="form-input mt-1 block w-full rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-indigo-300 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" value="{{ old('nombre_armario', $armario->nombre_armario ?? '') }}" required>
                        </div>

                        <!-- Salón -->
                        <div class="mb-4">
                            <label for="id_salon" class="block text-sm font-medium text-gray-700">Salón:</label>
                            <select name="id_salon" id="id_salon" class="form-select mt-1 block w-full rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-indigo-300 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" required>
                                @foreach($salones as $salon)
                                    <option value="{{ $salon->id }}" {{ isset($armario) && $armario->id_salon == $salon->id ? 'selected' : '' }}>
                                        {{ $salon->nombre_salon }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Botón de enviar -->
                        <div class="mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ isset($armario) ? 'Actualizar Armario' : 'Crear Armario' }}
                            </button>
                        </div>
                    </form>

                    <!-- Enlace para volver a la lista -->
                    <div class="mt-4">
                        <a href="{{ route('armarios.index') }}" class="text-blue-500 hover:text-blue-700">Volver a la lista de armarios</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

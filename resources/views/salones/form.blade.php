<!-- resources/views/salones/form.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ isset($salon) ? 'Editar Salón' : 'Crear Nuevo Salón' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Título del formulario -->
                    <h1 class="mb-6 text-lg font-bold">{{ isset($salon) ? 'Editar Salón' : 'Crear Nuevo Salón' }}</h1>

                    <!-- Formulario para crear/editar salón -->
                    <form action="{{ isset($salon) ? route('salones.update', $salon->id) : route('salones.store') }}" method="POST">
                        @csrf
                        @if(isset($salon))
                            @method('PUT')
                        @endif

                        <!-- Campo: Nombre del Salón -->
                        <div class="mb-4">
                            <label for="nombre_salon" class="block text-sm font-medium text-gray-700">Nombre del Salón:</label>
                            <input type="text" name="nombre_salon" id="nombre_salon" class="form-input mt-1 block w-full rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-indigo-300 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" value="{{ old('nombre_salon', $salon->nombre_salon ?? '') }}" required>
                        </div>

                        <!-- Botón de enviar -->
                        <div class="mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ isset($salon) ? 'Actualizar Salón' : 'Crear Salón' }}
                            </button>
                        </div>
                    </form>

                    <!-- Enlace para volver a la lista de salones -->
                    <div class="mt-4">
                        <a href="{{ route('salones.index') }}" class="text-blue-500 hover:text-blue-700">Volver a la lista de salones</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

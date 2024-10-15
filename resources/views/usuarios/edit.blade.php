<!-- resources/views/usuarios/edit.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-lg font-bold">Editar Usuario</h1>

                    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nombre de Usuario -->
                        <div class="mt-4">
                            <x-input-label for="nombre_usuario" :value="__('Nombre de Usuario')" />
                            <x-text-input id="nombre_usuario" class="block mt-1 w-full" type="text" name="nombre_usuario" value="{{ old('nombre_usuario', $usuario->nombre_usuario) }}" required autofocus />
                            <x-input-error :messages="$errors->get('nombre_usuario')" class="mt-2" />
                        </div>

                        <!-- Nombre -->
                        <div class="mt-4">
                            <x-input-label for="nombre" :value="__('Nombre')" />
                            <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required />
                            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                        </div>

                        <!-- Apellidos -->
                        <div class="mt-4">
                            <x-input-label for="apellidos" :value="__('Apellidos')" />
                            <x-text-input id="apellidos" class="block mt-1 w-full" type="text" name="apellidos" value="{{ old('apellidos', $usuario->apellidos) }}" required />
                            <x-input-error :messages="$errors->get('apellidos')" class="mt-2" />
                        </div>

                        <!-- Rol -->
                        <div class="mt-4">
                            <x-input-label for="rol" :value="__('Rol')" />
                            <select name="rol" id="rol" class="block mt-1 w-full dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->name }}" {{ $usuario->roles->pluck('name')->contains($rol->name) ? 'selected' : '' }}>
                                        {{ ucfirst($rol->name) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('rol')" class="mt-2" />
                        </div>

                        <!-- Información específica para Asistentes -->
                        <div id="asistente-info" class="mt-4" style="display: none;">
                            <div class="mt-4">
                                <x-input-label for="id_salon" :value="__('Salón')" />
                                <select name="id_salon" id="id_salon" class="block mt-1 w-full dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach($salones as $salon)
                                        <option value="{{ $salon->id }}" {{ isset($usuario->asistente) && $usuario->asistente->id_salon == $salon->id ? 'selected' : '' }}>
                                            {{ $salon->nombre_salon }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('id_salon')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="turno" :value="__('Turno')" />
                                <select name="turno" id="turno" class="block mt-1 w-full dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="mañana" {{ isset($usuario->asistente) && $usuario->asistente->turno == 'mañana' ? 'selected' : '' }}>Mañana</option>
                                    <option value="noche" {{ isset($usuario->asistente) && $usuario->asistente->turno == 'noche' ? 'selected' : '' }}>Noche</option>
                                </select>
                                <x-input-error :messages="$errors->get('turno')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Botón de enviar -->
                        <div class="mt-4">
                            <x-primary-button>
                                {{ __('Actualizar Usuario') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <a href="{{ route('usuarios.index') }}" class="inline-block mt-4 text-blue-500 hover:text-blue-700">
                        {{ __('Volver a la lista de usuarios') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mostrar el formulario de asistente si el rol seleccionado es 'asistente'
        document.getElementById('rol').addEventListener('change', function() {
            var asistenteInfo = document.getElementById('asistente-info');
            if (this.value === 'asistente') {
                asistenteInfo.style.display = 'block';
            } else {
                asistenteInfo.style.display = 'none';
            }
        });

        // Mostrar el formulario de asistente si ya está seleccionado
        window.onload = function() {
            if (document.getElementById('rol').value === 'asistente') {
                document.getElementById('asistente-info').style.display = 'block';
            }
        }
    </script>
</x-app-layout>

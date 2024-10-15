<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2>Crear Usuario</h2>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('usuarios.store') }}" method="POST">
                        @csrf

                        <!-- Nombre de usuario -->
                        <div class="mt-4">
                            <x-input-label for="nombre_usuario" :value="__('Nombre de Usuario')" />
                            <x-text-input id="nombre_usuario" class="block mt-1 w-full" type="text" name="nombre_usuario" :value="old('nombre_usuario')" required autofocus />
                            <x-input-error :messages="$errors->get('nombre_usuario')" class="mt-2" />
                        </div>

                        <!-- Nombre -->
                        <div class="mt-4">
                            <x-input-label for="nombre" :value="__('Nombre')" />
                            <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" required />
                            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                        </div>

                        <!-- Apellidos -->
                        <div class="mt-4">
                            <x-input-label for="apellidos" :value="__('Apellidos')" />
                            <x-text-input id="apellidos" class="block mt-1 w-full" type="text" name="apellidos" :value="old('apellidos')" required />
                            <x-input-error :messages="$errors->get('apellidos')" class="mt-2" />
                        </div>

                        <!-- Correo Electrónico -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Correo Electrónico')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Contraseña -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Contraseña')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Rol -->
                        <div class="mt-4">
                            <x-input-label for="rol" :value="__('Rol')" />
                            <select name="rol" id="rol" class="block mt-1 w-full">
                                <option value="">Selecciona un rol</option>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->name }}" {{ old('rol') == $rol->name ? 'selected' : '' }}>
                                        {{ ucfirst($rol->name) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('rol')" class="mt-2" />
                        </div>

                        <!-- Campos para Asistentes -->
                        <div id="asistente-fields" style="display: none;" class="mt-4">
                            <div>
                                <x-input-label for="id_salon" :value="__('Salón')" />
                                <select name="id_salon" id="id_salon" class="block mt-1 w-full">
                                    <option value="">Selecciona un salón</option>
                                    @foreach($salones as $salon)
                                        <option value="{{ $salon->id }}" {{ old('id_salon') == $salon->id ? 'selected' : '' }}>
                                            {{ $salon->nombre_salon }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('id_salon')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="turno" :value="__('Turno')" />
                                <select name="turno" id="turno" class="block mt-1 w-full">
                                    <option value="">Selecciona un turno</option>
                                    <option value="mañana" {{ old('turno') == 'mañana' ? 'selected' : '' }}>Mañana</option>
                                    <option value="noche" {{ old('turno') == 'noche' ? 'selected' : '' }}>Noche</option>
                                </select>
                                <x-input-error :messages="$errors->get('turno')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Botón de enviar -->
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-3">
                                {{ __('Crear Usuario') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('rol').addEventListener('change', function () {
            var asistenteFields = document.getElementById('asistente-fields');
            if (this.value === 'asistente') {
                asistenteFields.style.display = 'block';
            } else {
                asistenteFields.style.display = 'none';
            }
        });
    </script>
</x-app-layout>

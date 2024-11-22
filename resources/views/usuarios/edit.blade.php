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

                        <!-- Email -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ old('email', $usuario->email) }}" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Botón de enviar -->
                        <div class="mt-4">
                            <x-primary-button type="submit">
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
</x-app-layout>

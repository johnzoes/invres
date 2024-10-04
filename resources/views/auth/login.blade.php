@extends('layouts.guest')

@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="nombre_usuario" :value="__('Nombre de Usuario')" />
            <x-text-input id="nombre_usuario" class="block mt-1 w-full" type="text" name="nombre_usuario" :value="old('nombre_usuario')" required autofocus />
            <x-input-error :messages="$errors->get('nombre_usuario')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-3">
                {{ __('Iniciar Sesión') }}
            </x-primary-button>
        </div>
    </form>
@endsection

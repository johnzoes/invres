<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 w-64 fixed h-full overflow-y-auto">
    <div class="h-full flex flex-col">

        <!-- Logo -->
        <div class="p-4">
        </div>

        <!-- Enlaces de Navegación -->
        <div class="flex-1 flex flex-col mt-5">
            <nav class="flex flex-col flex-1 px-2 bg-white dark:bg-gray-800 space-y-1">

         <!-- Botón de Notificaciones -->
         <div class="relative mb-4">
                <button id="notificationButton" class="relative flex items-center">
                    <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if(isset($notificaciones) && $notificaciones->count() > 0)
                        <span class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 rounded-full bg-red-500 px-2 text-white text-xs">
                            {{ $notificaciones->count() }}
                        </span>
                    @endif
                </button>
            </div>

            <!-- Incluir el dropdown de notificaciones -->  
                @include('components.notification-dropdown')

                <!-- Enlace de Inicio (visible para todos) -->
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <x-icon name="home" />
                    <span>{{ __('Inicio') }}</span>
                </x-nav-link>

                <!-- Usuarios (Solo visible para Administradores) -->
                @can('ver usuarios')
                    <x-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">
                        <x-icon name="users" />
                        <span>{{ __('Usuarios') }}</span>
                    </x-nav-link>
                @endcan

                <!-- Ítems (visible para Administradores y Asistentes) -->
                @can('ver items')
                    <x-nav-link :href="route('items.index')" :active="request()->routeIs('items.*')">
                        <x-icon name="items" />
                        <span>{{ __('Ítems') }}</span>
                    </x-nav-link>
                @endcan

                <!-- Salones (visible para Administradores) -->
                @can('ver salones')
                    <x-nav-link :href="route('salones.index')" :active="request()->routeIs('salones.*')">
                        <x-icon name="salones" />
                        <span>{{ __('Salones') }}</span>
                    </x-nav-link>
                @endcan

                <!-- Armarios (visible para Administradores y Asistentes) -->
                @can('ver armarios')
                    <x-nav-link :href="route('armarios.index')" :active="request()->routeIs('armarios.*')">
                        <x-icon name="armario" />
                        <span>{{ __('Armarios') }}</span>
                    </x-nav-link>
                @endcan

                <!-- Reservas (visible para Profesores y Asistentes) -->
                @can('ver reservas')
                    <x-nav-link :href="route('reservas.index')" :active="request()->routeIs('reservas.*')">
                        <x-icon name="reservas" />
                        <span>{{ __('Reservas') }}</span>
                    </x-nav-link>
                @endcan

            </nav>
        </div>

        <!-- Perfil de Usuario y Cerrar Sesión -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <!-- Icono de Usuario y Nombre -->
            <a href="{{ route('profile.edit') }}" class="flex items-center text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-gray-100">
                <x-icon name="user" />
                <span class="ml-2">{{ Auth::user()->nombre }}</span>
            </a>
        </div>

        <div class="p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left text-red-500 hover:text-red-700">
                    {{ __('Cerrar Sesión') }}
                </button>
            </form>
        </div>
    </div>
</nav>

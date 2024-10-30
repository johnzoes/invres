<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')




            
            <div class="relative">
    <button id="notificationButton" class="relative">
        <i class="fa fa-bell text-gray-800 dark:text-gray-200"></i>
        @if(isset($notificaciones) && $notificaciones->count() > 0)
            <span class="absolute top-0 right-0 rounded-full bg-red-500 px-2 text-white text-xs">
                {{ $notificaciones->count() }}
            </span>
        @endif
    </button>

    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-lg overflow-hidden">
        <ul>
            @forelse($notificaciones as $notificacion)
                <li class="px-4 py-2 border-b hover:bg-gray-100">
                    {{ $notificacion->mensaje }}
                    <a href="{{ route('reservas.show', $notificacion->id_reserva) }}" class="text-blue-500">Ver reserva</a>
                </li>
            @empty
                <li class="px-4 py-2 text-gray-500">No tienes notificaciones nuevas</li>
            @endforelse
        </ul>
    </div>
</div>






            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- jQuery and Select2 -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

        <!-- Script para mostrar el menú desplegable de notificaciones -->
        <script>
            document.getElementById('notificationButton').addEventListener('click', function() {
                document.getElementById('notificationDropdown').classList.toggle('hidden');
            });

            document.addEventListener('click', function(event) {
                const notificationDropdown = document.getElementById('notificationDropdown');
                const notificationButton = document.getElementById('notificationButton');

                if (!notificationButton.contains(event.target) && !notificationDropdown.contains(event.target)) {
                    notificationDropdown.classList.add('hidden');
                }
            });
        </script>

        <!-- Additional Scripts -->
        @stack('scripts')

    </body>
</html>

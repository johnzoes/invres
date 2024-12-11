<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- jQuery primero -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Luego otros scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3.0.0/notyf.min.js"></script>

    <!-- Estilos -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/notyf@3.0.0/notyf.min.css" rel="stylesheet">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased" x-data>
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Sidebar -->
        @include('layouts.navigation')





        <!-- Contenido Principal -->
        <div class="flex-1 flex flex-col md:ml-64 ml-0 transition-all duration-300">
            <!-- Espaciado para la barra superior móvil -->
            <div class="h-16 md:h-0 block md:hidden"></div>

            <main class="flex-1 p-4 md:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- User Data -->
    <div id="user-data" data-user-id="{{ Auth::id() }}" class="hidden"></div>




    


    @stack('scripts')





</script>
</body>
</html>
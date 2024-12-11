<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">
    <div class="min-h-screen bg-gray-900 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Aquí puedes agregar tu logo personalizado si lo deseas -->
            
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-white">
                Bienvenido de nuevo
            </h2>
            <p class="mt-2 text-center text-sm text-gray-400">
                Inicia sesión para continuar
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-gray-800/80 backdrop-blur-lg py-8 px-4 shadow-xl rounded-2xl border border-gray-700/50 sm:px-10">
                <form class="space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div>
                        <label for="nombre_usuario" class="block text-sm font-medium text-gray-300">
                            Nombre de Usuario
                        </label>
                        <div class="mt-1 relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" name="nombre_usuario" id="nombre_usuario" required autofocus
                                class="bg-gray-700/50 border border-gray-600/50 text-gray-200 text-sm rounded-xl block w-full pl-10 p-2.5 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 focus:outline-none transition-colors duration-200"
                                placeholder="Ingresa tu usuario">
                        </div>
                        @error('nombre_usuario')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300">
                            Contraseña
                        </label>
                        <div class="mt-1 relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" name="password" id="password" required
                                class="bg-gray-700/50 border border-gray-600/50 text-gray-200 text-sm rounded-xl block w-full pl-10 p-2.5 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 focus:outline-none transition-colors duration-200"
                                placeholder="Ingresa tu contraseña">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" 
                            class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
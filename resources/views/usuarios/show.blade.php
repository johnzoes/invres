<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 shadow-xl">
                <!-- Header con breadcrumb -->
                <div class="border-b border-gray-700/50 p-6">
                    <div class="flex items-center text-sm text-gray-400 mb-4">
                        <a href="{{ route('usuarios.index') }}" class="hover:text-white transition-colors">Usuarios</a>
                        <svg class="h-4 w-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-white">Detalles del Usuario</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <h1 class="text-2xl font-light text-white">
                            {{ $usuario->nombre . ' ' . $usuario->apellidos }}
                        </h1>
                        <span class="px-3 py-1 bg-green-500/10 text-green-400 border border-green-500/20 rounded-xl text-sm">
                            {{ $usuario->roles->pluck('name')->first() }}
                        </span>
                    </div>
                </div>

                <!-- Contenido principal -->
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Información básica -->
                    <div class="space-y-6">
                        <div class="bg-gray-700/30 rounded-xl p-4 border border-gray-700/50">
                            <h2 class="text-lg font-medium text-white mb-4">Información Personal</h2>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">ID</span>
                                    <span class="text-gray-200">#{{ $usuario->id }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">Nombre Completo</span>
                                    <span class="text-gray-200">{{ $usuario->nombre . ' ' . $usuario->apellidos }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">Usuario</span>
                                    <span class="text-gray-200">{{ $usuario->nombre_usuario }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">Email</span>
                                    <span class="text-gray-200">{{ $usuario->email }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información específica del rol -->
                    <div class="space-y-6">
                        @if($usuario->roles->pluck('name')->first() === 'asistente' && $usuario->asistente)
                            <div class="bg-gray-700/30 rounded-xl p-4 border border-gray-700/50">
                                <h2 class="text-lg font-medium text-white mb-4">Información del Asistente</h2>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-400">Turno</span>
                                        <span class="px-3 py-1 bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-xl text-sm">
                                            {{ ucfirst($usuario->asistente->turno) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400 block mb-2">Salones Asignados</span>
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($usuario->asistente->salones as $salon)
                                                <span class="px-3 py-1.5 bg-gray-700/50 text-gray-300 rounded-lg text-sm">
                                                    {{ $salon->nombre_salon }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($usuario->roles->pluck('name')->first() === 'profesor' && $usuario->profesor)
                            <div class="bg-gray-700/30 rounded-xl p-4 border border-gray-700/50">
                                <h2 class="text-lg font-medium text-white mb-4">Información del Profesor</h2>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">Reservas Realizadas</span>
                                    <span class="px-3 py-1 bg-purple-500/10 text-purple-400 border border-purple-500/20 rounded-xl text-sm">
                                        {{ $usuario->profesor->reservas->count() }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer con acciones -->
                <div class="border-t border-gray-700/50 p-6 flex justify-between items-center">
                    <a href="{{ route('usuarios.index') }}" 
                       class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Volver a la Lista
                    </a>
                    
                    <div class="flex gap-3">
                        <a href="{{ route('usuarios.edit', $usuario->id) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 rounded-xl hover:bg-yellow-500/20 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Editar Usuario
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
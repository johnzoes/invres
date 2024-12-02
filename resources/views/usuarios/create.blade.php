<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 shadow-xl p-8">
                <h1 class="text-2xl font-light text-white mb-8">Crear Usuario</h1>

                @if($errors->any())
                    <div class="mb-6 relative">
                        <div class="absolute inset-0 bg-red-500/20 blur-xl rounded-2xl"></div>
                        <div class="relative bg-gray-800/40 border border-red-500/50 backdrop-blur rounded-xl p-4">
                            <ul class="text-red-400 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('usuarios.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <!-- Información básica -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nombre_usuario" class="block text-sm font-medium text-gray-300">Nombre de Usuario</label>
                                <input type="text" 
                                       name="nombre_usuario" 
                                       id="nombre_usuario" 
                                       value="{{ old('nombre_usuario') }}"
                                       class="mt-2 block w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-300">Correo Electrónico</label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email') }}"
                                       class="mt-2 block w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all">
                            </div>

                            <div>
                                <label for="nombre" class="block text-sm font-medium text-gray-300">Nombre</label>
                                <input type="text" 
                                       name="nombre" 
                                       id="nombre" 
                                       value="{{ old('nombre') }}"
                                       class="mt-2 block w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all">
                            </div>

                            <div>
                                <label for="apellidos" class="block text-sm font-medium text-gray-300">Apellidos</label>
                                <input type="text" 
                                       name="apellidos" 
                                       id="apellidos" 
                                       value="{{ old('apellidos') }}"
                                       class="mt-2 block w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all">
                            </div>
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-300">Contraseña</label>
                            <input type="password" 
                                   name="password" 
                                   id="password"
                                   class="mt-2 block w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all">
                        </div>

                        <!-- Rol -->
                        <div>
                            <label for="rol" class="block text-sm font-medium text-gray-300">Rol</label>
                            <select name="rol" 
                                    id="rol"
                                    class="mt-2 block w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all">
                                <option value="">Selecciona un rol</option>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->name }}" {{ old('rol') == $rol->name ? 'selected' : '' }}>
                                        {{ ucfirst($rol->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Campos para Asistentes -->
                        <div id="asistente-fields" style="display: none;" class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-3">Salones Asignados</label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                    @foreach($salones as $salon)
                                        <label class="relative flex items-start">
                                            <input type="checkbox" 
                                                   name="salones[]" 
                                                   value="{{ $salon->id }}" 
                                                   {{ in_array($salon->id, old('salones', [])) ? 'checked' : '' }}
                                                   class="rounded border-gray-600 bg-gray-700/50 text-blue-500 focus:ring-blue-500 focus:ring-offset-gray-900">
                                            <span class="ml-2 text-sm text-gray-300">{{ $salon->nombre_salon }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('salones.create') }}" 
                                       class="inline-flex items-center text-blue-400 hover:text-blue-300 text-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Agregar nuevo salón
                                    </a>
                                </div>
                            </div>

                            <div>
                                <label for="turno" class="block text-sm font-medium text-gray-300">Turno</label>
                                <select name="turno" 
                                        id="turno"
                                        class="mt-2 block w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all">
                                    <option value="">Selecciona un turno</option>
                                    <option value="mañana" {{ old('turno') == 'mañana' ? 'selected' : '' }}>Mañana</option>
                                    <option value="noche" {{ old('turno') == 'noche' ? 'selected' : '' }}>Noche</option>
                                </select>
                            </div>
                        </div>

                        <!-- Botón de envío -->
                        <div class="flex justify-end pt-6">
                            <button type="submit" 
                                    class="bg-blue-500/90 hover:bg-blue-500 text-white font-medium px-8 py-2.5 rounded-xl transition-all duration-200 shadow-lg shadow-blue-500/20">
                                Crear Usuario
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('rol').addEventListener('change', function() {
            const asistenteFields = document.getElementById('asistente-fields');
            asistenteFields.style.display = this.value === 'asistente' ? 'block' : 'none';
        });
    </script>
</x-app-layout>
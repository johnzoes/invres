<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 shadow-xl p-8">
                <!-- Header con navegación -->
                <div class="flex items-center justify-between mb-8">
                    <h1 class="text-2xl font-light text-white">
                        {{ isset($salon) ? 'Editar Salón' : 'Crear Nuevo Salón' }}
                    </h1>
                    <a href="{{ route('salones.index') }}" 
                       class="text-gray-400 hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </div>

                <!-- Mensajes de error -->
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

                <!-- Formulario -->
                <form action="{{ isset($salon) ? route('salones.update', $salon->id) : route('salones.store') }}" 
                      method="POST" 
                      class="space-y-6">
                    @csrf
                    @if(isset($salon))
                        @method('PUT')
                    @endif

                    <div>
                        <label for="nombre_salon" 
                               class="block text-sm font-medium text-gray-300 mb-2">
                            Nombre del Salón
                        </label>
                        <input type="text" 
                               name="nombre_salon" 
                               id="nombre_salon" 
                               value="{{ old('nombre_salon', $salon->nombre_salon ?? '') }}"
                               class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all"
                               placeholder="Ingrese el nombre del salón"
                               required>
                    </div>

                    <!-- Acciones -->
                    <div class="flex items-center justify-end gap-4 pt-4">
                        <a href="{{ route('salones.index') }}" 
                           class="px-6 py-2.5 border border-gray-600 text-gray-300 rounded-xl hover:bg-gray-700 transition-all duration-200">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="bg-blue-500/90 hover:bg-blue-500 text-white font-medium px-6 py-2.5 rounded-xl transition-all duration-200 shadow-lg shadow-blue-500/20">
                            {{ isset($salon) ? 'Actualizar' : 'Crear' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 shadow-xl p-6">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-2xl font-light text-white">
                        {{ isset($categoria) ? 'Editar Categoría' : 'Nueva Categoría' }}
                    </h1>
                    
                    <a href="{{ route('categorias.index') }}" 
                       class="text-gray-400 hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </div>

                <form action="{{ isset($categoria) ? route('categorias.update', $categoria->id) : route('categorias.store') }}" 
                      method="POST"
                      enctype="multipart/form-data"
                      class="space-y-6">
                    @csrf
                    @if(isset($categoria))
                        @method('PUT')
                    @endif

                    <div class="space-y-2">
                        <label for="nombre_categoria" class="block text-sm font-medium text-gray-300">
                            Nombre de la Categoría
                        </label>
                        <input type="text" 
                               name="nombre_categoria" 
                               id="nombre_categoria" 
                               value="{{ old('nombre_categoria', $categoria->nombre_categoria ?? '') }}"
                               class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-2.5 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all"
                               placeholder="Ingrese el nombre de la categoría"
                               required>
                        @error('nombre_categoria')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="imagen" class="block text-sm font-medium text-gray-300">
                            Imagen de la Categoría
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="imagen" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-600 border-dashed rounded-xl cursor-pointer bg-gray-700/30 hover:bg-gray-700/50 transition-all">
                                @if(isset($categoria) && $categoria->imagen)
                                    <img src="{{ Storage::url($categoria->imagen) }}" 
                                         alt="Preview" 
                                         class="h-full object-cover rounded-lg" 
                                         id="preview">
                                @else
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6" id="placeholder">
                                        <svg class="w-8 h-8 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-400">
                                            <span class="font-semibold">Click para subir</span> o arrastrar y soltar
                                        </p>
                                        <p class="text-xs text-gray-400">PNG, JPG o GIF (MAX. 2MB)</p>
                                    </div>
                                    <img src="" alt="Preview" class="hidden h-full object-cover rounded-lg" id="preview">
                                @endif
                                <input id="imagen" 
                                       type="file" 
                                       name="imagen" 
                                       class="hidden" 
                                       accept="image/*" />
                            </label>
                        </div>
                        @error('imagen')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-4 pt-4">
                        <a href="{{ route('categorias.index') }}" 
                           class="px-6 py-2.5 bg-gray-600 text-white rounded-xl hover:bg-gray-700 transition-all duration-200">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-all duration-200">
                            {{ isset($categoria) ? 'Actualizar' : 'Crear' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const imagen = document.getElementById('imagen');
        const preview = document.getElementById('preview');
        const placeholder = document.getElementById('placeholder');

        imagen.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>
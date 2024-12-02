<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 shadow-xl p-4 sm:p-6">
                <!-- Header con filtros y botón -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                    <div class="w-full sm:w-auto">
                        <form action="{{ route('categorias.index') }}" method="GET" class="flex items-center gap-2">
                            <select name="filter" 
                                    class="flex-1 sm:flex-none bg-gray-700/50 text-white border border-gray-600 rounded-xl px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all">
                                <option value="">Todas las categorías</option>
                                <option value="mis_items" @if(request('filter') === 'mis_items') selected @endif>
                                    Mis categorías
                                </option>
                            </select>
                            <button type="submit" 
                                    class="bg-blue-500/90 hover:bg-blue-500 text-white font-medium px-6 py-2.5 rounded-xl transition-all duration-200 shadow-lg shadow-blue-500/20">
                                Filtrar
                            </button>
                        </form>
                    </div>

                    <div class="flex items-center justify-between w-full sm:w-auto gap-4">
                        <h1 class="text-2xl font-light text-white">Categorías</h1>
                        <a href="{{ route('categorias.create') }}" 
                           class="inline-flex items-center gap-2 bg-green-500/90 hover:bg-green-500 text-white font-medium px-4 sm:px-6 py-2.5 rounded-xl transition-all duration-200 shadow-lg shadow-green-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="hidden sm:inline">Nueva Categoría</span>
                        </a>
                    </div>
                </div>

                <!-- Notificaciones -->
                @if(session('success'))
                    <div class="mb-6 relative">
                        <div class="absolute inset-0 bg-green-500/20 blur-xl rounded-2xl"></div>
                        <div class="relative bg-gray-800/40 border border-green-500/50 backdrop-blur rounded-xl p-4 flex items-center gap-3">
                            <div class="bg-green-500/20 rounded-full p-2">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-green-400">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Grid de Categorías -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($categorias as $categoria)
                        <div class="group bg-gray-900/50 rounded-xl border border-gray-700/50 backdrop-blur-sm overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <a href="{{ route('items.index', ['categoria' => $categoria->id]) }}" class="block relative">
                                @if($categoria->imagen)
                                    <img src="{{ Storage::url($categoria->imagen) }}" 
                                         alt="{{ $categoria->nombre_categoria }}" 
                                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-48 bg-gray-800 flex items-center justify-center group-hover:bg-gray-750 transition-colors">
                                        <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Badge Items -->
                                <span class="absolute top-3 right-3 bg-blue-500/90 text-white px-3 py-1 rounded-full text-sm shadow-lg">
                                    {{ $categoria->items_count ?? 0 }} items
                                </span>
                            </a>
                            
                            <div class="p-4">
                                <div class="mb-4">
                                    <h3 class="text-lg font-medium text-white group-hover:text-blue-400 transition-colors">
                                        {{ $categoria->nombre_categoria }}
                                    </h3>
                                    <p class="text-sm text-gray-400">ID: {{ $categoria->id }}</p>
                                </div>

                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('categorias.edit', $categoria->id) }}" 
                                       class="p-2 text-blue-400 hover:bg-blue-500/10 rounded-full transition-all"
                                       onclick="event.stopPropagation()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="event.stopPropagation(); return confirm('¿Estás seguro de eliminar esta categoría?')"
                                                class="p-2 text-red-400 hover:bg-red-500/10 rounded-full transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($categorias->isEmpty())
                    <div class="text-center text-gray-400 py-12">
                        <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-xl font-light">
                            @if(request('filter') === 'mis_items')
                                No tienes categorías asignadas.
                            @else
                                No hay categorías registradas.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
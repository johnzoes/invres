<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                    {{ __('Gestión de Ítems') }}
                </h2>
                @can('crear items')
                    <a href="{{ route('items.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        Agregar Nuevo Ítem
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tabs de navegación -->
            <div class="mb-6 border-b border-gray-700">
                <div class="flex space-x-8">
                    <a href="{{ route('items.index', ['view' => 'all']) }}" 
                       class="pb-4 px-1 border-b-2 {{ !$showMyItems ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300' }} transition-colors duration-200">
                        Todos los Items
                    </a>
                    <a href="{{ route('items.index', ['view' => 'my']) }}" 
                       class="pb-4 px-1 border-b-2 {{ $showMyItems ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300' }} transition-colors duration-200">
                        Mis Items
                    </a>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
                <form action="{{ route('items.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                    <input type="hidden" name="view" value="{{ request('view', 'all') }}">
                    
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-400 mb-2">Categoría</label>
                        <select name="categoria" class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ $categoriaActual && $categoriaActual->id == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre_categoria }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        Filtrar
                    </button>
                </form>
            </div>

            <!-- Lista de Items -->
            <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Imagen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Código BCI</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Descripción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Categoría</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Ubicación</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @forelse($items as $item)
                                <tr class="hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->imagen)
                                            <img src="{{ asset('storage/' . $item->imagen) }}" alt="Imagen del ítem" class="h-12 w-12 rounded-lg object-cover">
                                        @else
                                            <div class="h-12 w-12 rounded-lg bg-gray-700 flex items-center justify-center">
                                                <span class="text-gray-400">N/A</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-300">{{ $item->codigo_bci }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-300">{{ $item->descripcion }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-300">{{ $item->categoria->nombre_categoria }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-300">
                                        {{ $item->armario->salon->nombre_salon }} - {{ $item->armario->nombre_armario }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium space-x-2">
                                        <a href="{{ route('items.show', $item->id) }}" class="text-blue-400 hover:text-blue-300">Ver</a>
                                        @can('editar items')
                                            <a href="{{ route('items.edit', $item->id) }}" class="text-yellow-400 hover:text-yellow-300">Editar</a>
                                        @endcan
                                        @can('eliminar items')
                                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('¿Estás seguro?')">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                                        No se encontraron items
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
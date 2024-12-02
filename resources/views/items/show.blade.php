<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 shadow-xl">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-8">
                        <h1 class="text-2xl font-light text-white">Detalles del Ítem</h1>
                        <a href="{{ route('items.index') }}" 
                           class="text-gray-400 hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div class="space-y-1">
                                <p class="text-sm text-gray-400">Descripción</p>
                                <p class="text-white">{{ $item->descripcion }}</p>
                            </div>

                            <div class="space-y-1">
                                <p class="text-sm text-gray-400">Código BCI</p>
                                <p class="text-white">{{ $item->codigo_bci }}</p>
                            </div>

                            <div class="space-y-1">
                                <p class="text-sm text-gray-400">Cantidad</p>
                                <p class="text-white">{{ $item->cantidad }}</p>
                            </div>

                            <div class="space-y-1">
                                <p class="text-sm text-gray-400">Tipo</p>
                                <p class="text-white">{{ ucfirst($item->tipo) }}</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-1">
                                <p class="text-sm text-gray-400">Marca</p>
                                <p class="text-white">{{ $item->marca ?? 'No especificada' }}</p>
                            </div>

                            <div class="space-y-1">
                                <p class="text-sm text-gray-400">Modelo</p>
                                <p class="text-white">{{ $item->modelo ?? 'No especificado' }}</p>
                            </div>

                            <div class="space-y-1">
                                <p class="text-sm text-gray-400">Categoría</p>
                                <p class="text-white">{{ $item->categoria->nombre_categoria }}</p>
                            </div>

                            <div class="space-y-1">
                                <p class="text-sm text-gray-400">Número de Inventario</p>
                                <p class="text-white">{{ $item->nro_inventariado }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-700">
                        <div class="space-y-1">
                            <p class="text-sm text-gray-400">Ubicación</p>
                            <div class="flex items-center gap-2 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $item->armario->nombre_armario }} - {{ $item->armario->salon->nombre_salon }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
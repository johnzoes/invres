<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl border border-gray-700/50 shadow-xl p-6">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-2xl font-light text-white">Lista de Salones</h1>
                    
                    <a href="{{ route('salones.create') }}" 
                       class="inline-flex items-center gap-2 bg-green-500/90 hover:bg-green-500 text-white font-medium px-6 py-2.5 rounded-xl transition-all duration-200 shadow-lg shadow-green-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Nuevo Salón
                    </a>
                </div>

                @if (session('success'))
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

                @if (session('error'))
                    <div class="mb-6 relative">
                        <div class="absolute inset-0 bg-red-500/20 blur-xl rounded-2xl"></div>
                        <div class="relative bg-gray-800/40 border border-red-500/50 backdrop-blur rounded-xl p-4 flex items-center gap-3">
                            <div class="bg-red-500/20 rounded-full p-2">
                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <p class="text-red-400">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Vista Desktop/Tablet -->
                <div class="hidden md:block overflow-x-auto rounded-xl">
                    <table class="w-full">
                        <thead class="bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">ID</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Nombre</th>
                                <th class="px-6 py-4 text-right text-sm font-medium text-gray-400">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50">
                            @forelse($salones as $salon)
                                <tr class="hover:bg-gray-700/30 transition-all duration-200">
                                    <td class="px-6 py-4 text-sm text-gray-300">{{ $salon->id }}</td>
                                    <td class="px-6 py-4 text-sm text-white font-medium">{{ $salon->nombre_salon }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('salones.edit', $salon->id) }}" 
                                               class="p-2 text-blue-400 hover:bg-blue-500/10 rounded-full transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            </a>
                                            <button onclick="if(confirm('¿Estás seguro de eliminar este salón?')) document.getElementById('delete-form-{{ $salon->id }}').submit();"
                                                    class="p-2 text-red-400 hover:bg-red-500/10 rounded-full transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <form id="delete-form-{{ $salon->id }}" action="{{ route('salones.destroy', $salon->id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-400">
                                        No hay salones registrados
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Vista Móvil -->
                <div class="md:hidden space-y-4">
                    @forelse($salones as $salon)
                        <div class="bg-gray-700/30 rounded-xl p-4 border border-gray-700/50">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-white font-medium">{{ $salon->nombre_salon }}</h3>
                                    <p class="text-gray-400 text-sm mt-1">ID: {{ $salon->id }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('salones.edit', $salon->id) }}" 
                                       class="p-2 text-blue-400 hover:bg-blue-500/10 rounded-full transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </a>
                                    <button onclick="if(confirm('¿Estás seguro de eliminar este salón?')) document.getElementById('delete-form-mobile-{{ $salon->id }}').submit();"
                                            class="p-2 text-red-400 hover:bg-red-500/10 rounded-full transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <form id="delete-form-mobile-{{ $salon->id }}" action="{{ route('salones.destroy', $salon->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    @empty
                        <div class="text-center text-gray-400 py-8">
                            No hay salones registrados
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
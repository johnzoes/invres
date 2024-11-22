<x-app-layout>
    <div class="flex flex-col py-12">
        <div class="w-full max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                
                    <x-search-input>
            @section('input-events')
                oninput="handleSearch(event)"
            @endsection
        </x-search-input>





                @can('crear items')
                    <!-- Botón para agregar un nuevo ítem -->
                    <div class="flex justify-end mb-6">
                        <a href="{{ route('items.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-5 rounded-lg">
                            Agregar Nuevo Ítem
                        </a>
                    </div>
                @endcan

                <h1 class="text-2xl font-semibold text-white mb-6">Listado de Ítems</h1>

                <!-- Tabla de Ítems -->
                <div id="itemsTable">
                    @include('items.index-table', ['items' => $items])
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search');
            const itemsTable = document.getElementById('itemsTable');

            // Realizar búsqueda en tiempo real
            searchInput.addEventListener('input', function () {
                const query = this.value;

                fetch(`{{ route('items.search') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        itemsTable.innerHTML = data.html; // Actualizar la tabla con los resultados
                    })
                    .catch(error => console.error('Error en la búsqueda:', error));
            });
        });
    </script>
</x-app-layout>

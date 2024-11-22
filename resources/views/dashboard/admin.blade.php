<x-app-layout>
    <div class="grid grid-cols-3 gap-6 px-6 py-6">
        <!-- Historial de Reservas -->
        <div class="col-span-2 bg-gray-800 p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold text-white mb-4 text-center">Historial de Reservas</h2>
            <x-tabla-reservas :reservas="$reservas" />
        </div>

        <!-- Gráficos (Categorías y Profesores) -->
        <div class="col-span-1 flex flex-col space-y-6">
            <!-- Gráfico de Categorías -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold text-white mb-4 text-center">Reservas por Categoría</h2>
                <x-grafico-categorias :categorias="$categorias" />
            </div>

            <!-- Gráfico de Profesores -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold text-white mb-4 text-center">Top 10 Profesores</h2>
                <x-grafico-profesores :profesoresTop="$profesoresTop" />
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</x-app-layout>

<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
    <canvas id="categoriasChart" class="w-full h-64"></canvas>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Verificar si los datos están correctamente cargados
            const categorias = @json($categorias->pluck('nombre_categoria')->toArray());
            const reservas = @json($categorias->pluck('total_reservas')->toArray());

            // Comprobar si existen datos para el gráfico
            if (categorias.length > 0 && reservas.length > 0) {
                const categoriasCtx = document.getElementById('categoriasChart').getContext('2d');
                new Chart(categoriasCtx, {
                    type: 'doughnut',
                    data: {
                        labels: categorias,
                        datasets: [{
                            label: 'Reservas',
                            data: reservas,
                            backgroundColor: categorias.map((_, i) => `hsl(${i * 36}, 70%, 50%)`),
                            borderColor: categorias.map((_, i) => `hsl(${i * 36}, 70%, 40%)`),
                            borderWidth: 1,
                        }],
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true, position: 'bottom' },
                        },
                    },
                });
            } else {
                console.error('No hay datos disponibles para el gráfico de categorías.');
            }
        });
    </script>
</div>

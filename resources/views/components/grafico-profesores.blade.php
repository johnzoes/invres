<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
    <canvas id="profesoresChart" class="w-full h-64"></canvas>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const profesores = @json($profesoresTop->pluck('usuario.nombre'));
            const reservasProfesores = @json($profesoresTop->pluck('reservas_count')->toArray());

            if (profesores.length > 0 && reservasProfesores.length > 0) {
                const profesoresCtx = document.getElementById('profesoresChart').getContext('2d');
                new Chart(profesoresCtx, {
                    type: 'bar',
                    data: {
                        labels: profesores,
                        datasets: [{
                            label: 'Reservas',
                            data: reservasProfesores,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                        }],
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                        },
                        scales: {
                            x: { ticks: { color: '#ffffff' }, grid: { display: false } },
                            y: { ticks: { color: '#ffffff' }, grid: { color: '#374151' } },
                        },
                    },
                });
            } else {
                console.error('No hay datos disponibles para el gráfico de profesores.');
            }
        });
    </script>
</div>

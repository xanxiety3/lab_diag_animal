{{-- dashboard_resultados.blade.php (solo la parte de gráficos y script) --}}
<style>
    /* (puedes conservar tu CSS existente; incluyo estilos mínimos por si acaso) */
    .dashboard-container {
        padding: 2rem;
        background: #f7f9fc;
        min-height: 100vh;
        font-family: 'Poppins', sans-serif;
    }

    .charts-grid {
        display: grid;
        gap: 1.5rem;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    }

    .chart-card {
        background: #fff;
        border-radius: 10px;
        padding: 1rem;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
    }

    canvas {
        width: 100% !important;
        height: 300px !important;
    }

    .btn-dashboard {
        background: #27ae60;
        color: #fff;
        padding: 8px 12px;
        border-radius: 8px;
        text-decoration: none;
    }
</style>

<div class="dashboard-container">
    <a href="{{ route('dashboard') }}" class="btn-dashboard">⬅️ Volver al Dashboard</a>
    <h1>📊 Panel de Resultados del Laboratorio</h1>

    <div class="charts-grid">
        <div class="chart-card">
            <h3>📍 Muestras por tipo de ubicación</h3>
            <canvas id="chartUbicacion"></canvas>
        </div>

        <div class="chart-card">
            <h3>🧫 Tipos de muestra más analizados</h3>
            <canvas id="chartTipos"></canvas>
        </div>


        <div class="chart-card">
            <h3>📈 Tendencia de resultados registrados</h3>
            <canvas id="chartResultadosTendencia"></canvas>
        </div>

        <div class="chart-card">
            <h3>🧪 Resultados por técnica</h3>
            <canvas id="chartResultadosPorTecnica"></canvas>
        </div>

        <div class="chart-card">
            <h3>🚫 Aceptadas vs Rechazadas</h3>
            <canvas id="chartRechazadas"></canvas>
        </div>
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // === Datos del backend (asegúrate de que el controlador pase estas variables) ===
    const rawTipos = @json($muestrasPorTipo ?? []);
    const rawTendencia = @json($tendencia ?? []);
    const rawTendenciaResultados = @json($tendenciaResultados ?? []);
    const rawResultadosPorTecnica = @json($resultadosPorTecnica ?? []);
    const rawRechazadas = @json($rechazadas ?? ['aceptadas' => 0, 'rechazadas' => 0]);

    // Helper seguros
    const toNum = v => {
        const n = Number(v);
        return Number.isNaN(n) ? 0 : n;
    };

    // === 1) Ubicaciones (bar vertical) ===
    // Datos desde el backend
    const dataUbicacion = @json($porUbicacion ?? []);

    new Chart(document.getElementById('chartUbicacion'), {
        type: 'bar',
        data: {
            labels: dataUbicacion.map(item => item.ubicacion), // <--- usa "ubicacion"
            datasets: [{
                label: 'Cantidad de muestras',
                data: dataUbicacion.map(item => item.total), // <--- usa "total"
                backgroundColor: '#1abc9c'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // === 2) Tipos de muestra (doughnut) ===
    new Chart(document.getElementById('chartTipos'), {
        type: 'doughnut',
        data: {
            labels: rawTipos.map(r => r.tipo ?? '—'),
            datasets: [{
                data: rawTipos.map(r => toNum(r.total)),
                backgroundColor: ['#16a085', '#f39c12', '#9b59b6', '#2ecc71', '#e74c3c', '#2980b9']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // TENDENCIAS DE RESULTADOS 

    const dataResultadosTendencia = @json($resultadosTendencia ?? []);
    const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Sept', 'Oct', 'Nov',
        'Dic'
    ];

    new Chart(document.getElementById('chartResultadosTendencia'), {
        type: 'line',
        data: {
            labels: dataResultadosTendencia.map(item => meses[item.mes - 1]),
            datasets: [{
                label: 'Resultados registrados',
                data: dataResultadosTendencia.map(item => item.total),
                borderColor: '#8e44ad',
                fill: true,
                backgroundColor: 'rgba(142, 68, 173, 0.2)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });





    // === 5) Resultados por técnica (bar vertical) ===
    new Chart(document.getElementById('chartResultadosPorTecnica'), {
        type: 'bar',
        data: {
            labels: rawResultadosPorTecnica.map(r => r.tecnica ?? '—'),
            datasets: [{
                label: 'Resultados',
                data: rawResultadosPorTecnica.map(r => toNum(r.total)),
                backgroundColor: '#f39c12'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // === 6) Aceptadas vs Rechazadas (HORIZONTAL BAR para claridad) ===
    const aceptadas = toNum(rawRechazadas.aceptadas ?? rawRechazadas.accepted ?? 0);
    const rechazadas = toNum(rawRechazadas.rechazadas ?? rawRechazadas.rejected ?? 0);

    new Chart(document.getElementById('chartRechazadas'), {
        type: 'bar',
        data: {
            labels: ['Aceptadas', 'Rechazadas'],
            datasets: [{
                label: 'Muestras',
                data: [aceptadas, rechazadas],
                backgroundColor: ['#2ecc71', '#e74c3c']
            }]
        },
        options: {
            indexAxis: 'y', // <-- hace la barra horizontal
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>

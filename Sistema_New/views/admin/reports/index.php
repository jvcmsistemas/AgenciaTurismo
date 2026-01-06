<?php
// Sistema_New/views/admin/reports/index.php
include BASE_PATH . '/views/layouts/header.php';
?>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="row mb-4 animate-fade-in">
    <div class="col-md-6">
        <h2 class="mb-0">🏢 Inteligencia de Negocios (BI)</h2>
        <p class="text-muted">Análisis estratégico de agencias y mercado</p>
    </div>
    <div class="col-md-6 text-end">
        <div class="btn-group shadow-sm">
            <button class="btn btn-white border-end"><i class="fas fa-calendar-alt me-2"></i>Ene 2026</button>
            <button class="btn btn-success">
                <i class="fas fa-file-excel me-2"></i>Reporte Ejecutivo
            </button>
        </div>
    </div>
</div>

<!-- KPI Summary Cards -->
<div class="row g-3 mb-4 animate-fade-in-up">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm glass-card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted small fw-bold mb-1">RECAUDACIÓN MES</p>
                        <h3 class="mb-0 fw-bold">S/ <?= number_format($kpis['recaudacion_mes'] ?? 0, 2) ?></h3>
                    </div>
                    <div class="icon-shape bg-soft-success rounded-circle p-3">
                        <i class="fas fa-money-bill-wave text-success"></i>
                    </div>
                </div>
                <div class="mt-2 small"><span class="text-success"><i class="fas fa-arrow-up me-1"></i>12%</span> vs mes anterior</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm glass-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted small fw-bold mb-1">AGENCIAS ACTIVAS</p>
                        <h3 class="mb-0 fw-bold"><?= $kpis['agencias_activas'] ?? 0 ?></h3>
                    </div>
                    <div class="icon-shape bg-soft-primary rounded-circle p-3">
                        <i class="fas fa-building text-primary"></i>
                    </div>
                </div>
                <div class="mt-2 small"><span class="text-muted">En todo el sistema</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm glass-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted small fw-bold mb-1">PROX. VIAJES</p>
                        <h3 class="mb-0 fw-bold"><?= $kpis['proximas_salidas'] ?? 0 ?></h3>
                    </div>
                    <div class="icon-shape bg-soft-warning rounded-circle p-3">
                        <i class="fas fa-route text-warning"></i>
                    </div>
                </div>
                <div class="mt-2 small"><span class="text-warning">7 días siguientes</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm glass-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted small fw-bold mb-1">TICKETS ABIERTOS</p>
                        <h3 class="mb-0 fw-bold text-danger"><?= $kpis['tickets_abiertos'] ?? 0 ?></h3>
                    </div>
                    <div class="icon-shape bg-soft-danger rounded-circle p-3">
                        <i class="fas fa-exclamation-circle text-danger"></i>
                    </div>
                </div>
                <div class="mt-2 small"><span class="text-danger fw-bold">Atención requerida</span></div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Chart: Revenue Pipeline -->
    <div class="col-lg-8 animate-fade-in-up">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between">
                <h5 class="fw-bold mb-0">Tendencia de Ingresos Global</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">Últimos 6 meses</button>
                </div>
            </div>
            <div class="card-body">
                <div id="revenueChart"></div>
            </div>
        </div>
    </div>

    <!-- Chart: Agency Risk Radar -->
    <div class="col-lg-4 animate-fade-in-up">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Riesgo de Agencias (Deuda)</h5>
            </div>
            <div class="card-body">
                <div id="riskChart"></div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Agency Performance Table -->
    <div class="col-lg-12 animate-fade-in-up">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Rendimiento Financiero por Agencia</h5>
                <input type="text" class="form-control form-control-sm w-auto" placeholder="Buscar agencia...">
            </div>
            <div class="table-responsive p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Agencia</th>
                            <th>Reservas</th>
                            <th>Ventas Totales</th>
                            <th>Cobrado</th>
                            <th>Saldo Pendiente</th>
                            <th>Riesgo</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($agencyPerformance as $agency): ?>
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold"><?= htmlspecialchars($agency['agencia']) ?></span>
                            </td>
                            <td><?= $agency['num_reservas'] ?? 0 ?></td>
                            <td>S/ <?= number_format((float)($agency['total_ventas'] ?? 0), 2) ?></td>
                            <td class="text-success fw-bold">S/ <?= number_format((float)($agency['total_cobrado'] ?? 0), 2) ?></td>
                            <td class="text-danger">S/ <?= number_format((float)($agency['deuda_total'] ?? 0), 2) ?></td>
                            <td>
                                <?php 
                                $ratio = $agency['ratio_deuda'] ?? 0;
                                $color = $ratio > 30 ? 'danger' : ($ratio > 10 ? 'warning' : 'success');
                                ?>
                                <div class="d-flex align-items-center">
                                    <div class="progress w-100 me-2" style="height: 6px;">
                                        <div class="progress-bar bg-<?= $color ?>" style="width: <?= $ratio ?>%"></div>
                                    </div>
                                    <span class="small text-muted"><?= round($ratio) ?>%</span>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-light"><i class="fas fa-eye"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Market Insights: Metodos de Pago -->
    <div class="col-md-6 animate-fade-in-up">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Demanda por Método de Pago</h5>
            </div>
            <div class="card-body">
                <div id="paymentsChart"></div>
            </div>
        </div>
    </div>

    <!-- Support Load / Service Quality -->
    <div class="col-md-6 animate-fade-in-up">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Carga de Soporte por Agencia</h5>
            </div>
            <div class="card-body">
                <div id="supportChart"></div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts de Inicialización de Gráficos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let charts = [];

    function getThemeMode() {
        return document.body.classList.contains('superadmin-light-theme') ? 'light' : 'dark';
    }

    function initCharts() {
        // Destruir instancias previas si existen
        charts.forEach(c => c.destroy());
        charts = [];

        const mode = getThemeMode();

        // 1. Chart Revenue Trends
        var revenueOptions = {
            series: [{
                name: 'Vendido',
                data: [<?= implode(',', array_reverse(array_column($incomeTrends, 'total_vendido'))) ?>]
            }, {
                name: 'Cobrado',
                data: [<?= implode(',', array_reverse(array_map(fn($t) => $t['total_vendido'] - $t['total_pendiente'], $incomeTrends))) ?>]
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            theme: { mode: mode },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            colors: ['#0d6efd', '#198754'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100, 100, 100]
                }
            },
            xaxis: {
                categories: [<?= "'" . implode("','", array_reverse(array_column($incomeTrends, 'periodo'))) . "'" ?>]
            },
            tooltip: {
                y: { formatter: function(val) { return "S/ " + val.toLocaleString() } }
            }
        };
        var revChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
        revChart.render();
        charts.push(revChart);

        // 2. Chart Agency Risk Radar
        var riskOptions = {
            series: [<?= implode(',', array_slice(array_column($agencyPerformance, 'deuda_total'), 0, 5)) ?>],
            chart: {
                type: 'donut',
                height: 300
            },
            theme: { mode: mode },
            labels: [<?= "'" . implode("','", array_slice(array_column($agencyPerformance, 'agencia'), 0, 5)) . "'" ?>],
            colors: ['#dc3545', '#fd7e14', '#ffc107', '#6c757d', '#adb5bd'],
            legend: { position: 'bottom' },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                            total: { show: true, label: 'Deuda Total', formatter: function(w) { return "S/ " + w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString() } }
                        }
                    }
                }
            }
        };
        var rChart = new ApexCharts(document.querySelector("#riskChart"), riskOptions);
        rChart.render();
        charts.push(rChart);

        // 3. Chart Payments
        var paymentOptions = {
            series: [{
                data: [<?= implode(',', array_column($marketInsights['pagos'], 'cantidad')) ?>]
            }],
            chart: {
                type: 'bar',
                height: 250,
                toolbar: { show: false }
            },
            theme: { mode: mode },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                    distributed: true
                }
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: [<?= "'" . implode("','", array_column($marketInsights['pagos'], 'metodo_pago')) . "'" ?>]
            },
            colors: ['#0d6efd', '#198754', '#ffc107', '#0dcaf0', '#6610f2']
        };
        var pChart = new ApexCharts(document.querySelector("#paymentsChart"), paymentOptions);
        pChart.render();
        charts.push(pChart);

        // 4. Support Heatmap / Bar
        var supportOptions = {
            series: [{
                name: 'Tickets Totales',
                data: [<?= implode(',', array_column($supportLoad, 'total_tickets')) ?>]
            }, {
                name: 'Resueltos',
                data: [<?= implode(',', array_column($supportLoad, 'resueltos')) ?>]
            }],
            chart: {
                type: 'bar',
                height: 250,
                stacked: true,
                toolbar: { show: false }
            },
            theme: { mode: mode },
            xaxis: {
                categories: [<?= "'" . implode("','", array_column($supportLoad, 'agencia')) . "'" ?>]
            },
            colors: ['#adb5bd', '#198754'],
            legend: { position: 'top' }
        };
        var sChart = new ApexCharts(document.querySelector("#supportChart"), supportOptions);
        sChart.render();
        charts.push(sChart);
    }

    // Inicialización inicial
    initCharts();

    // Observar cambios de tema en el body para actualizar gráficos
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                initCharts();
            }
        });
    });
    observer.observe(document.body, { attributes: true });
});
</script>

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.125);
}
.icon-shape {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
}
.bg-soft-success { background: rgba(25, 135, 84, 0.15); }
.bg-soft-primary { background: rgba(13, 110, 253, 0.15); }
.bg-soft-warning { background: rgba(255, 193, 7, 0.15); }
.bg-soft-danger { background: rgba(220, 53, 69, 0.15); }
.transition-base { transition: all 0.3s ease; }
.card:hover { transform: translateY(-5px); }
</style>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
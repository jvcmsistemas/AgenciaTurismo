<?php include BASE_PATH . '/views/layouts/header.php'; ?>

<!-- ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="row mb-4 animate-fade-in">
    <div class="col-md-12">
        <h2 class="fw-bold"><span class="text-primary-gradient">👑 Panel de Control Ejecutivo</span></h2>
        <p class="text-muted">Gestión centralizada de agencias y salud del ecosistema.</p>
    </div>
</div>

<!-- KPI Summary Section -->
<div class="row g-4 mb-4 animate-fade-in-up">
    <!-- Monthly Revenue -->
    <div class="col-md-3">
        <div class="card glass-premium-card border-0 h-100 shadow-hover transition-base">
            <div class="card-body p-4 text-center">
                <div class="icon-circle bg-soft-success mb-3 mx-auto">
                    <i class="bi bi-currency-dollar fs-3 text-success"></i>
                </div>
                <p class="text-muted small fw-bold mb-1 text-uppercase">Recaudación (Mes)</p>
                <h3 class="fw-bold mb-0">S/ <?= number_format($monthlyRevenue, 2) ?></h3>
                <div class="mt-2">
                    <span class="badge bg-success bg-opacity-10 text-success small">
                        <i class="bi bi-arrow-up-right me-1"></i>En tiempo real
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Agencies -->
    <div class="col-md-3">
        <div class="card glass-premium-card border-0 h-100 shadow-hover transition-base">
            <div class="card-body p-4 text-center">
                <div class="icon-circle bg-soft-primary mb-3 mx-auto">
                    <i class="bi bi-building fs-3 text-primary"></i>
                </div>
                <p class="text-muted small fw-bold mb-1 text-uppercase">Agencias Socias</p>
                <h3 class="fw-bold mb-0"><?= count($agencies) ?></h3>
                <div class="mt-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary small">
                        Plataforma Global
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Support -->
    <div class="col-md-3">
        <div class="card glass-premium-card border-0 h-100 shadow-hover transition-base">
            <div class="card-body p-4 text-center">
                <div class="icon-circle bg-soft-warning mb-3 mx-auto">
                    <i class="bi bi-ticket-perforated fs-3 text-warning"></i>
                </div>
                <p class="text-muted small fw-bold mb-1 text-uppercase">Soporte Pendiente</p>
                <h3 class="fw-bold mb-0 text-<?= $openTicketsCount > 0 ? 'danger' : 'success' ?>">
                    <?= $openTicketsCount ?>
                </h3>
                <div class="mt-2">
                    <span class="badge bg-warning bg-opacity-10 text-warning small">
                        Atención Prioritaria
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="col-md-3">
        <div class="card glass-premium-card border-0 h-100 shadow-hover transition-base">
            <div class="card-body p-4 text-center">
                <div class="icon-circle bg-soft-info mb-3 mx-auto">
                    <i class="bi bi-people fs-3 text-info"></i>
                </div>
                <p class="text-muted small fw-bold mb-1 text-uppercase">Staff Activo</p>
                <h3 class="fw-bold mb-0"><?= $userCount ?></h3>
                <div class="mt-2">
                    <span class="badge bg-info bg-opacity-10 text-info small">
                        Usuarios Registrados
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Chart: Global Income Trend -->
    <div class="col-lg-8 animate-fade-in-up">
        <div class="card border-0 shadow-sm h-100 bg-surface-dynamic">
            <div
                class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Evolución de Ingresos</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-glass dropdown-toggle" data-bs-toggle="dropdown">Anual</button>
                    <ul class="dropdown-menu border-0 shadow">
                        <li><a class="dropdown-item" href="#">Últimos 6 meses</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div id="mainRevenueChart"></div>
            </div>
        </div>
    </div>

    <!-- Sidebar: Quick Actions -->
    <div class="col-lg-4 animate-fade-in-up">
        <div class="card border-0 shadow-sm h-100 bg-surface-dynamic overflow-hidden">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-lightning-charge me-2"></i>Centro de Acciones</h5>
            </div>
            <div class="card-body p-4">
                <div class="d-grid gap-3">
                    <a href="<?= BASE_URL ?>admin/agencies/create"
                        class="btn btn-primary btn-lg shadow-sm rounded-pill py-3 transition-base">
                        <i class="bi bi-plus-circle-fill me-2"></i>Expandir Ecosistema (Nueva Agencia)
                    </a>
                    <a href="<?= BASE_URL ?>admin/reports"
                        class="btn btn-outline-primary border-2 rounded-pill py-3 fw-bold transition-base">
                        <i class="bi bi-bar-chart-line-fill me-2"></i>Inteligencia de Negocios
                    </a>
                    <div class="hr-text my-2">Mantenimiento Crítico</div>
                    <!-- FIX: Backup DB Button optimized for themes -->
                    <a href="<?= BASE_URL ?>admin/settings/backup"
                        class="btn btn-backup-premium rounded-pill py-3 transition-base">
                        <i class="bi bi-shield-lock-fill me-2"></i>Generar Respaldo de Seguridad (DB)
                    </a>
                </div>
            </div>
            <div class="bg-soft-primary p-3 mt-auto text-center border-top">
                <small class="text-primary fw-bold">Último backup: Hace 2 días</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Table: Recent Agency Onboarding -->
    <div class="col-lg-7 animate-fade-in-up">
        <div class="card border-0 shadow-sm bg-surface-dynamic">
            <div
                class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Nuevas Alianzas</h5>
                <a href="<?= BASE_URL ?>admin/agencies"
                    class="text-primary text-decoration-none fw-bold small">Gestionar Agencias <i
                        class="bi bi-chevron-right"></i></a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 border-0">Agencia / Contacto</th>
                                <th class="border-0">Plan</th>
                                <th class="text-end pe-4 border-0">Aprobación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentAgencies as $agency): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar-sm bg-soft-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                <i class="bi bi-building text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($agency['nombre'] ?? '') ?></div>
                                                <small
                                                    class="text-muted"><?= htmlspecialchars($agency['email'] ?? '') ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge-code shadow-none small"><?= strtoupper($agency['tipo_suscripcion']) ?></span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <span
                                            class="badge <?= $agency['estado'] === 'activa' ? 'badge-status-aprobado' : 'badge-status-pendiente' ?>">
                                            <?= ucfirst($agency['estado']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tickets Card -->
    <div class="col-lg-5 animate-fade-in-up">
        <div class="card border-0 shadow-sm bg-surface-dynamic h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between">
                <h5 class="fw-bold mb-0">Incidencias de Soporte</h5>
                <span class="badge-status-rechazado"><?= $openTicketsCount ?> Abiertos</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (empty($recentTickets)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-check-circle-fill text-success fs-1 mb-2 d-block"></i>
                            Todo bajo control.
                        </div>
                    <?php else: ?>
                        <?php foreach ($recentTickets as $ticket): ?>
                            <a href="<?= BASE_URL ?>admin/support/show?id=<?= $ticket['id'] ?>"
                                class="list-group-item list-group-item-action px-4 py-3 border-0 border-bottom-dynamic bg-transparent transition-base">
                                <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold text-truncate" style="max-width: 75%;">
                                        <?= htmlspecialchars($ticket['asunto']) ?>
                                    </h6>
                                    <span
                                        class="badge <?= $ticket['prioridad'] === 'alta' ? 'bg-danger' : 'bg-warning text-dark' ?> small">
                                        <?= strtoupper($ticket['prioridad']) ?>
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted fw-bold"><?= htmlspecialchars($ticket['agencia']) ?></small>
                                    <small class="text-muted"><i
                                            class="bi bi-clock me-1"></i><?= date('d M', strtotime($ticket['created_at'])) ?></small>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 text-center p-3">
                <a href="<?= BASE_URL ?>admin/support"
                    class="btn btn-link text-primary text-decoration-none fw-bold btn-sm">Ir al Centro de Operaciones <i
                        class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Scripts for Modern Charts -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function getThemeMode() {
            return document.body.classList.contains('superadmin-light-theme') ? 'light' : 'dark';
        }

        let revenueChart;

        function renderChart() {
            if (revenueChart) revenueChart.destroy();

            const mode = getThemeMode();
            var options = {
                series: [{
                    name: 'Ingresos Aprobados',
                    data: [<?= implode(',', array_column($revenueData, 'total')) ?: '0, 0, 0, 0, 0, 0' ?>]
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: { show: false },
                    background: 'transparent'
                },
                theme: { mode: mode },
                colors: ['#0d6efd'],
                stroke: { curve: 'smooth', width: 4 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.5,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: [<?= "'" . implode("','", array_map(fn($d) => date('M Y', strtotime($d['mes'])), $revenueData)) . "'" ?>],
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                grid: {
                    borderColor: mode === 'dark' ? '#2d3561' : '#e5e7eb',
                    strokeDashArray: 4
                },
                yaxis: {
                    labels: {
                        formatter: function (val) { return 'S/ ' + val.toLocaleString(); }
                    }
                },
                tooltip: {
                    theme: mode,
                    y: { formatter: function (val) { return 'S/ ' + val.toLocaleString(); } }
                }
            };

            revenueChart = new ApexCharts(document.querySelector("#mainRevenueChart"), options);
            revenueChart.render();
        }

        renderChart();

        // Listen for theme changes to re-render charts
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') renderChart();
            });
        });
        observer.observe(document.body, { attributes: true });
    });
</script>

<style>
    /* PREMIUM UI ENHANCEMENTS */
    .text-primary-gradient {
        background: linear-gradient(90deg, #0d6efd 0%, #0dcaf0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .glass-premium-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    .superadmin-light-theme .glass-premium-card {
        background: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(0, 0, 0, 0.1) !important;
    }

    .icon-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-soft-success {
        background: rgba(25, 135, 84, 0.15);
    }

    .bg-soft-primary {
        background: rgba(13, 110, 253, 0.15);
    }

    .bg-soft-warning {
        background: rgba(255, 193, 7, 0.15);
    }

    .bg-soft-info {
        background: rgba(13, 202, 240, 0.15);
    }

    .transition-base {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .shadow-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2) !important;
    }

    .btn-glass {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--text-primary);
    }

    .hr-text {
        display: flex;
        align-items: center;
        color: var(--text-muted);
        font-size: 0.75rem;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .hr-text:before,
    .hr-text:after {
        content: "";
        flex: 1;
        height: 1px;
        background: var(--border-color);
    }

    .hr-text:before {
        margin-right: 15px;
    }

    .hr-text:after {
        margin-left: 15px;
    }

    /* FIX: Backup Button - High visibility even in Dark Mode */
    .btn-backup-premium {
        background: #1a1a2e;
        color: #e2e8f0;
        border: 1px solid #2d3561;
        font-weight: bold;
    }

    .btn-backup-premium:hover {
        background: #e94560;
        color: white;
        border-color: #e94560;
        transform: scale(1.02);
    }

    .superadmin-light-theme .btn-backup-premium {
        background: #0f172a;
        color: #f8fafc;
    }

    .border-bottom-dynamic {
        border-bottom: 1px solid var(--border-color) !important;
    }

    .avatar-sm {
        width: 40px;
        height: 40px;
    }
</style>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
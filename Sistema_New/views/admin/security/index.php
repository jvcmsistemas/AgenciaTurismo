<?php
// Sistema_New/views/admin/security/index.php
include BASE_PATH . '/views/layouts/header.php';
?>

<!-- ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="row mb-4 animate-fade-in">
    <div class="col-md-6">
        <h2 class="fw-bold"><span class="text-primary-gradient">🛡️ Seguridad & Auditoría Central</span></h2>
        <p class="text-muted">Centro de control para la integridad y transparencia del sistema.</p>
    </div>
    <div class="col-md-6 text-end">
        <div class="btn-group shadow-sm">
            <button class="btn btn-white border-end"><i class="bi bi-shield-check me-2 text-success"></i>Estado:
                Protegido</button>
            <button class="btn btn-backup-premium">
                <i class="bi bi-file-earmark-lock me-2"></i>Reporte de Cumplimiento
            </button>
        </div>
    </div>
</div>

<!-- Security KPIs -->
<div class="row g-4 mb-4 animate-fade-in-up">
    <!-- Logins Today -->
    <div class="col-md-4">
        <div class="card glass-premium-card border-0 h-100 shadow-hover transition-base">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="icon-circle bg-soft-primary">
                        <i class="bi bi-person-check fs-3 text-primary"></i>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary">Accesos</span>
                </div>
                <h6 class="text-muted small fw-bold mb-1 text-uppercase">Logins (Hoy)</h6>
                <h3 class="fw-bold mb-0"><?= $totalLoginsToday ?></h3>
            </div>
        </div>
    </div>
    <!-- Failed Attempts -->
    <div class="col-md-4">
        <div class="card glass-premium-card border-0 h-100 shadow-hover transition-base">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="icon-circle bg-soft-danger">
                        <i class="bi bi-shield-exclamation fs-3 text-danger"></i>
                    </div>
                    <span class="badge bg-danger bg-opacity-10 text-danger">Alertas</span>
                </div>
                <h6 class="text-muted small fw-bold mb-1 text-uppercase">Intentos Fallidos</h6>
                <h3 class="fw-bold mb-0 text-danger"><?= $totalFailedToday ?></h3>
            </div>
        </div>
    </div>
    <!-- Active Audits Today -->
    <div class="col-md-4">
        <div class="card glass-premium-card border-0 h-100 shadow-hover transition-base">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="icon-circle bg-soft-success">
                        <i class="bi bi-clock-history fs-3 text-success"></i>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success">Auditado</span>
                </div>
                <h6 class="text-muted small fw-bold mb-1 text-uppercase">Cambios Producidos (Hoy)</h6>
                <h3 class="fw-bold mb-0"><?= $activeAuditsToday ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Chart: Security Trend -->
    <div class="col-lg-8 animate-fade-in-up">
        <div class="card border-0 shadow-sm bg-surface-dynamic">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Evolución de Accesos (7 días)</h5>
            </div>
            <div class="card-body">
                <div id="securityTrendsChart"></div>
            </div>
        </div>
    </div>

    <!-- Quick Info: Critical Status -->
    <div class="col-lg-4 animate-fade-in-up">
        <div class="card border-0 shadow-sm bg-surface-dynamic overflow-hidden">
            <div class="card-header bg-danger text-white py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Atención Requerida</h5>
            </div>
            <div class="card-body p-4">
                <?php if ($totalFailedToday > 5): ?>
                    <div class="alert alert-soft-danger border-0 mb-3">
                        <h6 class="fw-bold mb-1">Actividad Sospechosa Detectada</h6>
                        <small>Se han superado los 5 intentos fallidos hoy. Considere una auditoría de IP.</small>
                    </div>
                <?php else: ?>
                    <div class="alert alert-soft-success border-0 mb-3">
                        <h6 class="fw-bold mb-1">Sin Amenazas Críticas</h6>
                        <small>La tasa de error de autenticación está dentro de los parámetros normales.</small>
                    </div>
                <?php endif; ?>

                <div class="d-grid gap-2">
                    <button class="btn btn-outline-danger btn-sm rounded-pill fw-bold">
                        <i class="bi bi-slash-circle me-1"></i>Bloqueo de IPs
                    </button>
                    <button class="btn btn-outline-primary btn-sm rounded-pill fw-bold">
                        <i class="bi bi-key me-1"></i>Política de Contraseñas
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Access Logs Table (Synchronized) -->
    <div class="col-lg-7 animate-fade-in-up">
        <div class="card border-0 shadow-sm bg-surface-dynamic">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between">
                <h5 class="fw-bold mb-0">Historial de Accesos</h5>
                <span class="badge-code small"><?= count($logs) ?> registros</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="bg-soft-primary">
                                <th class="ps-4 border-0">Timestamp</th>
                                <th class="border-0">Usuario</th>
                                <th class="border-0">Evento / Recurso</th>
                                <th class="border-0">IP de Origen</th>
                                <th class="text-end pe-4 border-0">Resultado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td class="ps-4 small">
                                        <div class="fw-bold"><?= date('H:i:s', strtotime($log['fecha_hora'])) ?></div>
                                        <div class="text-muted small"><?= date('d/m/Y', strtotime($log['fecha_hora'])) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($log['usuario'] ?? 'Sistema') ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($log['email'] ?? '-') ?></small>
                                    </td>
                                    <td>
                                        <div class="badge-code shadow-none small d-inline-block"><?= strtoupper($log['accion']) ?></div>
                                        <div class="text-muted extra-small mt-1"><?= htmlspecialchars($log['recurso'] ?? '') ?></div>
                                    </td>
                                    <td class="small text-monospace"><?= $log['ip_origen'] ?></td>
                                    <td class="text-end pe-4">
                                        <span
                                            class="badge <?= $log['estado'] === 'exitoso' ? 'badge-status-aprobado' : 'badge-status-rechazado' ?>">
                                            <?= ucfirst($log['estado']) ?>
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

    <!-- Audit Logs List (Synchronized) -->
    <div class="col-lg-5 animate-fade-in-up">
        <div class="card border-0 shadow-sm bg-surface-dynamic">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between">
                <h5 class="fw-bold mb-0">Trazabilidad de Cambios</h5>
                <i class="bi bi-filter text-primary"></i>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush list-group-premium">
                    <?php if (empty($audits)): ?>
                        <div class="text-center py-5 text-muted">No hay cambios registrados.</div>
                    <?php else: ?>
                        <?php foreach ($audits as $audit): ?>
                            <div class="list-group-item px-4 py-3 bg-transparent border-bottom-dynamic">
                                <div class="d-flex w-100 justify-content-between align-items-start mb-1">
                                    <div class="fw-bold text-primary">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        <?= strtoupper($audit['operacion']) ?> en <?= htmlspecialchars($audit['tabla_afectada'] ?? '') ?>
                                    </div>
                                    <small class="text-muted"><?= date('H:i', strtotime($audit['fecha_hora'])) ?></small>
                                </div>
                                <div class="small mb-2">
                                    <span class="text-muted">Por:</span> <strong><?= htmlspecialchars($audit['usuario'] ?? 'Sistema') ?></strong>
                                    <span class="mx-2 text-muted">|</span>
                                    <span class="text-muted">ID:</span> <span class="badge-code small"><?= $audit['registro_id'] ?? '-' ?></span>
                                </div>
                                <?php if ($audit['motivo']): ?>
                                    <div class="alert alert-soft-primary border-0 py-1 px-2 mb-0 extra-small">
                                        <i class="bi bi-quote me-1"></i><?= htmlspecialchars($audit['motivo'] ?? '') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function getThemeMode() {
            return document.body.classList.contains('superadmin-light-theme') ? 'light' : 'dark';
        }

        let securityChart;

        function renderSecurityChart() {
            if (securityChart) securityChart.destroy();

            const mode = getThemeMode();
            var options = {
                series: [{
                    name: 'Exitosos',
                    data: [<?= implode(',', array_column($securityTrends, 'exitosos')) ?: '0' ?>]
                }, {
                    name: 'Fallidos',
                    data: [<?= implode(',', array_column($securityTrends, 'fallidos')) ?: '0' ?>]
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                    toolbar: { show: false },
                    background: 'transparent'
                },
                theme: { mode: mode },
                colors: ['#0d6efd', '#dc3545'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 8,
                        columnWidth: '40%',
                    }
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: [<?= "'" . implode("','", array_map(fn($d) => date('d M', strtotime($d['fecha'])), $securityTrends)) . "'" ?>],
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                grid: {
                    borderColor: mode === 'dark' ? '#2d3561' : '#e5e7eb',
                    strokeDashArray: 4
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    offsetY: -10
                },
                tooltip: { theme: mode }
            };

            securityChart = new ApexCharts(document.querySelector("#securityTrendsChart"), options);
            securityChart.render();
        }

        renderSecurityChart();

        // Listen for theme changes to re-render charts
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') renderSecurityChart();
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

    .bg-soft-primary {
        background: rgba(13, 110, 253, 0.15);
    }

    .bg-soft-danger {
        background: rgba(220, 53, 69, 0.15);
    }

    .bg-soft-success {
        background: rgba(25, 135, 84, 0.15);
    }

    .transition-base {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .shadow-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2) !important;
    }

    .extra-small {
        font-size: 0.7rem;
    }

    .alert-soft-danger {
        background: rgba(220, 53, 69, 0.1);
        color: #ea868f;
    }

    .superadmin-light-theme .alert-soft-danger {
        background: #fdf2f2;
        color: #9b1c1c;
    }

    .alert-soft-success {
        background: rgba(25, 135, 84, 0.1);
        color: #75b798;
    }

    .superadmin-light-theme .alert-soft-success {
        background: #f3faf7;
        color: #03543f;
    }

    .alert-soft-primary {
        background: rgba(13, 110, 253, 0.08);
        color: var(--text-primary);
        border-left: 3px solid #0d6efd !important;
    }

    .border-bottom-dynamic {
        border-bottom: 1px solid var(--border-color) !important;
    }

    .list-group-premium .list-group-item:hover {
        background: rgba(13, 110, 253, 0.03);
    }
</style>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
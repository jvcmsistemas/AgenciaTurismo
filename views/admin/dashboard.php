<?php include BASE_PATH . '/views/layouts/header.php'; ?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row mb-4 animate-fade-in">
    <div class="col-md-12">
        <h2 class="fw-bold text-primary">Panel de Administración</h2>
        <p class="text-muted">Resumen global del sistema y métricas clave.</p>
    </div>
</div>

<!-- KPI Cards -->
<div class="row mb-4 g-4 animate-fade-in-up">
    <!-- Ingresos Mes -->
    <div class="col-md-3">
        <div class="card glass-card border-0 h-100 shadow-sm hover-scale">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="bg-success bg-opacity-10 p-2 rounded">
                        <i class="bi bi-currency-dollar fs-4 text-success"></i>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success positive-trend">
                        <i class="bi bi-graph-up-arrow me-1"></i>Hoy
                    </span>
                </div>
                <h6 class="text-muted mb-1 text-uppercase small fw-bold">Ingresos (Mes)</h6>
                <h3 class="fw-bold mb-0 text-dark">S/ <?php echo number_format($monthlyRevenue, 2); ?></h3>
            </div>
        </div>
    </div>

    <!-- Tickets Abiertos -->
    <div class="col-md-3">
        <div class="card glass-card border-0 h-100 shadow-sm hover-scale">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="bg-warning bg-opacity-10 p-2 rounded">
                        <i class="bi bi-ticket-perforated fs-4 text-warning"></i>
                    </div>
                    <span class="badge bg-warning bg-opacity-10 text-warning">Pendientes</span>
                </div>
                <h6 class="text-muted mb-1 text-uppercase small fw-bold">Soporte Pendiente</h6>
                <h3 class="fw-bold mb-0 text-dark"><?php echo $openTicketsCount; ?></h3>
            </div>
        </div>
    </div>

    <!-- Agencias Totales -->
    <div class="col-md-3">
        <div class="card glass-card border-0 h-100 shadow-sm hover-scale">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="bg-primary bg-opacity-10 p-2 rounded">
                        <i class="bi bi-building fs-4 text-primary"></i>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary">Total</span>
                </div>
                <h6 class="text-muted mb-1 text-uppercase small fw-bold">Agencias</h6>
                <h3 class="fw-bold mb-0 text-dark"><?php echo count($agencies); ?></h3>
            </div>
        </div>
    </div>

    <!-- Usuarios -->
    <div class="col-md-3">
        <div class="card glass-card border-0 h-100 shadow-sm hover-scale">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="bg-info bg-opacity-10 p-2 rounded">
                        <i class="bi bi-people fs-4 text-info"></i>
                    </div>
                    <span class="badge bg-info bg-opacity-10 text-info">Activos</span>
                </div>
                <h6 class="text-muted mb-1 text-uppercase small fw-bold">Usuarios</h6>
                <h3 class="fw-bold mb-0 text-dark"><?php echo $userCount; ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 animate-fade-in-up" style="animation-delay: 0.1s;">
    <!-- Main Content: Stats & New Agencies -->
    <div class="col-lg-8">
        <!-- Revenue Chart -->
        <div class="card glass-card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between">
                <h5 class="fw-bold text-primary mb-0">Resumen de Ingresos (Simulado)</h5>
                <select class="form-select form-select-sm w-auto">
                    <option>Este Año</option>
                    <option>Últimos 6 meses</option>
                </select>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" style="max-height: 300px;"></canvas>
            </div>
        </div>

        <!-- Recent Agencies Table -->
        <div class="card glass-card border-0 shadow-sm">
            <div
                class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-primary mb-0">Agencias Recientes</h5>
                <a href="<?= BASE_URL ?>admin/agencies" class="btn btn-sm btn-light text-primary fw-bold">Ver Todo</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Agencia</th>
                                <th>Plan</th>
                                <th class="text-end pe-4">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentAgencies)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Sin registros.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentAgencies as $agency): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($agency['nombre'] ?? '') ?>
                                            </div>
                                            <small class="text-muted"><?= htmlspecialchars($agency['email'] ?? '') ?></small>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-light border text-dark"><?= ucfirst($agency['tipo_suscripcion']) ?></span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">Activa</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar: Quick Actions & Support -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card glass-card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold text-primary mb-0">Acciones Rápidas</h5>
            </div>
            <div class="card-body p-4">
                <div class="d-grid gap-3">
                    <a href="<?= BASE_URL ?>admin/agencies/create" class="btn btn-primary shadow-sm">
                        <i class="bi bi-plus-lg me-2"></i>Nueva Agencia
                    </a>
                    <a href="<?= BASE_URL ?>admin/reports" class="btn btn-outline-primary border-2 fw-bold">
                        <i class="bi bi-bar-chart-fill me-2"></i>Ver Reportes
                    </a>
                    <a href="<?= BASE_URL ?>admin/settings/backup" class="btn btn-light text-dark border">
                        <i class="bi bi-database-down me-2"></i>Backup DB
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Support Tickets -->
        <div class="card glass-card border-0 shadow-sm h-100">
            <div
                class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-primary mb-0">Soporte</h5>
                <span class="badge bg-danger rounded-pill"><?= $openTicketsCount ?></span>
            </div>
            <div class="list-group list-group-flush">
                <?php if (empty($recentTickets)): ?>
                    <div class="text-center py-4 text-muted">No hay tickets pendientes.</div>
                <?php else: ?>
                    <?php foreach ($recentTickets as $ticket): ?>
                        <a href="<?= BASE_URL ?>admin/support/show?id=<?= $ticket['id'] ?>"
                            class="list-group-item list-group-item-action px-4 py-3 border-0 border-bottom">
                            <div class="d-flex w-100 justify-content-between mb-1">
                                <h6 class="mb-1 fw-bold text-truncate" style="max-width: 70%;">
                                    <?= htmlspecialchars($ticket['asunto']) ?>
                                </h6>
                                <small class="text-muted"><?= date('d/m', strtotime($ticket['created_at'])) ?></small>
                            </div>
                            <div class="mb-1 small text-muted text-truncate"><?= htmlspecialchars($ticket['agencia']) ?></div>
                            <span class="badge bg-warning text-dark"
                                style="font-size: 0.7em;"><?= $ticket['prioridad'] ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-transparent border-0 text-center p-3">
                <a href="<?= BASE_URL ?>admin/support" class="small fw-bold text-decoration-none">Ir al Centro de
                    Soporte</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('revenueChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [{
                        label: 'Ingresos 2024 (Simulado)',
                        data: [1200, 1900, 3000, 5000, 2000, 3000, 4500, 4000, 6000, 7000, 8000, <?php echo $monthlyRevenue > 0 ? $monthlyRevenue : 9500; ?>],
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    });
</script>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
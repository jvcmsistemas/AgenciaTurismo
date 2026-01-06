<?php
// Sistema_New/views/admin/payments/index.php
include BASE_PATH . '/views/layouts/header.php';
?>

<div class="row mb-4 animate-fade-in">
    <div class="col-md-6">
        <h2 class="mb-0">💰 Pagos & Facturación</h2>
        <p class="text-muted">Monitor de ingresos y transacciones globales</p>
    </div>
    <div class="col-md-6 text-end">
        <form class="d-inline-flex gap-2" method="GET">
            <input type="date" name="start_date" class="form-control"
                value="<?= $_GET['start_date'] ?? date('Y-m-01') ?>">
            <input type="date" name="end_date" class="form-control" value="<?= $_GET['end_date'] ?? date('Y-m-d') ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
        </form>
    </div>
</div>

<!-- KPI Cards -->
<div class="row mb-4 animate-fade-in-up">
    <!-- Total Income -->
    <div class="col-md-4">
        <div class="card bg-success text-white border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Ingresos Totales (Periodo)</h6>
                        <h3 class="mb-0 fw-bold">$<?= number_format($totalIncome, 2) ?></h3>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- By Method (Top 1) -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Método Principal</h6>
                        <?php if (!empty($statsByMethod)): ?>
                            <h3 class="mb-0 fw-bold"><?= ucfirst($statsByMethod[0]['metodo_pago']) ?></h3>
                            <small class="text-success">$<?= number_format($statsByMethod[0]['total_monto'], 2) ?></small>
                        <?php else: ?>
                            <h3 class="mb-0 fw-bold">-</h3>
                        <?php endif; ?>
                    </div>
                    <i class="fas fa-credit-card fa-2x text-primary opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- By Agency (Top 1) -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Agencia Top</h6>
                        <?php if (!empty($statsByAgency)): ?>
                            <h3 class="mb-0 fw-bold"><?= htmlspecialchars($statsByAgency[0]['agencia']) ?></h3>
                            <small
                                class="text-success">$<?= number_format($statsByAgency[0]['total_ingresos'], 2) ?></small>
                        <?php else: ?>
                            <h3 class="mb-0 fw-bold">-</h3>
                        <?php endif; ?>
                    </div>
                    <i class="fas fa-trophy fa-2x text-warning opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row animate-fade-in-up" style="animation-delay: 0.1s;">
    <!-- Recent Transactions Table -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Transacciones Recientes</h5>

            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Reserva</th>
                                <th>Agencia</th>
                                <th>Método</th>
                                <th>Monto</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($payments)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No se encontraron pagos en este
                                        periodo.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($payments as $payment): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($payment['fecha_pago'])) ?></td>
                                        <td>
                                            <span class="badge badge-code">
                                                <?= htmlspecialchars($payment['codigo_reserva']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($payment['agencia_nombre']) ?></td>
                                        <td>
                                            <?php
                                            $icon = 'money-bill';
                                            if ($payment['metodo_pago'] === 'tarjeta')
                                                $icon = 'credit-card';
                                            if ($payment['metodo_pago'] === 'transferencia')
                                                $icon = 'university';
                                            ?>
                                            <i class="fas fa-<?= $icon ?> me-1 text-muted"></i>
                                            <?= ucfirst($payment['metodo_pago']) ?>
                                        </td>
                                        <td class="fw-bold text-end">$<?= number_format($payment['monto'], 2) ?></td>
                                        <td>
                                            <?php if ($payment['estado'] === 'aprobado'): ?>
                                                <span class="badge badge-status-aprobado">Aprobado</span>
                                            <?php elseif ($payment['estado'] === 'pendiente'): ?>
                                                <span class="badge badge-status-pendiente">Pendiente</span>
                                            <?php else: ?>
                                                <span class="badge badge-status-rechazado">Rechazado</span>
                                            <?php endif; ?>
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

    <!-- Stats by Agency -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ingresos por Agencia</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php if (empty($statsByAgency)): ?>
                        <li class="list-group-item text-center text-muted py-3">Sin datos</li>
                    <?php else: ?>
                        <?php foreach ($statsByAgency as $stat): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold"><?= htmlspecialchars($stat['agencia']) ?></div>
                                    <small class="text-muted"><?= $stat['cantidad_transacciones'] ?> transacciones</small>
                                </div>
                                <span
                                    class="badge bg-primary rounded-pill">$<?= number_format($stat['total_ingresos'], 2) ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
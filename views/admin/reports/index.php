<?php
// Sistema_New/views/admin/reports/index.php
include BASE_PATH . '/views/layouts/header.php';
?>

<div class="row mb-4 animate-fade-in">
    <div class="col-md-6">
        <h2 class="mb-0">📊 Reportes & Analítica</h2>
        <p class="text-muted">Visión 360° del rendimiento global</p>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-outline-success">
            <i class="fas fa-file-excel me-2"></i>Exportar Excel
        </button>
    </div>
</div>

<!-- Tabs Navigation -->
<ul class="nav nav-tabs mb-4 animate-fade-in-up" id="reportTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" id="financial-tab" data-bs-toggle="tab" data-bs-target="#financial"
            type="button">
            💰 Financiero
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="operational-tab" data-bs-toggle="tab" data-bs-target="#operational" type="button">
            ⚙️ Operativo
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="satisfaction-tab" data-bs-toggle="tab" data-bs-target="#satisfaction"
            type="button">
            ⭐ Satisfacción
        </button>
    </li>
</ul>

<div class="tab-content animate-fade-in-up">

    <!-- Financiero -->
    <div class="tab-pane fade show active" id="financial">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Tendencia de Ingresos</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($incomeTrends)): ?>
                            <p class="text-muted text-center py-5">No hay datos financieros suficientes.</p>
                        <?php else: ?>
                            <table class="table">
                                <thead>
                                    <th>Periodo</th>
                                    <th class="text-end">Vendido</th>
                                    <th class="text-end">Por Cobrar</th>
                                </thead>
                                <tbody>
                                    <?php foreach ($incomeTrends as $trend): ?>
                                        <tr>
                                            <td><?= $trend['periodo'] ?></td>
                                            <td class="text-end fw-bold text-success">
                                                $<?= number_format($trend['total_vendido'], 2) ?></td>
                                            <td class="text-end text-danger">$<?= number_format($trend['total_pendiente'], 2) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Top Agencias</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php if (empty($topAgencies)): ?>
                                <li class="list-group-item text-muted text-center">Sin datos</li>
                            <?php else: ?>
                                <?php foreach ($topAgencies as $agency): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><?= htmlspecialchars($agency['agencia']) ?></span>
                                        <span class="badge bg-primary">$<?= number_format($agency['total'], 0) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Operativo -->
    <div class="tab-pane fade" id="operational">
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center mb-4">
                    <div class="card-body">
                        <h6 class="text-muted">Total Reservas</h6>
                        <h2 class="fw-bold"><?= $totalReservas ?></h2>
                    </div>
                </div>
            </div>
            <!-- More stats can go here -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Estado de Reservas</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($reservationStats as $estado => $count): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-capitalize"><?= $estado ?></span>
                                <span><?= $count ?></span>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                <?php
                                $pct = $totalReservas > 0 ? ($count / $totalReservas) * 100 : 0;
                                $color = match ($estado) {
                                    'completada' => 'bg-success',
                                    'cancelada' => 'bg-danger',
                                    'confirmada' => 'bg-primary',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <div class="progress-bar <?= $color ?>" style="width: <?= $pct ?>%"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Satisfacción -->
    <div class="tab-pane fade" id="satisfaction">
        <div class="row justify-content-center">
            <div class="col-md-4 text-center">
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h1 class="display-3 fw-bold text-warning mb-0">
                            <?= number_format($satisfaction['promedio'] ?? 0, 1) ?>
                        </h1>
                        <div class="mb-2">
                            <?php
                            $stars = round($satisfaction['promedio'] ?? 0);
                            for ($i = 1; $i <= 5; $i++)
                                echo $i <= $stars ? '⭐' : '☆';
                            ?>
                        </div>
                        <p class="text-muted"><?= $satisfaction['total_reviews'] ?? 0 ?> Opiniones</p>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="mb-3">Opiniones Recientes</h5>
        <div class="row">
            <?php if (empty($recentReviews)): ?>
                <div class="col-12 text-center text-muted py-5">No hay opiniones aún.</div>
            <?php else: ?>
                <?php foreach ($recentReviews as $review): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="fw-bold mb-0"><?= htmlspecialchars($review['agencia']) ?></h6>
                                    <span class="text-warning"><?= str_repeat('⭐', $review['calificacion']) ?></span>
                                </div>
                                <p class="card-text">"<?= htmlspecialchars($review['comentario']) ?>"</p>
                                <small class="text-muted"><?= date('d/m/Y', strtotime($review['created_at'])) ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
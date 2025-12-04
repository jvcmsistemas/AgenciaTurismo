<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold text-primary">Hola, <?php echo $_SESSION['user_name']; ?></h2>
            <p class="text-muted">Bienvenido al panel de gestión de tu agencia.</p>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card glass-card border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="bi bi-cash-coin fs-3 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Ingresos Totales</h6>
                        <h3 class="fw-bold mb-0">S/ <?php echo number_format($totalRevenue, 2); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card glass-card border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="bi bi-calendar-check fs-3 text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Reservas Totales</h6>
                        <h3 class="fw-bold mb-0"><?php echo $totalReservations; ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card glass-card border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="bi bi-map fs-3 text-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Tours Activos</h6>
                        <h3 class="fw-bold mb-0"><?php echo $totalTours; ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Reservations -->
        <div class="col-md-8">
            <div class="card glass-card border-0">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-primary">Últimas Reservas</h5>
                    <a href="<?php echo BASE_URL; ?>agency/reservations" class="btn btn-sm btn-outline-primary">Ver
                        Todas</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Cliente</th>
                                    <th>Fecha Tour</th>
                                    <th>Estado</th>
                                    <th class="text-end pe-4">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentReservations)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            No hay reservas recientes.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentReservations as $res): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-semibold">
                                                    <?php echo htmlspecialchars($res['cliente_nombre'] . ' ' . $res['cliente_apellido']); ?>
                                                </div>
                                                <div class="small text-muted">
                                                    <?php echo htmlspecialchars($res['tours_nombres']); ?></div>
                                            </td>
                                            <td><?php echo $res['fecha_inicio_tour'] ? date('d/m/Y', strtotime($res['fecha_inicio_tour'])) : '-'; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $statusColor = 'secondary';
                                                if ($res['estado'] === 'confirmada')
                                                    $statusColor = 'success';
                                                if ($res['estado'] === 'pendiente')
                                                    $statusColor = 'warning';
                                                if ($res['estado'] === 'cancelada')
                                                    $statusColor = 'danger';
                                                ?>
                                                <span
                                                    class="badge bg-<?php echo $statusColor; ?> bg-opacity-25 text-<?php echo $statusColor; ?> border border-<?php echo $statusColor; ?>">
                                                    <?php echo ucfirst($res['estado']); ?>
                                                </span>
                                            </td>
                                            <td class="text-end pe-4 fw-bold">S/
                                                <?php echo number_format($res['precio_total'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-md-4">
            <div class="card glass-card border-0 mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="fw-bold mb-0 text-primary">Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo BASE_URL; ?>agency/tours/create"
                            class="btn btn-outline-primary text-start p-3">
                            <i class="bi bi-plus-circle fs-4 me-2 align-middle"></i>
                            <span class="align-middle fw-bold">Nuevo Tour</span>
                            <div class="small text-muted ms-5">Agrega una nueva experiencia al catálogo</div>
                        </a>
                        <a href="<?php echo BASE_URL; ?>agency/reservations"
                            class="btn btn-outline-success text-start p-3">
                            <i class="bi bi-calendar-plus fs-4 me-2 align-middle"></i>
                            <span class="align-middle fw-bold">Gestionar Reservas</span>
                            <div class="small text-muted ms-5">Revisa y confirma solicitudes pendientes</div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Status or Promo -->
            <div class="card bg-primary text-white border-0 rounded-4"
                style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                <div class="card-body p-4">
                    <h5 class="fw-bold"><i class="bi bi-stars me-2"></i>Tip Pro</h5>
                    <p class="mb-0 small opacity-75">Mantén tu catálogo de tours actualizado con fotos atractivas para
                        recibir más reservas a través de la IA.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
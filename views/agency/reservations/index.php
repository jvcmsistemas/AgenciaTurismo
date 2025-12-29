<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary fw-bold mb-0">Gestión de Reservas</h2>
            <p class="text-muted mb-0">Administra las ventas y cupos de tus tours.</p>
        </div>
        <a href="<?php echo BASE_URL; ?>agency/reservations/create" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Nueva Reserva
        </a>
    </div>

    <!-- Buscador (Placeholder por ahora) -->
    <div class="card glass-card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form class="row g-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i
                                class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0"
                            placeholder="Buscar por código, cliente..." disabled>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="glass-card border-0 shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive"
                style="min-height: 400px; padding-bottom: 120px; overflow-y: hidden; overflow-x: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Código</th>
                            <th>Cliente</th>
                            <th>Tour / Servicio</th>
                            <th>Fecha Viaje</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reservations)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="mb-3"><i class="bi bi-calendar-check fs-1 text-secondary opacity-50"></i>
                                    </div>
                                    <h5 class="fw-normal">No hay reservas registradas aún</h5>
                                    <p class="small">Registra tu primera venta usando el botón superior.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $res): ?>
                                <tr class="align-middle border-bottom">
                                    <td class="ps-4 py-3">
                                        <span
                                            class="fw-bold text-primary display-font fs-6"><?php echo htmlspecialchars($res['codigo_reserva']); ?></span>
                                        <div class="small text-muted mt-1">
                                            <i
                                                class="bi bi-clock me-1"></i><?php echo date('d/m/Y H:i', strtotime($res['fecha_hora_reserva'])); ?>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-initial rounded-circle bg-light text-primary me-3 d-flex justify-content-center align-items-center shadow-sm"
                                                style="width: 40px; height: 40px; font-size: 1.1em;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark mb-0">
                                                    <?php echo htmlspecialchars($res['cliente_nombre'] . ' ' . $res['cliente_apellido']); ?>
                                                </div>
                                                <div class="small text-muted text-truncate" style="max-width: 150px;">
                                                    <?php echo htmlspecialchars($res['cliente_email']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-truncate fw-semibold text-dark" style="max-width: 250px;"
                                            title="<?php echo htmlspecialchars($res['tours_nombres']); ?>">
                                            <?php echo htmlspecialchars($res['tours_nombres'] ?: 'Sin detalles'); ?>
                                        </div>
                                        <span class="badge bg-white text-secondary border shadow-sm mt-2 rounded-pill px-3">
                                            <i class="bi bi-people-fill me-1 text-primary"></i>
                                            <?php echo $res['cantidad_personas']; ?> Pasajeros
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex flex-column justify-content-center">
                                            <span
                                                class="fw-bold text-dark fs-6"><?php echo $res['fecha_inicio_tour'] ? date('d M', strtotime($res['fecha_inicio_tour'])) : '-'; ?></span>
                                            <span
                                                class="small text-muted"><?php echo $res['fecha_inicio_tour'] ? date('Y', strtotime($res['fecha_inicio_tour'])) : ''; ?></span>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <?php
                                        $statusClass = match ($res['estado']) {
                                            'confirmada' => 'success',
                                            'pendiente' => 'warning',
                                            'cancelada' => 'danger',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <span
                                            class="badge bg-<?php echo $statusClass; ?> bg-opacity-10 text-<?php echo $statusClass; ?> px-3 py-2 rounded-pill border border-<?php echo $statusClass; ?> border-opacity-25 shadow-sm">
                                            <i class="bi bi-circle-fill me-1 small" style="font-size: 0.5em;"></i>
                                            <?php echo ucfirst($res['estado']); ?>
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div class="fw-bold text-dark fs-6">S/
                                            <?php echo number_format($res['precio_total'], 2); ?>
                                        </div>
                                        <?php if ($res['saldo_pendiente'] > 0): ?>
                                            <div class="small text-danger fw-bold mt-1">
                                                <i class="bi bi-exclamation-circle me-1"></i>Debe: S/
                                                <?php echo number_format($res['saldo_pendiente'], 2); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="small text-success fw-bold mt-1">
                                                <i class="bi bi-check-circle-fill me-1"></i>Pagado
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4 py-3">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-light btn-sm rounded-circle shadow-sm"
                                                data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static"
                                                style="width: 32px; height: 32px;">
                                                <i class="bi bi-three-dots-vertical text-dark"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-2"
                                                style="min-width: 200px; z-index: 1050;">
                                                <li>
                                                    <h6 class="dropdown-header text-uppercase small fw-bold text-muted ps-3">
                                                        Gestión</h6>
                                                </li>
                                                <li>
                                                    <a href="<?php echo BASE_URL; ?>agency/reservations/show?id=<?php echo $res['id']; ?>"
                                                        class="dropdown-item rounded py-2 d-flex align-items-center mb-1">
                                                        <i class="bi bi-eye text-primary me-3 fs-5"></i>
                                                        <span class="fw-medium">Ver Detalle</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider my-2">
                                                </li>
                                                <li>
                                                    <form action="<?php echo BASE_URL; ?>agency/reservations/update_status"
                                                        method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
                                                        <button type="submit" name="status" value="confirmada"
                                                            class="dropdown-item rounded py-2 d-flex align-items-center mb-1 text-success">
                                                            <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                                                            <span class="fw-medium">Confirmar</span>
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="<?php echo BASE_URL; ?>agency/reservations/update_status"
                                                        method="POST">
                                                        <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
                                                        <button type="submit" name="status" value="cancelada"
                                                            class="dropdown-item rounded py-2 d-flex align-items-center text-danger">
                                                            <i class="bi bi-x-circle-fill me-3 fs-5"></i>
                                                            <span class="fw-medium">Cancelar</span>
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
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

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
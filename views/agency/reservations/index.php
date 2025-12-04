<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary fw-bold mb-0">Reservas</h2>
            <p class="text-muted mb-0">Gestiona las reservas y ventas de tu agencia</p>
        </div>
        <!-- Botón para futura implementación de creación manual -->
        <button class="btn btn-outline-secondary" disabled title="Próximamente">
            <i class="bi bi-plus-lg me-2"></i>Nueva Reserva Manual
        </button>
    </div>

    <div class="glass-card border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Código</th>
                            <th>Cliente</th>
                            <th>Tour / Servicio</th>
                            <th>Fecha Inicio</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reservations)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                                    No hay reservas registradas aún.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $res): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-primary">
                                        <?php echo htmlspecialchars($res['codigo_reserva']); ?>
                                        <div class="small text-muted fw-normal">
                                            <?php echo date('d/m/Y H:i', strtotime($res['fecha_hora_reserva'])); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">
                                            <?php echo htmlspecialchars($res['cliente_nombre'] . ' ' . $res['cliente_apellido']); ?>
                                        </div>
                                        <div class="small text-muted"><?php echo htmlspecialchars($res['cliente_email']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($res['tours_nombres'] ?: 'Sin detalles'); ?>
                                        <div class="small text-muted">
                                            <i class="bi bi-people me-1"></i> <?php echo $res['cantidad_personas']; ?> pax
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo $res['fecha_inicio_tour'] ? date('d/m/Y', strtotime($res['fecha_inicio_tour'])) : '-'; ?>
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
                                    <td class="fw-bold">
                                        S/ <?php echo number_format($res['precio_total'], 2); ?>
                                        <?php if ($res['saldo_pendiente'] > 0): ?>
                                            <div class="small text-danger" title="Saldo Pendiente">
                                                (Debe: S/ <?php echo number_format($res['saldo_pendiente'], 2); ?>)
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                Estado
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end glass-card border-0">
                                                <li>
                                                    <form action="<?php echo BASE_URL; ?>agency/reservations/update_status"
                                                        method="POST">
                                                        <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
                                                        <button type="submit" name="status" value="confirmada"
                                                            class="dropdown-item text-success">
                                                            <i class="bi bi-check-circle me-2"></i>Confirmar
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="<?php echo BASE_URL; ?>agency/reservations/update_status"
                                                        method="POST">
                                                        <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
                                                        <button type="submit" name="status" value="cancelada"
                                                            class="dropdown-item text-danger">
                                                            <i class="bi bi-x-circle me-2"></i>Cancelar
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
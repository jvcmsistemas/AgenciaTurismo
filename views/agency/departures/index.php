<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-success fw-bold mb-1">Programación de Salidas</h2>
            <p class="text-muted mb-0">Gestiona las fechas y cupos de tus próximas experiencias.</p>
        </div>
        <a href="<?php echo BASE_URL; ?>agency/departures/create" class="btn btn-success rounded-pill px-4 shadow-sm">
            <i class="bi bi-calendar-plus me-2"></i>Nueva Salida
        </a>
    </div>

    <!-- Filtros (Próximamente) -->
    <!-- <div class="card glass-card border-0 shadow-sm mb-4">...</div> -->

    <div class="glass-card border-0 shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Fecha & Hora</th>
                            <th>Tour / Experiencia</th>
                            <th>Recursos Asignados</th>
                            <th>Cupos (Disp. / Total)</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($departures)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="mb-3"><i class="bi bi-calendar-x fs-1 text-secondary opacity-50"></i></div>
                                    <h5 class="fw-normal">No hay salidas programadas</h5>
                                    <p class="small">Comienza programando una fecha para tus tours.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($departures as $departure): ?>
                                <?php
                                $date = new DateTime($departure['fecha_salida']);
                                $statusColor = match ($departure['estado']) {
                                    'programada' => 'primary',
                                    'confirmada' => 'success',
                                    'cerrada' => 'secondary',
                                    'cancelada' => 'danger',
                                    default => 'info'
                                };

                                // Cálculo de porcentaje de ocupación
                                $ocupacion = 100 - (($departure['cupos_disponibles'] / $departure['cupos_totales']) * 100);
                                $barColor = $ocupacion > 90 ? 'danger' : ($ocupacion > 50 ? 'warning' : 'success');
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark fs-5"><?php echo $date->format('d M'); ?></span>
                                            <span class="text-muted small"><?php echo $date->format('h:i A'); ?></span>
                                            <span
                                                class="badge bg-light text-dark border mt-1"><?php echo $date->format('Y'); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark mb-1">
                                            <?php echo htmlspecialchars($departure['tour_nombre']); ?></div>
                                        <div class="small text-muted"><i
                                                class="bi bi-geo-alt me-1"></i><?php echo htmlspecialchars($departure['ubicacion']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <?php if ($departure['guia_nombre']): ?>
                                                <small class="text-muted"><i
                                                        class="bi bi-person-badge me-1 text-primary"></i><?php echo htmlspecialchars($departure['guia_nombre']); ?></small>
                                            <?php else: ?>
                                                <small class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>Sin
                                                    Guía</small>
                                            <?php endif; ?>

                                            <?php if ($departure['transporte_placa']): ?>
                                                <small class="text-muted"><i
                                                        class="bi bi-bus-front me-1 text-success"></i><?php echo htmlspecialchars($departure['transporte_placa']); ?></small>
                                            <?php else: ?>
                                                <small class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>Sin
                                                    Transporte</small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td style="min-width: 150px;">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="fw-bold text-dark"><?php echo $departure['cupos_disponibles']; ?>
                                                Libres</span>
                                            <span class="text-muted small">de <?php echo $departure['cupos_totales']; ?></span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-<?php echo $barColor; ?>" role="progressbar"
                                                style="width: <?php echo $ocupacion; ?>%"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-<?php echo $statusColor; ?> bg-opacity-10 text-<?php echo $statusColor; ?> border border-<?php echo $statusColor; ?> rounded-pill px-3">
                                            <?php echo ucfirst($departure['estado']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-sm btn-light text-primary rounded-circle me-2 shadow-sm"
                                                title="Editar (Próximamente)">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="<?php echo BASE_URL; ?>agency/departures/delete?id=<?php echo $departure['id']; ?>"
                                                class="btn btn-sm btn-light text-danger rounded-circle shadow-sm"
                                                onclick="return confirm('¿Eliminar esta salida?');" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </a>
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
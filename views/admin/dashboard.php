<?php include BASE_PATH . '/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold text-primary">Panel de Administración</h2>
        <p class="text-muted">Resumen global del sistema y accesos rápidos.</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4 g-4">
    <div class="col-md-4">
        <div class="card glass-card border-0 h-100 shadow-sm">
            <div class="card-body d-flex align-items-center p-4">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="bi bi-building fs-3 text-primary"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-uppercase small fw-bold">Agencias Totales</h6>
                    <h2 class="fw-bold mb-0 text-dark"><?php echo count($agencies); ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card glass-card border-0 h-100 shadow-sm">
            <div class="card-body d-flex align-items-center p-4">
                <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="bi bi-check-circle fs-3 text-success"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-uppercase small fw-bold">Agencias Activas</h6>
                    <h2 class="fw-bold mb-0 text-dark">
                        <?php echo count(array_filter($agencies, fn($a) => $a['estado'] === 'activa')); ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card glass-card border-0 h-100 shadow-sm">
            <div class="card-body d-flex align-items-center p-4">
                <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="bi bi-people fs-3 text-info"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-uppercase small fw-bold">Usuarios Registrados</h6>
                    <h2 class="fw-bold mb-0 text-dark"><?php echo $userCount; ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card glass-card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold text-primary mb-0"><i class="bi bi-lightning-charge me-2"></i>Accesos Rápidos</h5>
            </div>
            <div class="card-body p-4">
                <div class="d-grid gap-3">
                    <a href="<?php echo BASE_URL; ?>admin/agencies/create"
                        class="btn btn-primary btn-lg text-start shadow-sm hover-scale">
                        <i class="bi bi-plus-circle me-2"></i>Registrar Nueva Agencia
                    </a>
                    <a href="<?php echo BASE_URL; ?>admin/users"
                        class="btn btn-light btn-lg text-start border shadow-sm hover-scale text-dark">
                        <i class="bi bi-people me-2 text-primary"></i>Gestionar Usuarios
                    </a>
                    <a href="<?php echo BASE_URL; ?>admin/agencies"
                        class="btn btn-light btn-lg text-start border shadow-sm hover-scale text-dark">
                        <i class="bi bi-list-ul me-2 text-primary"></i>Ver Listado de Agencias
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-lg-8">
        <div class="card glass-card border-0 shadow-sm h-100">
            <div
                class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-primary mb-0"><i class="bi bi-clock-history me-2"></i>Actividad Reciente</h5>
                <small class="text-muted">Últimas 5 agencias registradas</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Agencia</th>
                                <th>Dueño</th>
                                <th>Plan</th>
                                <th class="text-end pe-4">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentAgencies)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No hay actividad reciente.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentAgencies as $agency): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($agency['nombre']); ?>
                                            </div>
                                            <small class="text-muted">ID: #<?php echo $agency['id']; ?></small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs bg-secondary bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 24px; height: 24px;">
                                                    <i class="bi bi-person text-secondary" style="font-size: 0.7rem;"></i>
                                                </div>
                                                <span
                                                    class="small"><?php echo htmlspecialchars($agency['dueno_nombre'] . ' ' . $agency['dueno_apellido']); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $planClass = match ($agency['tipo_suscripcion']) {
                                                'premium' => 'text-warning',
                                                'basico' => 'text-primary',
                                                default => 'text-secondary'
                                            };
                                            ?>
                                            <span class="badge bg-light border <?php echo $planClass; ?>">
                                                <?php echo ucfirst($agency['tipo_suscripcion']); ?>
                                            </span>
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
            <div class="card-footer bg-transparent border-0 text-center py-3">
                <a href="<?php echo BASE_URL; ?>admin/agencies" class="text-decoration-none small fw-bold">Ver todo el
                    historial <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-scale {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-scale:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    }

    .avatar-xs {
        width: 24px;
        height: 24px;
    }
</style>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
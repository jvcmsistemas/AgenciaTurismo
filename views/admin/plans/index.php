<?php
// Sistema_New/views/admin/plans/index.php
include BASE_PATH . '/views/layouts/header.php';
?>

<div class="row mb-4 animate-fade-in">
    <div class="col-md-6">
        <h2 class="text-white">💰 Planes de Suscripción</h2>
        <p class="text-muted">Gestiona los planes disponibles y sus características</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= BASE_URL ?>admin/plans/create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nuevo Plan
        </a>
    </div>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger animate-fade-in"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<div class="card animate-fade-in-up">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Orden</th>
                        <th>Plan</th>
                        <th>Precio</th>
                        <th>Duración</th>
                        <th>Límites (C/T/U)</th>
                        <th>Features</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($plans)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No hay planes registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($plans as $plan): ?>
                            <tr class="<?= $plan['destacado'] ? 'fw-bold' : '' ?>"
                                style="<?= $plan['destacado'] ? 'background-color: rgba(233, 69, 96, 0.05) !important;' : '' ?>">
                                <td><?= $plan['orden'] ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fw-bold"><?= htmlspecialchars($plan['nombre']) ?></div>
                                            <div class="small text-muted"><?= htmlspecialchars($plan['codigo']) ?></div>
                                        </div>
                                        <?php if ($plan['destacado']): ?>
                                            <span class="badge bg-warning text-dark ms-2">POPULAR</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($plan['precio'] == 0): ?>
                                        <span class="badge bg-success">Gratis</span>
                                    <?php else: ?>
                                        $<?= number_format($plan['precio'], 2) ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= $plan['duracionmeses'] ?> meses</td>
                                <td>
                                    <small title="Clientes / Tours / Usuarios">
                                        <i class="fas fa-users me-1"></i><?= $plan['limiteclientes'] ?? '∞' ?> /
                                        <i class="fas fa-map-marked-alt me-1"></i><?= $plan['limitetours'] ?? '∞' ?> /
                                        <i class="fas fa-user-shield me-1"></i><?= $plan['limiteusuarios'] ?? '∞' ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <?php if ($plan['incluye_api']): ?>
                                            <span class="badge bg-info" title="API Include">API</span>
                                        <?php endif; ?>
                                        <?php if ($plan['incluye_integraciones']): ?>
                                            <span class="badge bg-primary" title="Integraciones">INT</span>
                                        <?php endif; ?>
                                        <?php if ($plan['incluye_soporte_premium']): ?>
                                            <span class="badge bg-warning" title="Soporte Premium">SOP</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($plan['activo']): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <a href="<?= BASE_URL ?>admin/plans/edit?id=<?= $plan['id'] ?>"
                                        class="btn btn-sm btn-outline-info me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>admin/plans/delete?id=<?= $plan['id'] ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('¿Estás seguro de eliminar este plan? No se puede deshacer si tiene agencias asociadas.')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
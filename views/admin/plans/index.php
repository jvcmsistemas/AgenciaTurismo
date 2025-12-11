<?php
// Sistema_New/views/admin/plans/index.php
include BASE_PATH . '/views/layouts/header.php';
?>

<div class="row mb-4 animate-fade-in">
    <div class="col-md-6">
        <h2 class="mb-0 text-primary fw-bold">💰 Planes de Suscripción</h2>
        <p class="text-muted">Gestiona los planes disponibles y sus características</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= BASE_URL ?>admin/plans/create" class="btn btn-primary shadow-sm hover-scale">
            <i class="bi bi-plus-lg me-2"></i>Nuevo Plan
        </a>
    </div>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger animate-fade-in shadow-sm border-0">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($_GET['error']) ?>
    </div>
<?php endif; ?>

<div class="card glass-card border-0 animate-fade-in-up">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Orden</th>
                        <th>Plan</th>
                        <th>Precio</th>
                        <th>Duración</th>
                        <th>Límites (C/T/U)</th>
                        <th>Features</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
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
                                <td class="ps-4"><?= $plan['orden'] ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($plan['nombre']) ?></div>
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
                                        <span class="fw-bold text-success">$<?= number_format($plan['precio'], 2) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= $plan['duracionmeses'] ?> meses</span></td>
                                <td>
                                    <small title="Clientes / Tours / Usuarios" class="text-muted d-flex gap-3">
                                        <span><i class="bi bi-people me-1"></i><?= $plan['limiteclientes'] ?? '∞' ?></span>
                                        <span><i class="bi bi-map me-1"></i><?= $plan['limitetours'] ?? '∞' ?></span>
                                        <span><i class="bi bi-shield-lock me-1"></i><?= $plan['limiteusuarios'] ?? '∞' ?></span>
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <?php if ($plan['incluye_api']): ?>
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info" title="API Include">API</span>
                                        <?php endif; ?>
                                        <?php if ($plan['incluye_integraciones']): ?>
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary" title="Integraciones">INT</span>
                                        <?php endif; ?>
                                        <?php if ($plan['incluye_soporte_premium']): ?>
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning" title="Soporte Premium">SOP</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($plan['activo']): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary rounded-pill px-3">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= BASE_URL ?>admin/plans/edit?id=<?= $plan['id'] ?>"
                                        class="btn btn-sm btn-outline-primary border-0 me-1" title="Editar">
                                        <i class="bi bi-pencil-square fs-5"></i>
                                    </a>
                                    <!-- Use single quotes for onclick JS string to avoid syntax issues with newlines -->
                                    <a href="<?= BASE_URL ?>admin/plans/delete?id=<?= $plan['id'] ?>" 
                                        class="btn btn-sm btn-outline-danger border-0" 
                                        title="Eliminar"
                                        onclick="return confirm('¿Estás seguro de eliminar este plan? Esta acción no se puede deshacer.');">
                                        <i class="bi bi-trash fs-5"></i>
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
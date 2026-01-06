<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="container-fluid py-4 fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1">Gestión de Colaboradores</h2>
            <p class="text-muted small mb-0"><i class="bi bi-people me-1"></i> Administra el personal de tu agencia y
                sus accesos.</p>
        </div>
        <a href="<?php echo BASE_URL; ?>agency/users/create" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-person-plus me-2"></i>Nuevo Colaborador
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i> ¡Colaborador registrado exitosamente!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-info alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
            <i class="bi bi-info-circle me-2"></i> ¡Datos del colaborador actualizados!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card glass-card border-0 shadow-sm anim-slide-up">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Colaborador</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($collaborators)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No hay colaboradores registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($collaborators as $user): ?>
                            <tr>
                                <td class="ps-4 fw-bold">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <div>
                                            <?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?>
                                            <?php if ($user['rol'] === 'dueno_agencia'): ?>
                                                <span
                                                    class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill ms-2 small"
                                                    style="font-size: 0.65rem;">TÚ</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </td>
                                <td>
                                    <?php if ($user['rol'] === 'dueno_agencia'): ?>
                                        <span class="badge bg-dark rounded-pill px-3 small">Dueño</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary rounded-pill px-3 small">Empleado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user['es_activo'] ?? 1): ?>
                                        <span class="badge bg-success rounded-pill px-3 small">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger rounded-pill px-3 small">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <?php if ($user['rol'] === 'empleado_agencia'): ?>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="<?php echo BASE_URL; ?>agency/users/edit?id=<?php echo $user['id']; ?>"
                                                class="btn btn-sm btn-light text-primary rounded-circle shadow-sm"
                                                title="Editar colaborador">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="<?php echo BASE_URL; ?>agency/users/delete?id=<?php echo $user['id']; ?>"
                                                class="btn btn-sm btn-light text-danger rounded-circle shadow-sm"
                                                title="Eliminar acceso"
                                                onclick="return confirm('¿Está seguro de revocar el acceso a este colaborador?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small italic">Sin acciones</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 p-3 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-4 anim-fade-in">
        <h6 class="fw-bold text-primary mb-2"><i class="bi bi-shield-lock me-2"></i>Seguridad de Roles</h6>
        <p class="text-muted small mb-0">
            Los <strong>Empleados</strong> pueden gestionar Guías, Transportes y Reservas, pero tienen restringido el
            acceso a facturación y la eliminación de registros críticos.
        </p>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
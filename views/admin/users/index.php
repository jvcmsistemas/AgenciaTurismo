<?php include BASE_PATH . '/views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary fw-bold mb-0">Auditoría de Usuarios</h2>
            <p class="text-muted mb-0">Listado global de todos los usuarios registrados en el sistema.</p>
        </div>
        <div class="d-flex gap-2">
            <!-- Aquí podrían ir filtros o exportar en el futuro -->
            <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                <i class="bi bi-printer me-2"></i>Imprimir
            </button>
        </div>
    </div>

    <div class="glass-card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="text-secondary small fw-bold">USUARIO</th>
                        <th scope="col" class="text-secondary small fw-bold">ROL</th>
                        <th scope="col" class="text-secondary small fw-bold">AGENCIA</th>
                        <th scope="col" class="text-secondary small fw-bold">REGISTRADO</th>
                        <th scope="col" class="text-secondary small fw-bold text-end">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary-subtle text-primary me-3">
                                        <?php echo strtoupper(substr($user['nombre'], 0, 1) . substr($user['apellido'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">
                                            <?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?></div>
                                        <div class="small text-muted"><?php echo htmlspecialchars($user['email']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php
                                $badgeClass = 'bg-secondary';
                                $rolName = 'Desconocido';

                                switch ($user['rol']) {
                                    case 'administrador_general':
                                        $badgeClass = 'bg-primary';
                                        $rolName = 'Super Admin';
                                        break;
                                    case 'dueno_agencia':
                                        $badgeClass = 'bg-success';
                                        $rolName = 'Dueño Agencia';
                                        break;
                                    case 'empleado_agencia':
                                        $badgeClass = 'bg-info';
                                        $rolName = 'Empleado';
                                        break;
                                }
                                ?>
                                <span class="badge <?php echo $badgeClass; ?> rounded-pill px-3 py-2">
                                    <?php echo $rolName; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($user['nombre_agencia']): ?>
                                    <span class="text-dark fw-medium"><i
                                            class="bi bi-building me-1 text-muted"></i><?php echo htmlspecialchars($user['nombre_agencia']); ?></span>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <?php echo date('d/m/Y', strtotime($user['created_at'])); ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <?php if ($user['rol'] === 'dueno_agencia' && $user['agencia_id']): ?>
                                    <a href="<?php echo BASE_URL; ?>admin/agencies/edit?id=<?php echo $user['agencia_id']; ?>"
                                        class="btn btn-sm btn-outline-primary" title="Ir a Agencia">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
    }
</style>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
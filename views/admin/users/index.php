<?php include BASE_PATH . '/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-primary mb-1">Auditoría de Usuarios</h2>
        <p class="text-muted mb-0">Gestión y monitoreo de todos los usuarios del sistema.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="window.print()">
            <i class="bi bi-printer me-2"></i>Imprimir
        </button>
    </div>
</div>

<div class="card glass-card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="" method="GET" class="row g-3 align-items-center">
            <!-- Buscador -->
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0"
                        placeholder="Buscar por nombre o email..."
                        value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
            </div>

            <!-- Filtro por Agencia -->
            <div class="col-md-3">
                <select name="agency_id" class="form-select bg-light" onchange="this.form.submit()">
                    <option value="">🏢 Todas las Agencias</option>
                    <?php foreach ($agencies as $agency): ?>
                        <option value="<?php echo $agency['id']; ?>" <?php echo ($_GET['agency_id'] ?? '') == $agency['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($agency['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Ordenamiento -->
            <div class="col-md-3">
                <select name="sort" class="form-select bg-light" onchange="this.form.submit()">
                    <option value="created_at DESC" <?php echo ($_GET['sort'] ?? '') === 'created_at DESC' ? 'selected' : ''; ?>>📅 Registro (Reciente)</option>
                    <option value="created_at ASC" <?php echo ($_GET['sort'] ?? '') === 'created_at ASC' ? 'selected' : ''; ?>>📅 Registro (Antiguo)</option>
                    <option value="nombre ASC" <?php echo ($_GET['sort'] ?? '') === 'nombre ASC' ? 'selected' : ''; ?>>🔤
                        Nombre (A-Z)</option>
                    <option value="rol ASC" <?php echo ($_GET['sort'] ?? '') === 'rol ASC' ? 'selected' : ''; ?>>👤 Rol
                    </option>
                </select>
            </div>

            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary rounded-pill">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="card glass-card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Usuario</th>
                        <th class="py-3">Rol</th>
                        <th class="py-3">Agencia Asignada</th>
                        <th class="py-3">Registrado</th>
                        <th class="text-end pe-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x fs-1 d-block mb-2"></i>
                                No se encontraron usuarios con esos filtros.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-3">
                                            <?php echo strtoupper(substr($user['nombre'], 0, 1) . substr($user['apellido'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">
                                                <?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?>
                                            </h6>
                                            <small class="text-muted"><?php echo htmlspecialchars($user['email']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $badgeClass = 'bg-secondary';
                                    $rolName = 'Desconocido';

                                    switch ($user['rol']) {
                                        case 'administrador_general':
                                            $badgeClass = 'bg-dark text-white';
                                            $rolName = 'Super Admin';
                                            break;
                                        case 'dueno_agencia':
                                            $badgeClass = 'bg-primary bg-opacity-10 text-primary';
                                            $rolName = 'Dueño Agencia';
                                            break;
                                        case 'empleado_agencia':
                                            $badgeClass = 'bg-info bg-opacity-10 text-info';
                                            $rolName = 'Empleado';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?> rounded-pill px-3 py-2 border border-0">
                                        <?php echo $rolName; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($user['nombre_agencia']): ?>
                                        <div class="d-flex align-items-center text-dark fw-medium">
                                            <div class="bg-light rounded-circle p-1 me-2 d-flex align-items-center justify-content-center"
                                                style="width: 24px; height: 24px;">
                                                <i class="bi bi-building text-muted small"></i>
                                            </div>
                                            <?php echo htmlspecialchars($user['nombre_agencia']); ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic ms-2">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="text-muted small">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?php echo date('d/m/Y', strtotime($user['created_at'])); ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <?php if ($user['rol'] === 'dueno_agencia' && $user['agencia_id']): ?>
                                        <a href="<?php echo BASE_URL; ?>admin/agencies/edit?id=<?php echo $user['agencia_id']; ?>"
                                            class="btn btn-sm btn-light rounded-circle shadow-sm" title="Ver Agencia"
                                            data-bs-toggle="tooltip">
                                            <i class="bi bi-shop-window text-primary"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
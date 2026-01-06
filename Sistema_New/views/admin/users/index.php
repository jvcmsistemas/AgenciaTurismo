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
    <div class="users-container">
        <?php if (empty($users)): ?>
            <div class="card glass-card border-0 shadow-sm">
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-person-x fs-1 d-block mb-2"></i>
                    No se encontraron usuarios con esos filtros.
                </div>
            </div>
        <?php else:
            // Group users by agency
            $groupedUsers = [];
            foreach ($users as $user) {
                $agencyName = $user['nombre_agencia'] ?? 'SISTEMA / SUPER ADMIN';
                $groupedUsers[$agencyName][] = $user;
            }

            $colorPalette = ['#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#dc3545', '#fd7e14', '#ffc107', '#198754', '#20c997', '#0dcaf0'];
            $i = 0;

            foreach ($groupedUsers as $agencyName => $agencyUsers):
                $color = ($agencyName === 'SISTEMA / SUPER ADMIN') ? '#212529' : $colorPalette[$i % count($colorPalette)];
                $i++;
                ?>
                <div class="agency-group-card mb-4 animate-fade-in-up" style="--agency-color: <?= $color ?>;">
                    <div class="agency-group-header d-flex justify-content-between align-items-center p-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-building me-2"></i><?= htmlspecialchars($agencyName) ?>
                            <span class="badge bg-white bg-opacity-10 text-white rounded-pill ms-2 small"
                                style="font-size: 0.7rem;">
                                <?= count($agencyUsers) ?> usuarios
                            </span>
                        </h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 bg-surface-dynamic">
                            <thead>
                                <tr class="bg-light opacity-50">
                                    <th class="ps-4 py-2 small text-uppercase">Usuario</th>
                                    <th class="py-2 small text-uppercase">Rol</th>
                                    <th class="py-2 small text-uppercase">Registrado</th>
                                    <th class="text-end pe-4 py-2 small text-uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($agencyUsers as $user): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle-sm me-3"
                                                    style="background-color: <?= $color ?>33; color: <?= $color ?>;">
                                                    <?php echo strtoupper(substr($user['nombre'], 0, 1) . substr($user['apellido'], 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">
                                                        <?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?>
                                                    </h6>
                                                    <small
                                                        class="text-muted"><?php echo htmlspecialchars($user['email']); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $badgeStyle = 'background: #6c757d22; color: #6c757d;';
                                            $rolName = 'Desconocido';

                                            switch ($user['rol']) {
                                                case 'administrador_general':
                                                    $badgeStyle = 'background: #21252922; color: #212529;';
                                                    $rolName = 'Super Admin';
                                                    break;
                                                case 'dueno_agencia':
                                                    $badgeStyle = 'background: ' . $color . '22; color: ' . $color . ';';
                                                    $rolName = 'Dueño';
                                                    break;
                                                case 'empleado_agencia':
                                                    $badgeStyle = 'background: ' . $color . '11; color: ' . $color . ';';
                                                    $rolName = 'Empleado';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge rounded-pill px-3 py-1" style="<?= $badgeStyle ?>">
                                                <?php echo $rolName; ?>
                                            </span>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</div>

<style>
    .agency-group-card {
        background: var(--bg-secondary);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border-left: 6px solid var(--agency-color) !important;
        transition: transform 0.2s ease;
    }

    .agency-group-card:hover {
        transform: translateX(5px);
    }

    .agency-group-header {
        background: var(--agency-color);
        color: white;
    }

    .avatar-circle-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.75rem;
    }

    .bg-surface-dynamic {
        background-color: var(--bg-secondary) !important;
    }
</style>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
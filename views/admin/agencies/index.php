<?php include BASE_PATH . '/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary">Gestión de Agencias</h2>
    <a href="<?php echo BASE_URL; ?>admin/agencies/create" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-lg me-2"></i>Nueva Agencia
    </a>
</div>

<div class="card glass-card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="" method="GET" class="row g-3 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0"
                        placeholder="Buscar por nombre o email..."
                        value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <select name="sort" class="form-select bg-light" onchange="this.form.submit()">
                    <option value="fecha_vencimiento ASC" <?php echo ($_GET['sort'] ?? '') === 'fecha_vencimiento ASC' ? 'selected' : ''; ?>>📅 Vencimiento (Más próximo)</option>
                    <option value="fecha_vencimiento DESC" <?php echo ($_GET['sort'] ?? '') === 'fecha_vencimiento DESC' ? 'selected' : ''; ?>>📅 Vencimiento (Más lejano)</option>
                    <option value="id DESC" <?php echo ($_GET['sort'] ?? '') === 'id DESC' ? 'selected' : ''; ?>>🆕
                        Recientes</option>
                    <option value="nombre ASC" <?php echo ($_GET['sort'] ?? '') === 'nombre ASC' ? 'selected' : ''; ?>>🔤
                        Nombre (A-Z)</option>
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-outline-primary">Filtrar</button>
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
                        <th class="ps-4 py-3">Agencia</th>
                        <th class="py-3">Dueño</th>
                        <th class="py-3">Suscripción</th>
                        <th class="py-3">Vencimiento</th>
                        <th class="py-3">Estado</th>
                        <th class="text-end pe-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($agencies)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No hay agencias registradas aún.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($agencies as $agency): ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-initial rounded-circle bg-primary text-white me-3 d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <?php echo strtoupper(substr($agency['nombre'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">
                                                <?php echo htmlspecialchars($agency['nombre'] ?? ''); ?>
                                            </h6>
                                            <small class="text-muted">ID: #<?php echo $agency['id']; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <h6 class="mb-0 text-dark">
                                        <?php echo htmlspecialchars(($agency['dueno_nombre'] ?? '') . ' ' . ($agency['dueno_apellido'] ?? '')); ?>
                                    </h6>
                                    <small
                                        class="text-muted"><?php echo htmlspecialchars($agency['dueno_email'] ?? 'Sin email'); ?></small>
                                </td>
                                <td class="py-3">
                                    <?php
                                    $planColors = [
                                        'prueba' => 'info',
                                        'basico' => 'primary',
                                        'premium' => 'warning'
                                    ];
                                    $planColor = $planColors[$agency['tipo_suscripcion'] ?? ''] ?? 'secondary';
                                    ?>
                                    <span class="badge rounded-pill px-3 text-uppercase <?php
                                    echo $agency['tipo_suscripcion'] === 'basico'
                                        ? 'bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25'
                                        : 'bg-' . $planColor . ' bg-opacity-10 text-' . $planColor;
                                    ?>">
                                        <?php echo htmlspecialchars($agency['tipo_suscripcion'] ?? 'Sin Plan'); ?>
                                    </span>
                                </td>
                                <td class="py-3">
                                    <?php
                                    if (!empty($agency['fecha_vencimiento'])) {
                                        $vencimiento = new DateTime($agency['fecha_vencimiento']);
                                        $hoy = new DateTime();
                                        $diasRestantes = $hoy->diff($vencimiento)->format('%r%a');
                                        $textClass = 'text-muted';

                                        if ($diasRestantes < 0) {
                                            $textClass = 'text-danger fw-bold'; // Vencido
                                        } elseif ($diasRestantes < 7) {
                                            $textClass = 'text-warning fw-bold'; // Por vencer
                                        }
                                        ?>
                                        <div class="<?php echo $textClass; ?>">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            <?php echo $vencimiento->format('d/m/Y'); ?>
                                        </div>
                                        <?php if ($diasRestantes < 0): ?>
                                            <small class="text-danger">Vencido</small>
                                        <?php elseif ($diasRestantes < 30): ?>
                                            <small class="text-muted"><?php echo $diasRestantes; ?> días</small>
                                        <?php endif; ?>
                                    <?php } else { ?>
                                        <span class="text-muted">-</span>
                                    <?php } ?>
                                </td>
                                <td class="py-3">
                                    <?php if ($agency['estado'] === 'activa'): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Activa</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Inactiva</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4 py-3">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light rounded-circle" type="button"
                                            data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                            <li><a class="dropdown-item"
                                                    href="<?php echo BASE_URL; ?>admin/agencies/edit?id=<?php echo $agency['id']; ?>"><i
                                                        class="bi bi-pencil me-2"></i>Editar</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="<?php echo BASE_URL; ?>admin/agencies/toggle_status" method="POST"
                                                    class="d-inline">
                                                    <input type="hidden" name="id" value="<?php echo $agency['id']; ?>">
                                                    <input type="hidden" name="status"
                                                        value="<?php echo $agency['estado'] === 'activa' ? 'inactiva' : 'activa'; ?>">
                                                    <button type="submit"
                                                        class="dropdown-item <?php echo $agency['estado'] === 'activa' ? 'text-danger' : 'text-success'; ?>">
                                                        <i class="bi bi-power me-2"></i>
                                                        <?php echo $agency['estado'] === 'activa' ? 'Desactivar' : 'Activar'; ?>
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

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
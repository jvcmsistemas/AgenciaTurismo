<?php
// Sistema_New/views/admin/security/index.php
include BASE_PATH . '/views/layouts/header.php';
?>

<div class="row mb-4 animate-fade-in">
    <div class="col-md-6">
        <h2 class="mb-0">🛡️ Seguridad & Auditoría</h2>
        <p class="text-muted">Monitoreo de accesos, permisos y eventos críticos</p>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-outline-danger">
            <i class="fas fa-ban me-2"></i>Bloquear IPs Sospechosas
        </button>
    </div>
</div>

<!-- KPIs -->
<div class="row mb-4 animate-fade-in-up">
    <!-- Logins Today -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Logins Hoy</h6>
                        <h3 class="mb-0 fw-bold"><?= $totalLoginsToday ?></h3>
                    </div>
                    <i class="fas fa-sign-in-alt fa-2x text-primary opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Failed Attempts -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Intentos Fallidos</h6>
                        <h3 class="mb-0 fw-bold text-danger"><?= $totalFailedToday ?></h3>
                    </div>
                    <i class="fas fa-exclamation-triangle fa-2x text-danger opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Active Sessions -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Sesiones Activas</h6>
                        <h3 class="mb-0 fw-bold text-success"><?= $activeSessions ?></h3>
                    </div>
                    <i class="fas fa-users fa-2x text-success opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Access Logs -->
    <div class="col-lg-7 mb-4">
        <div class="card h-100">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Logs de Acceso (Recientes)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Evento</th>
                                <th>IP</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No hay logs registrados</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td><small><?= date('d/m/Y H:i:s', strtotime($log['fecha_hora'])) ?></small></td>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($log['usuario'] ?? 'Sistema') ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($log['email']) ?></small>
                                        </td>
                                        <td><?= ucfirst(str_replace('_', ' ', $log['tipo_evento'])) ?></td>
                                        <td><small class="text-monospace"><?= $log['direccion_ip'] ?></small></td>
                                        <td>
                                            <?php if ($log['resultado'] === 'exitoso'): ?>
                                                <span class="badge bg-success">Exitoso</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Fallido</span>
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
    </div>

    <!-- Audit Logs -->
    <div class="col-lg-5 mb-4">
        <div class="card h-100">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Auditoría de Cambios</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php if (empty($audits)): ?>
                        <li class="list-group-item text-center text-muted py-4">No hay cambios registrados</li>
                    <?php else: ?>
                        <?php foreach ($audits as $audit): ?>
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold"><?= ucfirst($audit['tipo_operacion']) ?> en <?= $audit['tabla'] ?>
                                        </div>
                                        <small class="text-muted">Por: <?= htmlspecialchars($audit['usuario']) ?></small>
                                    </div>
                                    <small class="text-muted"><?= date('H:i', strtotime($audit['fecha_hora'])) ?></small>
                                </div>
                                <div class="mt-1">
                                    <?php if ($audit['razon_cambio']): ?>
                                        <small
                                            class="d-block text-secondary">"<?= htmlspecialchars($audit['razon_cambio']) ?>"</small>
                                    <?php endif; ?>
                                    <small class="text-monospace text-primary">ID Recurso: <?= $audit['id_recurso'] ?></small>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
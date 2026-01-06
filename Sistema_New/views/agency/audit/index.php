<?php
// Sistema_New/views/agency/audit/index.php
include BASE_PATH . '/views/layouts/header_agency.php';
?>

<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold mb-0 text-dynamic">
                <i class="bi bi-activity me-2 text-primary"></i>Registro de Actividad y Auditoría
            </h2>
            <p class="text-muted-dynamic mb-0">Monitoreo de accesos y cambios en los datos de la agencia.</p>
        </div>
    </div>

    <!-- Tabs para alternar entre Accesos y Actividad -->
    <div class="card glass-card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-0 p-3">
            <ul class="nav nav-pills nav-fill gap-2" id="auditTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill shadow-sm py-2" id="access-tab" data-bs-toggle="tab"
                        data-bs-target="#access" type="button" role="tab" aria-controls="access" aria-selected="true">
                        <i class="bi bi-shield-lock me-2"></i>Logs de Acceso
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill shadow-sm py-2" id="activity-tab" data-bs-toggle="tab"
                        data-bs-target="#activity" type="button" role="tab" aria-controls="activity"
                        aria-selected="false">
                        <i class="bi bi-pencil-square me-2"></i>Cambios en Datos
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="auditTabsContent">
        <!-- LOGS DE ACCESO -->
        <div class="tab-pane fade show active" id="access" role="tabpanel" aria-labelledby="access-tab">
            <div class="card glass-card border-0 shadow-sm overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-soft-dynamic border-dynamic">
                            <tr>
                                <th class="ps-4 py-3 border-dynamic">Usuario</th>
                                <th class="py-3 border-dynamic">Evento</th>
                                <th class="py-3 border-dynamic">IP / Navegador</th>
                                <th class="py-3 border-dynamic">Fecha y Hora</th>
                                <th class="py-3 border-dynamic text-center pe-4">Resultado</th>
                            </tr>
                        </thead>
                        <tbody class="border-dynamic">
                            <?php if (empty($accessLogs)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">No hay registros de acceso
                                        recientes.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($accessLogs as $log): ?>
                                    <tr class="border-dynamic">
                                        <td class="ps-4">
                                            <div class="fw-bold text-dynamic">
                                                <?php echo htmlspecialchars($log['nombre'] . ' ' . $log['apellido']); ?>
                                            </div>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars($log['email']); ?> (
                                                <?php echo htmlspecialchars($log['rol']); ?>)
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-info text-info border border-info-subtle small">
                                                <?php echo strtoupper(str_replace('_', ' ', $log['accion'])); ?>
                                            </span>
                                            <div class="small text-muted mt-1">
                                                <?php echo htmlspecialchars($log['recurso']); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small fw-bold">
                                                <?php echo htmlspecialchars($log['ip_origen']); ?>
                                            </div>
                                            <small class="text-muted text-truncate d-inline-block" style="max-width: 200px;"
                                                title="<?php echo htmlspecialchars($log['user_agent']); ?>">
                                                <?php echo htmlspecialchars($log['user_agent']); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <?php echo date('d/m/Y', strtotime($log['fecha_hora'])); ?>
                                            </div>
                                            <div class="small text-muted">
                                                <?php echo date('H:i:s', strtotime($log['fecha_hora'])); ?>
                                            </div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <?php if ($log['estado'] === 'exitoso'): ?>
                                                <span class="badge bg-success shadow-sm">EXITOSO</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger shadow-sm">FALLIDO</span>
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

        <!-- LOGS DE ACTIVIDAD (CAMBIOS) -->
        <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
            <div class="card glass-card border-0 shadow-sm overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-soft-dynamic border-dynamic">
                            <tr>
                                <th class="ps-4 py-3 border-dynamic">Usuario</th>
                                <th class="py-3 border-dynamic">Entidad / Recurso</th>
                                <th class="py-3 border-dynamic">Operación</th>
                                <th class="py-3 border-dynamic">Razón / Comentario</th>
                                <th class="py-3 border-dynamic pe-4">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="border-dynamic">
                            <?php if (empty($activityLogs)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">No hay registros de cambios
                                        recientes.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($activityLogs as $log): ?>
                                    <tr class="border-dynamic">
                                        <td class="ps-4">
                                            <div class="fw-bold text-dynamic">
                                                <?php echo htmlspecialchars($log['usuario_nombre'] . ' ' . $log['usuario_apellido']); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-primary">
                                                <i class="bi bi-database me-1"></i>
                                                <?php echo strtoupper($log['tabla_afectada']); ?>
                                            </div>
                                            <small class="text-muted">ID Recurso:
                                                <?php echo $log['registro_id']; ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php
                                            $opClass = 'bg-info';
                                            if ($log['operacion'] === 'INSERT')
                                                $opClass = 'bg-success';
                                            if ($log['operacion'] === 'DELETE')
                                                $opClass = 'bg-danger';
                                            if ($log['operacion'] === 'UPDATE')
                                                $opClass = 'bg-warning text-dark';
                                            ?>
                                            <span class="badge <?php echo $opClass; ?> shadow-sm">
                                                <?php echo strtoupper($log['operacion']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <?php echo htmlspecialchars($log['motivo'] ?: 'N/A'); ?>
                                            </div>
                                            <?php if ($log['valor_anterior'] || $log['valor_nuevo']): ?>
                                                <button class="btn btn-sm btn-link p-0 mt-1 show-details"
                                                    data-id="<?php echo $log['id']; ?>">Ver detalle técnico</button>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-4">
                                            <div class="small">
                                                <?php echo date('d/m/Y', strtotime($log['fecha_hora'])); ?>
                                            </div>
                                            <div class="small text-muted">
                                                <?php echo date('H:i:s', strtotime($log['fecha_hora'])); ?>
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
</div>

<style>
    .bg-soft-dynamic {
        background-color: var(--bg-soft);
    }

    .border-dynamic {
        border-color: var(--border-color) !important;
    }

    .nav-pills .nav-link {
        color: var(--text-muted);
        background: var(--bg-card);
        transition: all 0.3s ease;
    }

    .nav-pills .nav-link.active {
        background: var(--primary-color) !important;
        color: white !important;
    }

    .bg-soft-info {
        background-color: rgba(13, 202, 240, 0.1);
    }
</style>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
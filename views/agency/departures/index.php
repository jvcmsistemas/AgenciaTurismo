<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<style>
    .glass-card-premium {
        background: var(--glass-bg, rgba(255, 255, 255, 0.05));
        backdrop-filter: blur(var(--glass-blur, 12px));
        -webkit-backdrop-filter: blur(var(--glass-blur, 12px));
        border: 1px solid var(--glass-border, rgba(255, 255, 255, 0.1));
        border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .dep-table thead th {
        background: rgba(16, 185, 129, 0.05);
        color: var(--brand-primary);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1.25rem 1rem;
        border: none;
    }

    .dep-table tbody td {
        padding: 1.25rem 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        vertical-align: middle;
        color: var(--text-primary);
    }

    .date-badge-box {
        width: 60px;
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-radius: 12px;
        padding: 8px;
        text-align: center;
    }

    .status-pill {
        padding: 0.4em 1em;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.7rem;
        border: 1px solid transparent;
    }

    .status-programada {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border-color: rgba(16, 185, 129, 0.2);
    }

    .status-confirmada {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border-color: rgba(59, 130, 246, 0.2);
    }

    .status-cerrada {
        background: rgba(100, 116, 139, 0.1);
        color: #64748b;
        border-color: rgba(100, 116, 139, 0.2);
    }

    .status-cancelada {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border-color: rgba(239, 68, 68, 0.2);
    }

    .search-container-glass {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 15px;
        padding: 5px 15px;
        transition: all 0.3s ease;
    }

    .action-btn-glass {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--text-muted);
        transition: all 0.2s ease;
    }

    .action-btn-glass:hover {
        background: var(--brand-primary);
        color: white !important;
        transform: translateY(-2px);
    }

    .btn-emerald-premium {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white !important;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        text-decoration: none;
    }

    .btn-emerald-premium:hover {
        background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        color: white !important;
    }

    .occupancy-bar {
        height: 6px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        overflow: hidden;
    }

    .agency-light-theme .glass-card-premium {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(0, 0, 0, 0.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    .agency-light-theme .occupancy-bar {
        background: rgba(0, 0, 0, 0.05);
    }
</style>

<div class="container-fluid py-4 fade-in">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-gradient-emerald mb-1">Programación de Salidas</h2>
            <p class="text-muted small mb-0"><i class="bi bi-calendar-event me-1"></i> Control de frecuencias y
                ocupación</p>
        </div>
        <a href="<?php echo BASE_URL; ?>agency/departures/create" class="btn btn-emerald-premium">
            <i class="bi bi-calendar-plus me-2"></i>Nueva Salida
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="glass-card-premium mb-4 p-3 anim-slide-up">
        <form action="<?php echo BASE_URL; ?>agency/departures" method="GET" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="search-container-glass d-flex align-items-center">
                    <i class="bi bi-search text-muted me-2"></i>
                    <input type="text" name="search" class="form-control bg-transparent border-0 shadow-none"
                        placeholder="Tour, guía o placa..."
                        value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                        style="color: var(--text-primary) !important;">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select bg-transparent border-0 text-muted small"
                    onchange="this.form.submit()"
                    style="color: var(--text-primary) !important; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08) !important; border-radius: 12px;">
                    <option value="">Todos los Estados</option>
                    <option value="programada" <?php echo (($_GET['status'] ?? '') === 'programada') ? 'selected' : ''; ?>>Programada</option>
                    <option value="confirmada" <?php echo (($_GET['status'] ?? '') === 'confirmada') ? 'selected' : ''; ?>>Confirmada</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="sort" class="form-select bg-transparent border-0 text-muted small"
                    onchange="this.form.submit()"
                    style="color: var(--text-primary) !important; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08) !important; border-radius: 12px;">
                    <option value="fecha_asc" <?php echo (($_GET['sort'] ?? '') === 'fecha_asc') ? 'selected' : ''; ?>>
                        Próximas primero</option>
                    <option value="fecha_desc" <?php echo (($_GET['sort'] ?? '') === 'fecha_desc') ? 'selected' : ''; ?>>
                        Últimas primero</option>
                </select>
            </div>
            <div class="col-auto">
                <a href="<?php echo BASE_URL; ?>agency/departures"
                    class="btn btn-sm btn-outline-secondary rounded-pill px-3">Limpiar</a>
            </div>
        </form>
    </div>

    <!-- Main Table -->
    <div class="glass-card-premium anim-slide-up" style="animation-delay: 0.1s;">
        <div class="table-responsive">
            <table class="table dep-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Fecha & Hora</th>
                        <th>Tour / Experiencia</th>
                        <th>Recursos Asignados</th>
                        <th>Ocupación</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Gestión</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($departures)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="opacity-20 mb-3"><i class="bi bi-calendar-x" style="font-size: 4rem;"></i></div>
                                <h5 class="fw-bold text-muted">No hay salidas programadas</h5>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($departures as $departure): ?>
                            <?php
                            $statusClass = "status-" . $departure['estado'];
                            $ocupacion = 100 - (($departure['cupos_disponibles'] / $departure['cupos_totales']) * 100);
                            $occupancyColor = $ocupacion > 90 ? '#ef4444' : ($ocupacion > 50 ? '#f59e0b' : '#10b981');
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="date-badge-box me-3">
                                            <div class="fw-bold text-primary fs-5">
                                                <?php echo date('d', strtotime($departure['fecha_salida'])); ?>
                                            </div>
                                            <div class="text-muted small text-uppercase">
                                                <?php echo date('M', strtotime($departure['fecha_salida'])); ?>
                                            </div>
                                        </div>
                                        <div class="text-muted small">
                                            <i
                                                class="bi bi-clock me-1"></i><?php echo date('h:i A', strtotime($departure['hora_salida'])); ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold mb-1" style="color: var(--text-primary);">
                                        <?php echo htmlspecialchars($departure['tour_nombre']); ?>
                                    </div>
                                    <div class="small text-muted">
                                        <i
                                            class="bi bi-geo-alt me-1 text-brand"></i><?php echo htmlspecialchars($departure['ubicacion']); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="small" style="color: var(--text-secondary);">
                                            <i
                                                class="bi bi-person-badge me-2 text-brand"></i><?php echo htmlspecialchars($departure['guia_nombre'] ?? 'Sin Guía'); ?>
                                        </span>
                                        <span class="small" style="color: var(--text-secondary);">
                                            <i
                                                class="bi bi-bus-front me-2 text-primary"></i><?php echo htmlspecialchars($departure['transporte_placa'] ?? 'Sin Vehículo'); ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-between mb-1 small">
                                        <span class="fw-bold"
                                            style="color: var(--text-primary);"><?php echo $departure['cupos_disponibles']; ?>
                                            libres</span>
                                        <span class="text-muted">Total: <?php echo $departure['cupos_totales']; ?></span>
                                    </div>
                                    <div class="occupancy-bar">
                                        <div class="h-100"
                                            style="width: <?php echo $ocupacion; ?>%; background: <?php echo $occupancyColor; ?>; transition: width 1s ease;">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-pill <?php echo $statusClass; ?>">
                                        <?php echo ucfirst($departure['estado']); ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?php echo BASE_URL; ?>agency/departures/edit?id=<?php echo $departure['id']; ?>"
                                            class="action-btn-glass" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>agency/departures/delete?id=<?php echo $departure['id']; ?>"
                                            class="action-btn-glass text-danger"
                                            onclick="return confirm('¿Eliminar esta salida?');">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <?php if ($totalPages > 1): ?>
            <div class="p-3 border-top border-dynamic d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Mostrando página <strong><?php echo $page; ?></strong> de <?php echo $totalPages; ?>
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link bg-transparent border-dynamic text-primary"
                                href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&sort=<?php echo urlencode($sort); ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                <a class="page-link <?php echo $page == $i ? 'bg-primary border-primary' : 'bg-transparent border-dynamic text-primary'; ?>"
                                    href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&sort=<?php echo urlencode($sort); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link bg-transparent border-dynamic text-primary"
                                href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&sort=<?php echo urlencode($sort); ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
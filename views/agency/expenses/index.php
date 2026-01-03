<?php
// Sistema_New/views/agency/expenses/index.php
include BASE_PATH . '/views/layouts/header_agency.php';

// Cálculos para KPIs
$totalEgresos = array_sum(array_column($expenses, 'monto'));
$totalPendiente = array_sum(array_map(function ($ex) {
    return ($ex['estado'] === 'pendiente') ? $ex['monto'] : 0;
}, $expenses));

// Agrupar por categoría para el KPI de "Categoría Top"
$catTotals = [];
foreach ($expenses as $ex) {
    $catTotals[$ex['categoria']] = ($catTotals[$ex['categoria']] ?? 0) + $ex['monto'];
}
arsort($catTotals);
$topCategory = key($catTotals) ?: 'N/A';
$topCategoryAmount = current($catTotals) ?: 0;

// Helper para badges de estado
function getStatusBadgeExpenses($status)
{
    if ($status === 'pagado') {
        return '<span class="badge badge-pagado"><i class="bi bi-check-circle-fill me-1"></i>Pagado</span>';
    }
    return '<span class="badge badge-pendiente"><i class="bi bi-clock-history me-1"></i>Pendiente</span>';
}

// Helper para iconos de categoría premium
function getCategoryIcon($cat)
{
    switch ($cat) {
        case 'honorarios':
            return '<div class="icon-shape bg-soft-primary"><i class="bi bi-person-badge text-primary"></i></div>';
        case 'viaticos':
            return '<div class="icon-shape bg-soft-info"><i class="bi bi-truck text-info"></i></div>';
        case 'servicios':
            return '<div class="icon-shape bg-soft-warning"><i class="bi bi-building text-warning"></i></div>';
        case 'retiro_personal':
            return '<div class="icon-shape bg-soft-danger"><i class="bi bi-wallet2 text-danger"></i></div>';
        default:
            return '<div class="icon-shape bg-soft-secondary"><i class="bi bi-cash"></i></div>';
    }
}
?>

<div class="container-fluid py-4">
    <?php if (isset($_GET['success'])): ?>
        <script>
            window.addEventListener('load', () => {
                Swal.fire({
                    title: '¡Registrado!',
                    text: 'El egreso ha sido asentado correctamente.',
                    icon: 'success',
                    confirmButtonColor: '#ff416c'
                });
            });
        </script>
    <?php endif; ?>

    <?php if (isset($_GET['updated'])): ?>
        <script>
            window.addEventListener('load', () => {
                Swal.fire({
                    title: '¡Actualizado!',
                    text: 'El registro ha sido modificado correctamente.',
                    icon: 'success',
                    confirmButtonColor: '#0d6efd'
                });
            });
        </script>
    <?php endif; ?>

    <!-- Encabezado Premium -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <h1 class="display-6 fw-bold mb-0 text-dynamic animate__animated animate__fadeInDown">
                <span class="text-gradient-primary">Gestión de Egresos</span>
            </h1>
            <p class="text-muted-dynamic mb-0 fs-5">Control maestro de flujos de caja y costos operativos.</p>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-premium-danger rounded-pill shadow-lg px-4 py-2" data-bs-toggle="modal"
                data-bs-target="#modalNuevoGasto">
                <i class="bi bi-plus-circle-fill me-2"></i>Registrar Nuevo Egreso
            </button>
        </div>
    </div>

    <!-- Dashboard KPI -->
    <div class="row g-4 mb-5 animate__animated animate__fadeIn">
        <div class="col-md-3">
            <div class="card kpi-card bg-glass-blue border-0 h-100 overflow-hidden shadow">
                <div class="card-body p-4 position-relative">
                    <div class="kpi-icon"><i class="bi bi-cash-stack"></i></div>
                    <h6 class="text-uppercase fw-bold text-white-50 small mb-2">Total Egresos (Periodo)</h6>
                    <h2 class="fw-bold text-white mb-0"><?php echo formatCurrency($totalEgresos); ?></h2>
                    <div class="mt-3 fs-7 text-white-50"><i class="bi bi-graph-up me-1"></i>Basado en filtros activos
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card kpi-card bg-glass-orange border-0 h-100 overflow-hidden shadow">
                <div class="card-body p-4 position-relative">
                    <div class="kpi-icon"><i class="bi bi-clock-history"></i></div>
                    <h6 class="text-uppercase fw-bold text-white-50 small mb-2">Pendientes de Pago</h6>
                    <h2 class="fw-bold text-white mb-0"><?php echo formatCurrency($totalPendiente); ?></h2>
                    <div class="mt-3 fs-7 text-white-50 badge bg-white-10 text-orange rounded-pill">
                        <?php echo count(array_filter($expenses, fn($e) => $e['estado'] === 'pendiente')); ?> registros
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card kpi-card bg-glass-red border-0 h-100 overflow-hidden shadow">
                <div class="card-body p-4 position-relative">
                    <div class="kpi-icon"><i class="bi bi-person-fill-dash"></i></div>
                    <h6 class="text-uppercase fw-bold text-white-50 small mb-2">Retiros Personales</h6>
                    <h2 class="fw-bold text-white mb-0">
                        <?php echo formatCurrency($catTotals['retiro_personal'] ?? 0); ?>
                    </h2>
                    <div class="mt-3 fs-7 text-white-50">Privado para dueños</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card kpi-card bg-glass-purple border-0 h-100 overflow-hidden shadow">
                <div class="card-body p-4 position-relative">
                    <div class="kpi-icon"><i class="bi bi-star-fill"></i></div>
                    <h6 class="text-uppercase fw-bold text-white-50 small mb-2">Mayor Gasto en...</h6>
                    <h2 class="fw-bold text-white mb-0 text-capitalize">
                        <?php echo str_replace('_', ' ', $topCategory); ?>
                    </h2>
                    <div class="mt-3 fs-7 text-white-50"><?php echo formatCurrency($topCategoryAmount); ?> total</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Listado con Filtros Premium -->
    <div class="card glass-card border-0 shadow-lg animate__animated animate__fadeInUp">
        <div class="card-header bg-transparent border-0 p-4 pb-0">
            <div class="row g-3 align-items-center">
                <div class="col-md-8">
                    <h4 class="fw-bold text-dynamic mb-0">Listado de transacciones</h4>
                </div>
                <div class="col-md-4">
                    <div class="input-group search-group">
                        <span class="input-group-text bg-dynamic border-dynamic text-muted"><i
                                class="bi bi-search"></i></span>
                        <input type="text" id="expenseSearch"
                            class="form-control bg-dynamic border-dynamic text-dynamic"
                            placeholder="Buscar beneficiario o nota...">
                    </div>
                </div>
            </div>

            <form method="GET" action="" class="row g-3 mt-3 pb-4 border-bottom border-dynamic">
                <input type="hidden" name="path" value="agency/expenses">
                <div class="col-md-3">
                    <div class="filter-group">
                        <label class="form-label small fw-bold text-muted-dynamic">Periodo de Análisis</label>
                        <div class="d-flex gap-2">
                            <input type="date" name="start_date"
                                class="form-control form-control-sm bg-dynamic border-dynamic text-dynamic"
                                value="<?php echo $filters['start_date']; ?>">
                            <input type="date" name="end_date"
                                class="form-control form-control-sm bg-dynamic border-dynamic text-dynamic"
                                value="<?php echo $filters['end_date']; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted-dynamic">Categoría de Egreso</label>
                    <select name="categoria"
                        class="form-select form-select-sm bg-dynamic border-dynamic text-dynamic rounded-pill">
                        <option value="">Todas las categorías</option>
                        <option value="honorarios" <?php echo ($filters['categoria'] == 'honorarios') ? 'selected' : ''; ?>>Honorarios Staff</option>
                        <option value="viaticos" <?php echo ($filters['categoria'] == 'viaticos') ? 'selected' : ''; ?>>
                            Viáticos y Viaje</option>
                        <option value="servicios" <?php echo ($filters['categoria'] == 'servicios') ? 'selected' : ''; ?>>
                            Servicios de Oficina</option>
                        <option value="retiro_personal" <?php echo ($filters['categoria'] == 'retiro_personal') ? 'selected' : ''; ?>>Retiro Personal</option>
                        <option value="otros" <?php echo ($filters['categoria'] == 'otros') ? 'selected' : ''; ?>>Otros
                            Gastos</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted-dynamic">Estado de Pago</label>
                    <select name="estado"
                        class="form-select form-select-sm bg-dynamic border-dynamic text-dynamic rounded-pill">
                        <option value="">Cualquier estado</option>
                        <option value="pendiente" <?php echo ($filters['estado'] == 'pendiente') ? 'selected' : ''; ?>>
                            Solo Pendientes</option>
                        <option value="pagado" <?php echo ($filters['estado'] == 'pagado') ? 'selected' : ''; ?>>Solo
                            Pagados</option>
                    </select>
                </div>
                <div class="col-md-3 text-end align-self-end">
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm">
                        <i class="bi bi-funnel-fill me-2"></i>Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>

        <div class="table-responsive p-0">
            <table class="table table-hover align-middle mb-0" id="expensesTable">
                <thead class="bg-soft-dynamic-header">
                    <tr>
                        <th class="ps-4 py-3 border-0">Beneficiario / Concepto</th>
                        <th class="py-3 border-0">Vínculo</th>
                        <th class="py-3 border-0">Fecha Registro</th>
                        <th class="py-3 border-0 text-end">Importe</th>
                        <th class="py-3 border-0 text-center">Estado</th>
                        <th class="py-3 border-0 text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($expenses)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                <p class="text-muted">No se encontraron registros financieros para este periodo.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($expenses as $ex): ?>
                            <tr class="expense-row transition-all border-bottom-dynamic">
                                <td class="ps-4 py-4">
                                    <div class="d-flex align-items-center">
                                        <?php echo getCategoryIcon($ex['categoria']); ?>
                                        <div class="ms-3">
                                            <div class="fw-bold text-dynamic fs-6 mb-0">
                                                <?php echo htmlspecialchars($ex['beneficiario']); ?>
                                            </div>
                                            <div class="text-muted small d-flex align-items-center mt-1">
                                                <span class="badge bg-soft-secondary text-uppercase border-0 me-2"
                                                    style="font-size: 0.65rem; font-weight: 800;">
                                                    <?php echo str_replace('_', ' ', $ex['categoria']); ?>
                                                </span>
                                                <i class="bi bi-sticky me-1"></i>
                                                <span class="text-truncate"
                                                    style="max-width: 200px;"><?php echo htmlspecialchars($ex['notas'] ?: 'Sin notas descriptivas'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($ex['salida_id']): ?>
                                        <div
                                            class="departure-link p-2 bg-soft-primary rounded-3 text-primary d-inline-flex align-items-center small shadow-sm border border-primary border-opacity-10">
                                            <i class="bi bi-calendar-event me-2"></i>
                                            <div>
                                                <div class="fw-bold line-height-1" style="font-size: 0.75rem;">
                                                    <?php echo htmlspecialchars($ex['tour_nombre']); ?>
                                                </div>
                                                <small class="opacity-75"
                                                    style="font-size: 0.65rem;"><?php echo date('d M, Y', strtotime($ex['fecha_salida'])); ?></small>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small"><i class="bi bi-building me-1 fs-xs"></i>Gasto
                                            Operativo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="text-dynamic small fw-semibold">
                                        <i
                                            class="bi bi-calendar3 me-2 text-muted"></i><?php echo date('d/m/Y', strtotime($ex['fecha_gasto'])); ?>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold fs-5 text-danger">
                                        - <?php echo formatCurrency($ex['monto']); ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php echo getStatusBadgeExpenses($ex['estado']); ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <?php if ($ex['estado'] === 'pendiente'): ?>
                                            <a href="<?php echo BASE_URL; ?>agency/expenses/update-status?id=<?php echo $ex['id']; ?>&status=pagado"
                                                class="btn btn-icon-action bg-soft-success text-success" title="Marcar como pagado">
                                                <i class="bi bi-check-lg"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button class="btn btn-icon-action bg-soft-primary text-primary"
                                            onclick='openEditModal(<?php echo json_encode($ex); ?>)' title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-icon-action bg-soft-secondary text-muted"
                                            onclick="showInfo('<?php echo addslashes($ex['beneficiario']); ?>', '<?php echo addslashes($ex['notas'] ?: 'Sin notas adicionales'); ?>')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <a href="<?php echo BASE_URL; ?>agency/expenses/delete?id=<?php echo $ex['id']; ?>"
                                            class="btn btn-icon-action bg-soft-danger text-danger"
                                            onclick="return confirm('¿Está seguro de eliminar este registro financiero? Esta acción es irreversible.')">
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
    </div>
</div>

<!-- Modal Registro Premium -->
<div class="modal fade" id="modalNuevoGasto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content glass-card-modal border-0 overflow-hidden">
            <div class="modal-header border-0 p-4 pb-0">
                <div>
                    <h3 class="modal-title fw-bold text-dynamic"><i
                            class="bi bi-plus-circle-fill me-2 text-danger"></i>Nuevo Registro de Egreso</h3>
                    <p class="text-muted small mb-0">Complete la información para asentar el gasto en la caja de la
                        agencia.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="<?php echo BASE_URL; ?>agency/expenses/store" method="POST" class="needs-validation">
                    <?php echo csrf_field(); ?>
                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="form-label small fw-black text-uppercase text-muted">Beneficiario /
                                Destinatario</label>
                            <div class="input-group input-premium shadow-sm">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" name="beneficiario" class="form-control"
                                    placeholder="Ej: Pago a Guía, Luz de Local, etc." required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-black text-uppercase text-muted">Importe Total</label>
                            <div class="input-group input-premium shadow-sm">
                                <span class="input-group-text fw-bold">S/</span>
                                <input type="number" name="monto" step="0.01" class="form-control fw-bold"
                                    placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-black text-uppercase text-muted">Categoría
                                Financiera</label>
                            <div class="input-group input-premium shadow-sm">
                                <span class="input-group-text"><i class="bi bi-tags-fill"></i></span>
                                <select name="categoria" class="form-select bg-dynamic text-dynamic-force" required>
                                    <option value="honorarios" class="option-force">Honorarios Staff</option>
                                    <option value="viaticos" selected class="option-force">Viáticos / Operatividad
                                    </option>
                                    <option value="servicios" class="option-force">Servicios Generales</option>
                                    <option value="retiro_personal" class="option-force">Retiro Directo Dueño</option>
                                    <option value="otros" class="option-force">Otros Gastos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-black text-uppercase text-muted">Fecha del Gasto</label>
                            <div class="input-group input-premium shadow-sm">
                                <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                                <input type="date" name="fecha_gasto" class="form-control"
                                    value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-black text-uppercase text-muted">Vincular a Salida
                                Programada (Opcional)</label>
                            <div class="search-select-container">
                                <div class="input-group input-premium shadow-sm mb-2">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" id="departureSearchInput" class="form-control"
                                        placeholder="Escriba para buscar salida por destino o fecha...">
                                </div>
                                <input type="hidden" name="salida_id" id="selectedDepartureId">
                                <div id="selectedDepartureDisplay" class="d-none mb-2">
                                    <div
                                        class="departure-link p-2 bg-soft-primary rounded-3 text-primary d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                                            <div>
                                                <div class="fw-bold line-height-1" id="selectedDepartureTitle">--</div>
                                                <small class="opacity-75" id="selectedDepartureDate">--</small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm text-danger"
                                            onclick="clearDepartureSelection()">
                                            <i class="bi bi-x-circle-fill"></i> Quitar
                                        </button>
                                    </div>
                                </div>
                                <ul id="departureResultsList"
                                    class="list-group list-group-flush shadow border-dynamic rounded-3 overflow-auto d-none"
                                    style="max-height: 200px; z-index: 1060; position: absolute; width: calc(100% - 3rem);">
                                    <?php foreach ($activeDepartures as $sd): ?>
                                        <li class="list-group-item list-group-item-action border-dynamic py-2 px-3"
                                            style="cursor: pointer;" data-id="<?php echo $sd['id']; ?>"
                                            data-title="<?php echo htmlspecialchars($sd['tour_nombre']); ?>"
                                            data-date="<?php echo date('d M, Y', strtotime($sd['fecha_salida'])); ?>"
                                            onclick="selectDeparture(this)">
                                            <div class="fw-bold small"><?php echo htmlspecialchars($sd['tour_nombre']); ?>
                                            </div>
                                            <div class="text-muted" style="font-size: 0.7rem;">
                                                <?php echo date('d M, Y', strtotime($sd['fecha_salida'])); ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <small class="text-muted d-block ps-2"><i class="bi bi-info-circle me-1"></i>Deje vacío
                                    si es un gasto administrativo general.</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-black text-uppercase text-muted">Notas Internas</label>
                            <textarea name="notas" class="form-control bg-transparent border-dynamic text-dynamic"
                                rows="3"
                                placeholder="Añada cualquier detalle relevante para la auditoría..."></textarea>
                        </div>
                        <input type="hidden" name="estado" value="pendiente">
                    </div>
                    <div class="mt-5 mb-2 row g-3 text-center">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-outline-secondary w-100 py-3 rounded-pill border-0"
                                data-bs-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-premium-danger w-100 py-3 rounded-pill shadow-lg">
                                <i class="bi bi-plus-circle-fill me-2"></i>Registrar Egreso
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Premium -->
<div class="modal fade" id="modalEditarGasto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content glass-card-modal border-0 overflow-hidden">
            <div class="modal-header border-0 p-4 pb-0">
                <div>
                    <h3 class="modal-title fw-bold text-dynamic"><i
                            class="bi bi-pencil-fill me-2 text-primary"></i>Modificar Registro de Egreso</h3>
                    <p class="text-muted small mb-0">Actualice la información del gasto seleccionado.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="<?php echo BASE_URL; ?>agency/expenses/update" method="POST" class="needs-validation">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="form-label small fw-black text-uppercase text-muted">Beneficiario /
                                Destinatario</label>
                            <div class="input-group input-premium shadow-sm">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" name="beneficiario" id="edit_beneficiario" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-black text-uppercase text-muted">Importe Total</label>
                            <div class="input-group input-premium shadow-sm">
                                <span class="input-group-text fw-bold">S/</span>
                                <input type="number" name="monto" id="edit_monto" step="0.01"
                                    class="form-control fw-bold" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-black text-uppercase text-muted">Categoría
                                Financiera</label>
                            <div class="input-group input-premium shadow-sm">
                                <span class="input-group-text"><i class="bi bi-tags-fill"></i></span>
                                <select name="categoria" id="edit_categoria"
                                    class="form-select bg-dynamic text-dynamic-force" required>
                                    <option value="honorarios" class="option-force">Honorarios Staff</option>
                                    <option value="viaticos" class="option-force">Viáticos / Operatividad</option>
                                    <option value="servicios" class="option-force">Servicios Generales</option>
                                    <option value="retiro_personal" class="option-force">Retiro Directo Dueño</option>
                                    <option value="otros" class="option-force">Otros Gastos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-black text-uppercase text-muted">Fecha del Gasto</label>
                            <div class="input-group input-premium shadow-sm">
                                <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                                <input type="date" name="fecha_gasto" id="edit_fecha_gasto" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-black text-uppercase text-muted">Estado de Pago</label>
                            <div class="input-group input-premium shadow-sm">
                                <span class="input-group-text"><i class="bi bi-check-circle"></i></span>
                                <select name="estado" id="edit_estado" class="form-select bg-dynamic text-dynamic-force"
                                    required>
                                    <option value="pendiente" class="option-force">Pendiente</option>
                                    <option value="pagado" class="option-force">Pagado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-black text-uppercase text-muted">Vincular a Salida
                                Programada (Opcional)</label>
                            <div class="search-select-container">
                                <div class="input-group input-premium shadow-sm mb-2" id="edit_departureSearchGroup">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" id="edit_departureSearchInput" class="form-control"
                                        placeholder="Escriba para cambiar salida...">
                                </div>
                                <input type="hidden" name="salida_id" id="edit_selectedDepartureId">
                                <div id="edit_selectedDepartureDisplay" class="d-none mb-2">
                                    <div
                                        class="departure-link p-2 bg-soft-primary rounded-3 text-primary d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                                            <div>
                                                <div class="fw-bold line-height-1" id="edit_selectedDepartureTitle">--
                                                </div>
                                                <small class="opacity-75" id="edit_selectedDepartureDate">--</small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm text-danger"
                                            onclick="clearDepartureSelectionEdit()">
                                            <i class="bi bi-x-circle-fill"></i> Quitar
                                        </button>
                                    </div>
                                </div>
                                <ul id="edit_departureResultsList"
                                    class="list-group list-group-flush shadow border-dynamic rounded-3 overflow-auto d-none"
                                    style="max-height: 200px; z-index: 1060; position: absolute; width: calc(100% - 3rem);">
                                    <?php foreach ($activeDepartures as $sd): ?>
                                        <li class="list-group-item list-group-item-action border-dynamic py-2 px-3"
                                            style="cursor: pointer;" data-id="<?php echo $sd['id']; ?>"
                                            data-title="<?php echo htmlspecialchars($sd['tour_nombre']); ?>"
                                            data-date="<?php echo date('d M, Y', strtotime($sd['fecha_salida'])); ?>"
                                            onclick="selectDepartureEdit(this)">
                                            <div class="fw-bold small"><?php echo htmlspecialchars($sd['tour_nombre']); ?>
                                            </div>
                                            <div class="text-muted" style="font-size: 0.7rem;">
                                                <?php echo date('d M, Y', strtotime($sd['fecha_salida'])); ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-black text-uppercase text-muted">Notas Internas</label>
                            <textarea name="notas" id="edit_notas"
                                class="form-control bg-transparent border-dynamic text-dynamic" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="mt-5 mb-2 row g-3 text-center">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-outline-secondary w-100 py-3 rounded-pill border-0"
                                data-bs-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-premium-danger w-100 py-3 rounded-pill shadow-lg"
                                style="background: linear-gradient(45deg, #0d6efd, #0dcaf0);">
                                <i class="bi bi-save-fill me-2"></i>Actualizar Registro
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Financial Dashboard Styles */
    .text-gradient-primary {
        background: linear-gradient(45deg, var(--brand-primary, #10b981), #4dabf7);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* KPI Cards Advanced */
    .kpi-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        z-index: 1;
    }

    .kpi-card:hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2) !important;
    }

    .kpi-icon {
        position: absolute;
        right: -10px;
        bottom: -15px;
        font-size: 5rem;
        opacity: 0.15;
        color: white;
        transform: rotate(-15deg);
        pointer-events: none;
    }

    .bg-glass-blue {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .bg-glass-orange {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%);
    }

    .bg-glass-red {
        background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%);
    }

    .bg-glass-purple {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    /* Icons Shapes */
    .icon-shape {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 1.25rem;
    }

    .bg-soft-primary {
        background-color: rgba(13, 110, 253, 0.15);
    }

    .bg-soft-info {
        background-color: rgba(13, 202, 240, 0.15);
    }

    .bg-soft-warning {
        background-color: rgba(255, 193, 7, 0.15);
    }

    .bg-soft-danger {
        background-color: rgba(220, 53, 69, 0.15);
    }

    .bg-soft-secondary {
        background-color: rgba(108, 117, 125, 0.15);
    }

    /* Table Improvements */
    .bg-soft-dynamic-header {
        background-color: var(--bg-soft);
        color: var(--text-muted);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .expense-row {
        border-bottom: 1px solid var(--border-color);
    }

    .expense-row:hover {
        background-color: rgba(255, 255, 255, 0.02);
    }

    .btn-icon-action {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        border: none;
        transition: all 0.2s;
    }

    .btn-icon-action:hover {
        transform: scale(1.1);
    }

    /* Modal & Inputs */
    .glass-card-modal {
        background-color: var(--bg-secondary) !important;
        border: 1px solid var(--border-color);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
    }

    .input-premium {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    .input-premium .input-group-text {
        border: none;
        background: var(--bg-soft);
        color: var(--text-muted);
    }

    .input-premium .form-control,
    .input-premium .form-select {
        border: none;
        padding: 12px;
        background: var(--bg-secondary);
        color: var(--text-primary) !important;
    }

    .input-premium .form-control:focus,
    .input-premium .form-select:focus {
        box-shadow: none;
        background: var(--bg-soft);
    }

    /* Select visibility fix - VERSION FORZADA */
    .text-dynamic-force {
        color: var(--text-primary) !important;
    }

    .option-force {
        background-color: #ffffff !important;
        color: #000000 !important;
    }

    body.superadmin-theme .option-force {
        background-color: #16213e !important;
        color: #ffffff !important;
    }

    .btn-premium-danger {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: white;
        border: none;
        font-weight: bold;
        transition: all 0.3s;
    }

    .btn-premium-danger:hover {
        box-shadow: 0 10px 20px rgba(255, 65, 108, 0.3);
        transform: translateY(-2px);
    }

    /* Badges */
    .badge-pagado {
        background: rgba(25, 135, 84, 0.15);
        color: #198754;
        padding: 8px 12px;
        border-radius: 30px;
        font-weight: 600;
    }

    .badge-pendiente {
        background: rgba(255, 193, 7, 0.15);
        color: #ffc107;
        padding: 8px 12px;
        border-radius: 30px;
        font-weight: 600;
    }

    .line-height-1 {
        line-height: 1.2;
    }

    .border-bottom-dynamic {
        border-bottom: 1px solid var(--border-color);
    }

    /* Search Results List Fix */
    #departureResultsList {
        background-color: var(--bg-secondary) !important;
        border: 1px solid var(--border-primary) !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3) !important;
    }

    #departureResultsList li {
        background-color: var(--bg-secondary) !important;
        color: var(--text-primary) !important;
        border-bottom: 1px solid var(--border-color) !important;
    }

    #departureResultsList li:hover {
        background-color: var(--bg-soft) !important;
    }

    .search-select-container {
        position: relative;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Búsqueda en tabla principal
        const searchInput = document.getElementById('expenseSearch');
        const tableRows = document.querySelectorAll('#expensesTable tbody tr.expense-row');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase();
                tableRows.forEach(row => {
                    row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
                });
            });
        }

        // Buscador de Salidas en Modal
        const depSearchInput = document.getElementById('departureSearchInput');
        const depResultsList = document.getElementById('departureResultsList');
        const depItems = depResultsList ? depResultsList.querySelectorAll('li') : [];

        if (depSearchInput) {
            depSearchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase();
                let hasResults = false;

                if (query.length > 0) {
                    depResultsList.classList.remove('d-none');
                    depItems.forEach(item => {
                        const match = item.innerText.toLowerCase().includes(query);
                        item.style.display = match ? '' : 'none';
                        if (match) hasResults = true;
                    });
                } else {
                    depResultsList.classList.add('d-none');
                }
            });

            // Cerrar lista al hacer click fuera
            document.addEventListener('click', function (e) {
                if (!depSearchInput.contains(e.target) && !depResultsList.contains(e.target)) {
                    depResultsList.classList.add('d-none');
                }
            });
        }

        // Buscador de Salidas en Modal Editar
        const editDepSearchInput = document.getElementById('edit_departureSearchInput');
        const editDepResultsList = document.getElementById('edit_departureResultsList');
        const editDepItems = editDepResultsList ? editDepResultsList.querySelectorAll('li') : [];

        if (editDepSearchInput) {
            editDepSearchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase();
                if (query.length > 0) {
                    editDepResultsList.classList.remove('d-none');
                    editDepItems.forEach(item => {
                        item.style.display = item.innerText.toLowerCase().includes(query) ? '' : 'none';
                    });
                } else {
                    editDepResultsList.classList.add('d-none');
                }
            });

            document.addEventListener('click', function (e) {
                if (!editDepSearchInput.contains(e.target) && !editDepResultsList.contains(e.target)) {
                    editDepResultsList.classList.add('d-none');
                }
            });
        }
    });

    function selectDeparture(element) {
        const id = element.getAttribute('data-id');
        const title = element.getAttribute('data-title');
        const date = element.getAttribute('data-date');

        document.getElementById('selectedDepartureId').value = id;
        document.getElementById('selectedDepartureTitle').innerText = title;
        document.getElementById('selectedDepartureDate').innerText = date;

        document.getElementById('selectedDepartureDisplay').classList.remove('d-none');
        document.getElementById('departureSearchInput').parentElement.classList.add('d-none');
        document.getElementById('departureResultsList').classList.add('d-none');
    }

    function clearDepartureSelection() {
        document.getElementById('selectedDepartureId').value = '';
        document.getElementById('selectedDepartureDisplay').classList.add('d-none');
        document.getElementById('departureSearchInput').parentElement.classList.remove('d-none');
        document.getElementById('departureSearchInput').value = '';
    }

    // Funciones para Modal Editar
    function openEditModal(ex) {
        document.getElementById('edit_id').value = ex.id;
        document.getElementById('edit_beneficiario').value = ex.beneficiario;
        document.getElementById('edit_monto').value = ex.monto;
        document.getElementById('edit_categoria').value = ex.categoria;
        document.getElementById('edit_fecha_gasto').value = ex.fecha_gasto;
        document.getElementById('edit_estado').value = ex.estado;
        document.getElementById('edit_notas').value = ex.notas || '';

        if (ex.salida_id) {
            document.getElementById('edit_selectedDepartureId').value = ex.salida_id;
            document.getElementById('edit_selectedDepartureTitle').innerText = ex.tour_nombre || 'Salida vinculada';
            document.getElementById('edit_selectedDepartureDate').innerText = ex.fecha_salida ? new Date(ex.fecha_salida).toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' }) : '';
            document.getElementById('edit_selectedDepartureDisplay').classList.remove('d-none');
            document.getElementById('edit_departureSearchGroup').classList.add('d-none');
        } else {
            clearDepartureSelectionEdit();
        }

        new bootstrap.Modal(document.getElementById('modalEditarGasto')).show();
    }

    function selectDepartureEdit(element) {
        const id = element.getAttribute('data-id');
        const title = element.getAttribute('data-title');
        const date = element.getAttribute('data-date');

        document.getElementById('edit_selectedDepartureId').value = id;
        document.getElementById('edit_selectedDepartureTitle').innerText = title;
        document.getElementById('edit_selectedDepartureDate').innerText = date;

        document.getElementById('edit_selectedDepartureDisplay').classList.remove('d-none');
        document.getElementById('edit_departureSearchGroup').classList.add('d-none');
        document.getElementById('edit_departureResultsList').classList.add('d-none');
    }

    function clearDepartureSelectionEdit() {
        document.getElementById('edit_selectedDepartureId').value = '';
        document.getElementById('edit_selectedDepartureDisplay').classList.add('d-none');
        document.getElementById('edit_departureSearchGroup').classList.remove('d-none');
        document.getElementById('edit_departureSearchInput').value = '';
    }

    function showInfo(beneficiario, notas) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: beneficiario,
                text: notas,
                icon: 'info',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#ff4b2b',
                background: getComputedStyle(document.body).getPropertyValue('--bg-secondary') || '#16213e',
                color: getComputedStyle(document.body).getPropertyValue('--text-primary') || '#ffffff'
            });
        } else {
            alert(beneficiario + ": " + notas);
        }
    }
</script>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
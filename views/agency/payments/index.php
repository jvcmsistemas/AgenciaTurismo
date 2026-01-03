<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<?php
// Helper for sorting links
$currentSearch = $_GET['search'] ?? '';
$currentSort = $_GET['sort'] ?? 'p.fecha_pago';
$currentOrder = $_GET['order'] ?? 'DESC';
$startDate = $_GET['start_date'] ?? date('Y-m-01');
$endDate = $_GET['end_date'] ?? date('Y-m-d');

if (!function_exists('buildSortLink')) {
    function buildSortLink($column, $currentSort, $currentOrder, $search, $startDate, $endDate)
    {
        $newOrder = ($currentSort === $column && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
        return BASE_URL . "agency/payments?search=" . urlencode($search) . "&start_date=$startDate&end_date=$endDate&sort=$column&order=$newOrder";
    }
}

if (!function_exists('renderSortIcon')) {
    function renderSortIcon($column, $currentSort, $currentOrder)
    {
        if ($currentSort !== $column)
            return '<i class="bi bi-arrow-down-up small ms-1 opacity-25"></i>';
        return $currentOrder === 'ASC' ? '<i class="bi bi-sort-down ms-1 text-primary"></i>' : '<i class="bi bi-sort-up-alt ms-1 text-primary"></i>';
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-primary mb-1">Flujo de Pagos</h2>
        <p class="text-muted mb-0">Monitorea los ingresos y pagos de tus reservas.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary rounded-pill px-4" onclick="window.print()">
            <i class="bi bi-printer me-2"></i>Imprimir Reporte
        </button>
    </div>
</div>

<!-- Filtros y Resumen -->
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card glass-card border-0 shadow-sm h-100">
            <div class="card-body">
                <form method="GET" action="" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Desde</label>
                        <input type="date" name="start_date" class="form-control rounded-pill"
                            value="<?php echo $startDate; ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Hasta</label>
                        <input type="date" name="end_date" class="form-control rounded-pill"
                            value="<?php echo $endDate; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Búsqueda</label>
                        <input type="text" name="search" class="form-control rounded-pill" placeholder="Código o Ref..."
                            value="<?php echo htmlspecialchars($currentSearch); ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary rounded-pill w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-primary text-white border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-center text-center">
                <h6 class="text-white-50 text-uppercase mb-2">Ingresos Totales (Periodo)</h6>
                <h2 class="fw-bold mb-0">$
                    <?php echo number_format($totalIncome, 2); ?>
                </h2>
                <small class="text-white-50">Solo pagos aprobados</small>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Pagos -->
<div class="card glass-card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0 pt-4 px-4">
        <h5 class="fw-bold mb-0">Historial de Transacciones</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">
                            <a href="<?php echo buildSortLink('p.fecha_pago', $currentSort, $currentOrder, $currentSearch, $startDate, $endDate); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Fecha <?php echo renderSortIcon('p.fecha_pago', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo buildSortLink('r.codigo_reserva', $currentSort, $currentOrder, $currentSearch, $startDate, $endDate); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Reserva <?php echo renderSortIcon('r.codigo_reserva', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo buildSortLink('p.metodo_pago', $currentSort, $currentOrder, $currentSearch, $startDate, $endDate); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Método <?php echo renderSortIcon('p.metodo_pago', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th>Referencia</th>
                        <th>
                            <a href="<?php echo buildSortLink('p.monto', $currentSort, $currentOrder, $currentSearch, $startDate, $endDate); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Monto <?php echo renderSortIcon('p.monto', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo buildSortLink('p.estado', $currentSort, $currentOrder, $currentSearch, $startDate, $endDate); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Estado <?php echo renderSortIcon('p.estado', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($payments)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">No se encontraron pagos en este periodo.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($payments as $p): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">
                                        <?php echo date('d/m/Y', strtotime($p['fecha_pago'])); ?>
                                    </div>
                                    <small class="text-muted">
                                        <?php echo date('H:i', strtotime($p['fecha_pago'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>agency/reservations/show?id=<?php echo $p['reserva_id']; ?>"
                                        class="text-decoration-none fw-bold">
                                        <?php echo $p['codigo_reserva']; ?>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border rounded-pill px-3">
                                        <?php echo ucfirst($p['metodo_pago']); ?>
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    <?php echo $p['referencia'] ?: '-'; ?>
                                </td>
                                <td class="fw-bold text-primary">
                                    $
                                    <?php echo number_format($p['monto'], 2); ?>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = match ($p['estado']) {
                                        'aprobado' => 'bg-success',
                                        'pendiente' => 'bg-warning text-dark',
                                        'rechazado', 'anulado' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?> rounded-pill px-3">
                                        <?php echo ucfirst($p['estado']); ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?php echo BASE_URL; ?>agency/reservations/show?id=<?php echo $p['reserva_id']; ?>"
                                        class="btn btn-sm btn-light rounded-circle" title="Ver Reserva">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación Moderna -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="d-flex justify-content-between align-items-center mt-3 px-4 pb-4">
                <div class="text-muted small">
                    Mostrando <strong><?php echo count($payments); ?></strong> de
                    <strong><?php echo $totalPayments; ?></strong> pagos
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link rounded-pill border-primary text-primary px-3 py-2 bg-white small me-2 <?php echo $page <= 1 ? 'opacity-50 pointer-events-none' : 'shadow-sm'; ?>"
                                href="<?php echo BASE_URL . 'agency/payments?page=' . ($page - 1) . '&search=' . urlencode($currentSearch) . '&start_date=' . $startDate . '&end_date=' . $endDate . '&sort=' . $currentSort . '&order=' . $currentOrder; ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item">
                                <a class="page-link rounded-circle d-flex align-items-center justify-content-center mx-1 <?php echo $i === $page ? 'bg-primary text-white border-primary' : 'bg-white text-primary border-primary'; ?>"
                                    style="width: 32px; height: 32px; text-decoration: none; font-size: 0.85rem;"
                                    href="<?php echo BASE_URL . 'agency/payments?page=' . $i . '&search=' . urlencode($currentSearch) . '&start_date=' . $startDate . '&end_date=' . $endDate . '&sort=' . $currentSort . '&order=' . $currentOrder; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link rounded-pill border-primary text-primary px-3 py-2 bg-white small ms-2 <?php echo $page >= $totalPages ? 'opacity-50 pointer-events-none' : 'shadow-sm'; ?>"
                                href="<?php echo BASE_URL . 'agency/payments?page=' . ($page + 1) . '&search=' . urlencode($currentSearch) . '&start_date=' . $startDate . '&end_date=' . $endDate . '&sort=' . $currentSort . '&order=' . $currentOrder; ?>">
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
<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<?php
// Helper for sorting links
$currentSearch = $_GET['search'] ?? '';
$currentSort = $_GET['sort'] ?? 'placa';
$currentOrder = $_GET['order'] ?? 'ASC';

if (!function_exists('buildSortLink')) {
    function buildSortLink($column, $currentSort, $currentOrder, $search)
    {
        $newOrder = ($currentSort === $column && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
        return BASE_URL . "agency/transport?search=" . urlencode($search) . "&sort=$column&order=$newOrder";
    }
}

if (!function_exists('renderSortIcon')) {
    function renderSortIcon($column, $currentSort, $currentOrder)
    {
        if ($currentSort !== $column)
            return '<i class="bi bi-arrow-down-up small ms-1 opacity-25"></i>';
        return $currentOrder === 'ASC' ? '<i class="bi bi-sort-alpha-down ms-1 text-primary"></i>' : '<i class="bi bi-sort-alpha-up-alt ms-1 text-primary"></i>';
    }
}
?>

<div class="container-fluid py-4 fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1">Flota y Transporte</h2>
            <p class="text-muted small mb-0"><i class="bi bi-truck me-1"></i> Gestiona los vehículos y unidades de tu
                agencia.</p>
        </div>
        <a href="<?php echo BASE_URL; ?>agency/transport/create" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i>Registrar Vehículo
        </a>
    </div>

    <!-- Buscador Mejorado -->
    <div class="card glass-card border-0 shadow-sm mb-4 anim-slide-up">
        <div class="card-body p-3">
            <form action="<?php echo BASE_URL; ?>agency/transport" method="GET" class="row g-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i
                                class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0"
                            placeholder="Buscar por placa, modelo, chofer..."
                            value="<?php echo htmlspecialchars($currentSearch); ?>">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Unidades -->
    <div class="card glass-card border-0 shadow-sm anim-slide-up" style="animation-delay: 0.1s;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">
                            <a href="<?php echo buildSortLink('placa', $currentSort, $currentOrder, $currentSearch); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Placa
                                <?php echo renderSortIcon('placa', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo buildSortLink('modelo', $currentSort, $currentOrder, $currentSearch); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Modelo
                                <?php echo renderSortIcon('modelo', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo buildSortLink('capacidad', $currentSort, $currentOrder, $currentSearch); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Capacidad
                                <?php echo renderSortIcon('capacidad', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo buildSortLink('chofer_nombre', $currentSort, $currentOrder, $currentSearch); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Chofer Habitual
                                <?php echo renderSortIcon('chofer_nombre', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo buildSortLink('estado', $currentSort, $currentOrder, $currentSearch); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Estado
                                <?php echo renderSortIcon('estado', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transports)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No se encontraron vehículos registrados.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transports as $transport): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-uppercase">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-truck-flatbed"></i>
                                        </div>
                                        <?php echo htmlspecialchars($transport['placa']); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($transport['modelo'] ?? '-'); ?>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-warning bg-opacity-10 text-warning px-3 rounded-pill border border-warning border-opacity-25">
                                        <?php echo $transport['capacidad']; ?> Asientos
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold">
                                        <?php echo htmlspecialchars($transport['chofer_nombre'] ?? 'No asignado'); ?>
                                    </div>
                                    <small class="text-muted">
                                        <?php echo htmlspecialchars($transport['chofer_telefono'] ?? ''); ?>
                                    </small>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = ($transport['estado'] ?? 'activo') === 'activo' ? 'bg-success' : 'bg-danger';
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?> rounded-pill px-3">
                                        <?php echo ucfirst($transport['estado'] ?? 'activo'); ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?php echo BASE_URL; ?>agency/transport/edit?id=<?php echo $transport['id']; ?>"
                                            class="btn btn-sm btn-light text-primary rounded-circle shadow-sm" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if ($_SESSION['user_role'] === 'dueno_agencia'): ?>
                                            <a href="<?php echo BASE_URL; ?>agency/transport/delete?id=<?php echo $transport['id']; ?>"
                                                class="btn btn-sm btn-light text-danger rounded-circle shadow-sm" title="Eliminar"
                                                onclick="return confirm('¿Está seguro de eliminar esta unidad?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
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
                    Mostrando <strong>
                        <?php echo count($transports); ?>
                    </strong> de <strong>
                        <?php echo $totalTransports; ?>
                    </strong> vehículos
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link rounded-pill border-primary text-primary px-3 py-2 bg-white small me-2 <?php echo $page <= 1 ? 'opacity-50 pointer-events-none' : 'shadow-sm'; ?>"
                                href="<?php echo BASE_URL . 'agency/transport?page=' . ($page - 1) . '&search=' . urlencode($currentSearch) . '&sort=' . $currentSort . '&order=' . $currentOrder; ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item">
                                <a class="page-link rounded-circle d-flex align-items-center justify-content-center mx-1 <?php echo $i === $page ? 'bg-primary text-white border-primary' : 'bg-white text-primary border-primary'; ?>"
                                    style="width: 32px; height: 32px; text-decoration: none; font-size: 0.85rem;"
                                    href="<?php echo BASE_URL . 'agency/transport?page=' . $i . '&search=' . urlencode($currentSearch) . '&sort=' . $currentSort . '&order=' . $currentOrder; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link rounded-pill border-primary text-primary px-3 py-2 bg-white small ms-2 <?php echo $page >= $totalPages ? 'opacity-50 pointer-events-none' : 'shadow-sm'; ?>"
                                href="<?php echo BASE_URL . 'agency/transport?page=' . ($page + 1) . '&search=' . urlencode($currentSearch) . '&sort=' . $currentSort . '&order=' . $currentOrder; ?>">
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
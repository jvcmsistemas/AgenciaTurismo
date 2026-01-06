<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<?php
// Helper for sorting links
$currentSearch = $_GET['search'] ?? '';
$currentSort = $_GET['sort'] ?? 'id';
$currentOrder = $_GET['order'] ?? 'DESC';

if (!function_exists('buildSortLink')) {
    function buildSortLink($column, $currentSort, $currentOrder, $search)
    {
        $newOrder = ($currentSort === $column && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
        return BASE_URL . "agency/tours?search=" . urlencode($search) . "&sort=$column&order=$newOrder";
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

    .tour-table thead th {
        background: rgba(16, 185, 129, 0.05);
        color: var(--brand-primary);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1.25rem 1rem;
        border: none;
    }

    .tour-table tbody td {
        padding: 1.25rem 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        vertical-align: middle;
        color: var(--text-primary);
    }

    .tour-icon-box {
        width: 45px;
        height: 45px;
        background: rgba(16, 185, 129, 0.1);
        color: var(--brand-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.25rem;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .badge-pill-custom {
        padding: 0.4em 1em;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.7rem;
        border: 1px solid transparent;
    }

    .difficulty-facil {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border-color: rgba(16, 185, 129, 0.2);
    }

    .difficulty-medio {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
        border-color: rgba(245, 158, 11, 0.2);
    }

    .difficulty-alto {
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

    .search-container-glass:focus-within {
        background: rgba(255, 255, 255, 0.05);
        border-color: var(--brand-secondary);
        box-shadow: 0 0 15px rgba(52, 211, 153, 0.1);
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

    .tag-badge {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--text-secondary);
        font-size: 0.65rem;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .agency-light-theme .glass-card-premium {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(0, 0, 0, 0.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    .agency-light-theme .tag-badge {
        background: rgba(0, 0, 0, 0.03);
        border-color: rgba(0, 0, 0, 0.05);
        color: var(--text-secondary);
    }
</style>

<div class="container-fluid py-4 fade-in">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-gradient-emerald mb-1">Catálogo de Experiencias</h2>
            <p class="text-muted small mb-0"><i class="bi bi-map me-1"></i> Gestiona tus rutas, tours y paquetes</p>
        </div>
        <a href="<?php echo BASE_URL; ?>agency/tours/create" class="btn btn-emerald-premium">
            <i class="bi bi-plus-lg me-2"></i>Crear Nueva Ruta
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="glass-card-premium mb-4 p-3 anim-slide-up">
        <form method="GET" action="<?php echo BASE_URL; ?>agency/tours" class="row g-3 align-items-center">
            <div class="col-md-6 col-lg-5">
                <div class="search-container-glass d-flex align-items-center">
                    <i class="bi bi-search text-muted me-2"></i>
                    <input type="text" name="search" class="form-control bg-transparent border-0 shadow-none text-white"
                        placeholder="Buscar por nombre, ubicación o etiquetas..."
                        value="<?php echo htmlspecialchars($currentSearch); ?>"
                        style="color: var(--text-primary) !important;">
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-emerald-premium py-2 px-4 shadow-none">Filtrar Resultados</button>
            </div>
        </form>
    </div>

    <!-- Main Table -->
    <div class="glass-card-premium anim-slide-up" style="animation-delay: 0.1s;">
        <div class="table-responsive">
            <table class="table tour-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">
                            <a href="<?php echo buildSortLink('nombre', $currentSort, $currentOrder, $currentSearch); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Experiencia / Tour <?php echo renderSortIcon('nombre', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo buildSortLink('duracion', $currentSort, $currentOrder, $currentSearch); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Tipo & Duración <?php echo renderSortIcon('duracion', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo buildSortLink('ubicacion', $currentSort, $currentOrder, $currentSearch); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Ubicación <?php echo renderSortIcon('ubicacion', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo buildSortLink('precio', $currentSort, $currentOrder, $currentSearch); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Precio Base <?php echo renderSortIcon('precio', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo buildSortLink('nivel_dificultad', $currentSort, $currentOrder, $currentSearch); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Dificultad
                                <?php echo renderSortIcon('nivel_dificultad', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tours)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="opacity-20 mb-3"><i class="bi bi-map" style="font-size: 4rem;"></i></div>
                                <h5 class="fw-bold text-muted">No se encontraron rutas</h5>
                                <p class="small text-muted">Comienza creando tu primera experiencia turística.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tours as $tour): ?>
                            <?php
                            $isPackage = isset($tour['duracion']) && $tour['duracion'] > 1;
                            $isFullDay = isset($tour['duracion']) && $tour['duracion'] == 1;

                            $typeLabel = 'Tour Corto';
                            $typeIcon = 'bi-stopwatch';
                            $typeClass = 'text-info';

                            if ($isPackage) {
                                $typeLabel = 'Paquete';
                                $typeIcon = 'bi-box-seam-fill';
                                $typeClass = 'text-primary';
                            } elseif ($isFullDay) {
                                $typeLabel = 'Full Day';
                                $typeIcon = 'bi-sun-fill';
                                $typeClass = 'text-warning';
                            }
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="tour-icon-box me-3">
                                            <i class="bi <?php echo $typeIcon; ?>"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold mb-1" style="color: var(--text-primary);">
                                                <?php echo htmlspecialchars($tour['nombre']); ?>
                                            </div>
                                            <?php if (!empty($tour['tags'])): ?>
                                                <div class="d-flex gap-1 flex-wrap">
                                                    <?php foreach (explode(',', $tour['tags']) as $tag): ?>
                                                        <span class="tag-badge"><?php echo trim($tag); ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold small mb-1 <?php echo $typeClass; ?>">
                                            <?php echo $typeLabel; ?>
                                        </span>
                                        <span class="text-muted small">
                                            <i class="bi bi-clock me-1"></i>
                                            <?php
                                            if ($isPackage)
                                                echo $tour['duracion'] . ' Días / ' . ($tour['duracion'] - 1) . ' Noches';
                                            else
                                                echo $isFullDay ? 'Todo el día' : $tour['duracion'] . ' Días';
                                            ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-muted small">
                                        <i class="bi bi-geo-alt-fill me-2 text-danger opacity-75"></i>
                                        <span><?php echo htmlspecialchars($tour['ubicacion'] ?? 'No especificada'); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold" style="color: var(--brand-primary);">
                                        <?php echo formatCurrency($tour['precio']); ?>
                                    </div>
                                    <div class="text-muted" style="font-size: 0.7rem;">base por persona</div>
                                </td>
                                <td>
                                    <?php
                                    $dif = $tour['nivel_dificultad'] ?? 'medio';
                                    $difClass = "difficulty-$dif";
                                    ?>
                                    <span class="badge-pill-custom <?php echo $difClass; ?>">
                                        <?php echo ucfirst($dif); ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?php echo BASE_URL; ?>agency/tours/edit?id=<?php echo $tour['id']; ?>"
                                            class="action-btn-glass" title="Editar Tour">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>agency/tours/delete?id=<?php echo $tour['id']; ?>"
                                            class="action-btn-glass text-danger"
                                            onclick="return confirm('¿Eliminar este tour?');" title="Eliminar">
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

        <!-- Paginación Moderna Premium -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="d-flex justify-content-between align-items-center mt-3 px-4 pb-4">
                <div class="text-muted small">
                    Mostrando <strong><?php echo count($tours); ?></strong> de <strong><?php echo $totalTours; ?></strong>
                    experiencias
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link rounded-pill border-success text-success px-3 py-2 bg-white small me-2 <?php echo $page <= 1 ? 'opacity-50 pointer-events-none' : 'shadow-sm'; ?>"
                                href="<?php echo BASE_URL . 'agency/tours?page=' . ($page - 1) . '&search=' . urlencode($currentSearch) . '&sort=' . $currentSort . '&order=' . $currentOrder; ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item">
                                <a class="page-link rounded-circle d-flex align-items-center justify-content-center mx-1 <?php echo $i === $page ? 'bg-success text-white border-success' : 'bg-white text-success border-success'; ?>"
                                    style="width: 32px; height: 32px; text-decoration: none; font-size: 0.85rem;"
                                    href="<?php echo BASE_URL . 'agency/tours?page=' . $i . '&search=' . urlencode($currentSearch) . '&sort=' . $currentSort . '&order=' . $currentOrder; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link rounded-pill border-success text-success px-3 py-2 bg-white small ms-2 <?php echo $page >= $totalPages ? 'opacity-50 pointer-events-none' : 'shadow-sm'; ?>"
                                href="<?php echo BASE_URL . 'agency/tours?page=' . ($page + 1) . '&search=' . urlencode($currentSearch) . '&sort=' . $currentSort . '&order=' . $currentOrder; ?>">
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
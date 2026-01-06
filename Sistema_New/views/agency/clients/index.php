<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<?php
// Helper for sorting links
$currentSearch = $_GET['search'] ?? '';
$currentSort = $_GET['sort'] ?? 'nombre';
$currentOrder = $_GET['order'] ?? 'ASC';

if (!function_exists('buildSortLink')) {
    function buildSortLink($column, $currentSort, $currentOrder, $search)
    {
        $newOrder = ($currentSort === $column && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
        return BASE_URL . "agency/clients?search=" . urlencode($search) . "&sort=$column&order=$newOrder";
    }
}

if (!function_exists('renderSortIcon')) {
    function renderSortIcon($column, $currentSort, $currentOrder)
    {
        if ($currentSort !== $column)
            return '<i class="bi bi-arrow-down-up small ms-1 opacity-25"></i>';
        return $currentOrder === 'ASC' ? '<i class="bi bi-sort-alpha-down ms-1 text-success"></i>' : '<i class="bi bi-sort-alpha-up-alt ms-1 text-success"></i>';
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

    .client-table thead th {
        background: rgba(16, 185, 129, 0.05);
        color: var(--brand-primary);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1.25rem 1rem;
        border: none;
    }

    .client-table tbody td {
        padding: 1.25rem 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        vertical-align: middle;
        color: var(--text-primary);
        /* Ensure text follows theme */
    }

    .avatar-client {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
        color: white !important;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .badge-doc {
        background: rgba(16, 185, 129, 0.1);
        color: var(--brand-primary);
        font-weight: 600;
        font-size: 0.7rem;
        padding: 0.4em 0.8em;
        border-radius: 6px;
        border: 1px solid rgba(16, 185, 129, 0.2);
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

    .action-btn-glass.btn-delete:hover {
        background: #ef4444;
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
    }

    .btn-emerald-premium:hover {
        background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        color: white !important;
    }

    /* Light Theme Overrides */
    .agency-light-theme .glass-card-premium {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(0, 0, 0, 0.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    .agency-light-theme .client-table tbody td {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .agency-light-theme .search-container-glass input {
        color: var(--text-primary) !important;
    }

    .agency-light-theme .search-container-glass {
        background: rgba(0, 0, 0, 0.03);
        border-color: rgba(0, 0, 0, 0.1);
    }
</style>

<div class="container-fluid py-4 fade-in">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-gradient-emerald mb-1">Directorio de Clientes</h2>
            <p class="text-muted small mb-0"><i class="bi bi-shield-check me-1"></i> Base de datos centralizada de
                viajeros</p>
        </div>
        <a href="<?php echo BASE_URL; ?>agency/clients/create" class="btn btn-emerald-premium">
            <i class="bi bi-person-plus-fill me-2"></i>Nuevo Registro
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="glass-card-premium mb-4 p-3 anim-slide-up">
        <form action="<?php echo BASE_URL; ?>agency/clients" method="GET" class="row g-3 align-items-center">
            <div class="col-md-6 col-lg-4">
                <div class="search-container-glass d-flex align-items-center">
                    <i class="bi bi-search text-muted me-2"></i>
                    <input type="text" name="search" class="form-control bg-transparent border-0 shadow-none text-white"
                        placeholder="Nombre, documento o correo..."
                        value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-outline-emerald rounded-pill px-3">Filtrar</button>
            </div>
        </form>
    </div>

    <!-- Main Table -->
    <div class="glass-card-premium anim-slide-up" style="animation-delay: 0.1s;">
        <div class="table-responsive">
            <table class="table client-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">
                            <a href="<?php echo buildSortLink('nombre', $currentSort, $currentOrder, $currentSearch); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Identidad <?php echo renderSortIcon('nombre', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo buildSortLink('email', $currentSort, $currentOrder, $currentSearch); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Contacto <?php echo renderSortIcon('email', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo buildSortLink('dni', $currentSort, $currentOrder, $currentSearch); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Documentación <?php echo renderSortIcon('dni', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo buildSortLink('nacionalidad', $currentSort, $currentOrder, $currentSearch); ?>"
                                class="text-decoration-none" style="color: inherit;">
                                Nacionalidad <?php echo renderSortIcon('nacionalidad', $currentSort, $currentOrder); ?>
                            </a>
                        </th>
                        <th class="text-end pe-4">Gestión</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clients)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="opacity-20 mb-3"><i class="bi bi-people" style="font-size: 4rem;"></i></div>
                                <h5 class="fw-bold text-muted">No hay clientes registrados</h5>
                                <p class="small text-muted">Tus clientes aparecerán listados aquí una vez que los registres.
                                </p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-client me-3">
                                            <?php
                                            $iniciales = strtoupper(substr($client['nombre'], 0, 1) . substr($client['apellido'], 0, 1));
                                            echo $iniciales;
                                            ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold mb-0" style="color: var(--text-primary);">
                                                <?php echo htmlspecialchars($client['nombre'] . ' ' . $client['apellido']); ?>
                                            </div>
                                            <div class="text-muted" style="font-size: 0.75rem;">
                                                ID: #<?php echo str_pad($client['id'], 5, '0', STR_PAD_LEFT); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="small opacity-90" style="color: var(--text-primary);"><i
                                                class="bi bi-envelope-at me-2 text-brand"></i><?php echo htmlspecialchars($client['email']); ?></span>
                                        <span class="small text-muted"><i
                                                class="bi bi-whatsapp me-2"></i><?php echo htmlspecialchars($client['telefono']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-2">
                                        <span class="badge-doc w-fit"><i
                                                class="bi bi-card-text me-2"></i><?php echo htmlspecialchars($client['dni'] ?? 'N/A'); ?></span>
                                        <?php if (!empty($client['ruc'])): ?>
                                            <span class="badge-doc w-fit" style="background: rgba(52, 211, 153, 0.05);"><i
                                                    class="bi bi-building me-2"></i><?php echo htmlspecialchars($client['ruc']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="small fw-600" style="color: var(--text-primary);">
                                        <i class="bi bi-geo-alt me-1 text-brand"></i>
                                        <?php echo htmlspecialchars($client['nacionalidad'] ?? 'Peruana'); ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?php echo BASE_URL; ?>agency/clients/edit?id=<?php echo $client['id']; ?>"
                                            class="action-btn-glass" title="Editar Expediente">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>agency/clients/delete?id=<?php echo $client['id']; ?>"
                                            class="action-btn-glass btn-delete"
                                            onclick="return confirm('¿Está seguro de eliminar este registro?');"
                                            title="Dar de baja">
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

        <!-- Paginación Moderna (Estilo Universal de Alto Contraste) -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="d-flex justify-content-between align-items-center mt-3 px-4 pb-4">
                <div class="text-muted small">
                    Mostrando <strong><?php echo count($clients); ?></strong> de
                    <strong><?php echo $totalClients; ?></strong> clientes
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link rounded-pill border-success text-success px-3 py-2 bg-white small me-2 <?php echo $page <= 1 ? 'opacity-50 pointer-events-none' : 'shadow-sm'; ?>"
                                href="<?php echo BASE_URL . 'agency/clients?page=' . (isset($page) ? $page - 1 : 1) . '&search=' . urlencode($currentSearch) . '&sort=' . $currentSort . '&order=' . $currentOrder; ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item">
                                <a class="page-link rounded-circle d-flex align-items-center justify-content-center mx-1 <?php echo $i === (isset($page) ? $page : 1) ? 'bg-success text-white border-success' : 'bg-white text-success border-success'; ?>"
                                    style="width: 32px; height: 32px; text-decoration: none; font-size: 0.85rem; transition: all 0.2s;"
                                    href="<?php echo BASE_URL . 'agency/clients?page=' . $i . '&search=' . urlencode($currentSearch) . '&sort=' . $currentSort . '&order=' . $currentOrder; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link rounded-pill border-success text-success px-3 py-2 bg-white small ms-2 <?php echo $page >= $totalPages ? 'opacity-50 pointer-events-none' : 'shadow-sm'; ?>"
                                href="<?php echo BASE_URL . 'agency/clients?page=' . (isset($page) ? $page + 1 : 1) . '&search=' . urlencode($currentSearch) . '&sort=' . $currentSort . '&order=' . $currentOrder; ?>">
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
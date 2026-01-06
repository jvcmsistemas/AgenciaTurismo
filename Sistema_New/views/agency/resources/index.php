<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<?php
// Helper for sorting links
$currentTab = $_GET['tab'] ?? 'guides';
$currentSearch = $_GET['search'] ?? '';
$currentSort = $_GET['sort'] ?? 'nombre';
$currentOrder = $_GET['order'] ?? 'ASC';

if (!function_exists('buildSortLink')) {
    function buildSortLink($column, $currentSort, $currentOrder, $tab, $search)
    {
        $newOrder = ($currentSort === $column && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
        return BASE_URL . "agency/resources?tab=" . urlencode($tab) . "&search=" . urlencode($search) . "&sort=$column&order=$newOrder";
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

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-success mb-1">Staff de Guías</h2>
        <p class="text-muted mb-0">Administra el personal de guías de tu agencia.</p>
    </div>
</div>


<div class="tab-content" id="resourceTabsContent">

    <!-- TAB: GUÍAS -->
    <div class="tab-pane fade show active" id="guides" role="tabpanel">
        <div class="card glass-card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-success mb-0">Staff de Guías</h5>
                    <button class="btn btn-success rounded-pill" onclick="openGuideModal()">
                        <i class="bi bi-plus-lg me-2"></i>Nuevo Guía
                    </button>
                </div>
                <!-- Buscador Guías -->
                <form class="d-flex" method="GET" action="">
                    <input type="hidden" name="tab" value="guides">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                            placeholder="Buscar guía por nombre, DNI o email..."
                            value="<?php echo ($_GET['tab'] ?? '') === 'guides' ? htmlspecialchars($_GET['search'] ?? '') : ''; ?>">
                        <button class="btn btn-outline-success" type="submit">Buscar</button>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">
                                    <a href="<?php echo buildSortLink('nombre', $currentSort, $currentOrder, 'guides', $currentSearch); ?>"
                                        class="text-dark text-decoration-none">
                                        Guía <?php echo renderSortIcon('nombre', $currentSort, $currentOrder); ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="<?php echo buildSortLink('dni', $currentSort, $currentOrder, 'guides', $currentSearch); ?>"
                                        class="text-dark text-decoration-none">
                                        DNI / CE <?php echo renderSortIcon('dni', $currentSort, $currentOrder); ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="<?php echo buildSortLink('email', $currentSort, $currentOrder, 'guides', $currentSearch); ?>"
                                        class="text-dark text-decoration-none">
                                        Email / Teléfono
                                        <?php echo renderSortIcon('email', $currentSort, $currentOrder); ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="<?php echo buildSortLink('genero', $currentSort, $currentOrder, 'guides', $currentSearch); ?>"
                                        class="text-dark text-decoration-none">
                                        Info <?php echo renderSortIcon('genero', $currentSort, $currentOrder); ?>
                                    </a>
                                </th>
                                <th>Notas</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($guides)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No hay guías registrados.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($guides as $guide): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold">
                                                <?php echo htmlspecialchars($guide['nombre'] . ' ' . ($guide['apellido'] ?? '')); ?>
                                            </div>
                                            <small
                                                class="badge bg-dark text-white"><?php echo ucfirst($guide['genero'] ?? 'No especificado'); ?></small>
                                        </td>
                                        <td><code
                                                class="text-primary fw-bold"><?php echo htmlspecialchars($guide['dni'] ?? '-'); ?></code>
                                        </td>
                                        <td>
                                            <div><i
                                                    class="bi bi-envelope small me-1"></i><?php echo htmlspecialchars($guide['email'] ?? '-'); ?>
                                            </div>
                                            <div class="small text-muted"><i
                                                    class="bi bi-whatsapp small me-1"></i><?php echo htmlspecialchars($guide['telefono'] ?? '-'); ?>
                                            </div>
                                        </td>
                                        <td class="small">
                                            <div><i
                                                    class="bi bi-geo-alt small me-1"></i><?php echo htmlspecialchars($guide['ciudad_region'] ?? '-'); ?>
                                            </div>
                                            <div class="text-muted"><i
                                                    class="bi bi-calendar-event small me-1"></i><?php echo $guide['fecha_nacimiento'] ? date('d/m/Y', strtotime($guide['fecha_nacimiento'])) : '-'; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (!empty($guide['notas'])): ?>
                                                <button type="button"
                                                    class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1"
                                                    onclick="alert('Notas del Guía:\n<?php echo addslashes(str_replace(["\r", "\n"], ' ', $guide['notas'])); ?>')"
                                                    title="Haz clic para ver notas" style="cursor:pointer; border-radius: 4px;">
                                                    <i class="bi bi-chat-left-text me-1"></i>Ver notas
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-light text-primary rounded-circle me-1"
                                                onclick='openGuideModal(<?php echo json_encode($guide); ?>)'>
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <?php if ($_SESSION['user_role'] === 'dueno_agencia'): ?>
                                                <a href="<?php echo BASE_URL; ?>agency/resources/delete-guide?id=<?php echo $guide['id']; ?>"
                                                    class="btn btn-sm btn-light text-danger rounded-circle"
                                                    onclick="return confirm('¿Eliminar guía?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación Moderna -->
                <?php if (isset($totalPages) && $totalPages > 1): ?>
                    <div class="d-flex justify-content-between align-items-center mt-3 px-4 pb-3">
                        <div class="text-muted small">
                            Mostrando <strong><?php echo count($guides); ?></strong> de
                            <strong><?php echo $totalGuides; ?></strong> guías
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                    <a class="page-link rounded-pill border-success text-success px-3 py-2 bg-white small me-2 <?php echo $page <= 1 ? 'opacity-50 pointer-events-none' : 'shadow-sm'; ?>"
                                        href="<?php echo BASE_URL . 'agency/resources?tab=guides&page=' . (isset($page) ? $page - 1 : 1) . '&search=' . urlencode($currentSearch) . '&sort=' . $currentSort . '&order=' . $currentOrder; ?>">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item">
                                        <a class="page-link rounded-circle d-flex align-items-center justify-content-center mx-1 <?php echo $i === (isset($page) ? $page : 1) ? 'bg-success text-white border-success' : 'bg-white text-success border-success'; ?>"
                                            style="width: 32px; height: 32px; text-decoration: none; font-size: 0.85rem; transition: all 0.2s;"
                                            href="<?php echo BASE_URL . 'agency/resources?tab=guides&page=' . $i . '&search=' . urlencode($currentSearch) . '&sort=' . $currentSort . '&order=' . $currentOrder; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                    <a class="page-link rounded-pill border-success text-success px-3 py-2 bg-white small ms-2 <?php echo $page >= $totalPages ? 'opacity-50 pointer-events-none' : 'shadow-sm'; ?>"
                                        href="<?php echo BASE_URL . 'agency/resources?tab=guides&page=' . (isset($page) ? $page + 1 : 1) . '&search=' . urlencode($currentSearch) . '&sort=' . $currentSort . '&order=' . $currentOrder; ?>">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>


</div>

<!-- MODALES MEJORADOS -->

<!-- Modal Guía -->
<div class="modal fade" id="modalGuide" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content glass-card border-0 shadow-lg" id="formGuide"
            action="<?php echo BASE_URL; ?>agency/resources/store-guide" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" id="guide_id">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold text-success" id="modalGuideTitle"><i
                            class="bi bi-person-badge me-2"></i>Registrar Guía</h5>
                    <p class="text-muted small mb-0">Ingrese los datos del guía turístico.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nombres <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="guide_nombre" class="form-control"
                            placeholder="Ej. Juan Carlos" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Apellidos <span class="text-danger">*</span></label>
                        <input type="text" name="apellido" id="guide_apellido" class="form-control"
                            placeholder="Ej. Pérez Quispe" required>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">DNI / CE <span class="text-danger">*</span></label>
                        <input type="text" name="dni" id="guide_dni" class="form-control" placeholder="Documento"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Género</label>
                        <select name="genero" id="guide_genero" class="form-select">
                            <option value="">Seleccionar...</option>
                            <option value="masculino">Masculino</option>
                            <option value="femenino">Femenino</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" id="guide_fecha_nacimiento" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Teléfono / WhatsApp</label>
                        <input type="text" name="telefono" id="guide_telefono" class="form-control"
                            placeholder="Ej. 999888777">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Correo Electrónico</label>
                    <input type="email" name="email" id="guide_email" class="form-control"
                        placeholder="correo@ejemplo.com">
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Dirección</label>
                        <input type="text" name="direccion" id="guide_direccion" class="form-control"
                            placeholder="Calle, Av, Jr.">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ciudad y Región</label>
                        <input type="text" name="ciudad_region" id="guide_ciudad_region" class="form-control"
                            placeholder="Ej. Cusco, Cusco">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Comentarios / Notas</label>
                    <textarea name="notas" id="guide_notas" class="form-control" rows="2"
                        placeholder="Ej. Solo trabaja fines de semana..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success rounded-pill px-4">Guardar Guía</button>
            </div>
        </form>
    </div>
</div>



<script>
    const baseUrl = '<?php echo BASE_URL; ?>';

    // Mantener la pestaña activa al recargar si viene por URL
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab) {
            const tabTrigger = document.querySelector(`#${tab}-tab`);
            if (tabTrigger) {
                const tabInstance = new bootstrap.Tab(tabTrigger);
                tabInstance.show();
            }
        }
    });

    function updateUrlTab(tabName) {
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        // Limpiar search al cambiar de tab para evitar confusión
        url.searchParams.delete('search');
        window.history.pushState({}, '', url);
    }

    // --- MODAL LOGIC ---

    function openGuideModal(data = null) {
        const form = document.getElementById('formGuide');
        const title = document.getElementById('modalGuideTitle');
        const titleText = title.querySelector('i').outerHTML + (data ? ' Editar Guía' : ' Registrar Guía');

        title.innerHTML = titleText;

        if (data) {
            form.action = baseUrl + 'agency/resources/update-guide';
            document.getElementById('guide_id').value = data.id;
            document.getElementById('guide_nombre').value = data.nombre;
            document.getElementById('guide_apellido').value = data.apellido;
            document.getElementById('guide_dni').value = data.dni;
            document.getElementById('guide_genero').value = data.genero;
            document.getElementById('guide_fecha_nacimiento').value = data.fecha_nacimiento;
            document.getElementById('guide_telefono').value = data.telefono;
            document.getElementById('guide_email').value = data.email;
            document.getElementById('guide_direccion').value = data.direccion;
            document.getElementById('guide_ciudad_region').value = data.ciudad_region;
            document.getElementById('guide_notas').value = data.notas;
        } else {
            form.action = baseUrl + 'agency/resources/store-guide';
            form.reset();
            document.getElementById('guide_id').value = '';
        }
        new bootstrap.Modal(document.getElementById('modalGuide')).show();
    }


</script>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
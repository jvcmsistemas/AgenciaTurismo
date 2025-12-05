<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-success fw-bold mb-1">Catálogo de Experiencias</h2>
            <p class="text-muted mb-0">Gestiona tus tours, full days y paquetes turísticos.</p>
        </div>
        <a href="<?php echo BASE_URL; ?>agency/tours/create" class="btn btn-success rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Nuevo Tour
        </a>
    </div>

    <!-- Barra de Herramientas: Buscador y Filtros -->
    <div class="card glass-card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="" class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i
                                class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                            placeholder="Buscar por nombre, ubicación o descripción..."
                            value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i
                                class="bi bi-sort-down"></i></span>
                        <select name="order" class="form-select border-start-0" onchange="this.form.submit()">
                            <option value="newest" <?php echo ($_GET['order'] ?? '') === 'newest' ? 'selected' : ''; ?>>
                                Más Recientes</option>
                            <option value="name_asc" <?php echo ($_GET['order'] ?? '') === 'name_asc' ? 'selected' : ''; ?>>Nombre (A-Z)</option>
                            <option value="name_desc" <?php echo ($_GET['order'] ?? '') === 'name_desc' ? 'selected' : ''; ?>>Nombre (Z-A)</option>
                            <option value="price_asc" <?php echo ($_GET['order'] ?? '') === 'price_asc' ? 'selected' : ''; ?>>Precio (Menor a Mayor)</option>
                            <option value="price_desc" <?php echo ($_GET['order'] ?? '') === 'price_desc' ? 'selected' : ''; ?>>Precio (Mayor a Menor)</option>
                            <option value="duration_asc" <?php echo ($_GET['order'] ?? '') === 'duration_asc' ? 'selected' : ''; ?>>Duración (Cortos primero)</option>
                            <option value="duration_desc" <?php echo ($_GET['order'] ?? '') === 'duration_desc' ? 'selected' : ''; ?>>Duración (Largos primero)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-outline-success">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Listado de Tours -->
    <div class="glass-card border-0 shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Experiencia / Tour</th>
                            <th>Tipo & Duración</th>
                            <th>Ubicación</th>
                            <th>Precio Base</th>
                            <th>Dificultad</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tours)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="mb-3"><i class="bi bi-map fs-1 text-secondary opacity-50"></i></div>
                                    <h5 class="fw-normal">No se encontraron resultados</h5>
                                    <p class="small">Intenta con otros términos de búsqueda o crea un nuevo tour.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($tours as $tour): ?>
                                <?php
                                // Lógica de Diferenciación Visual
                                $isPackage = $tour['duracion'] > 1;
                                $isFullDay = $tour['duracion'] == 1;

                                $typeLabel = 'Tour Corto';
                                $typeIcon = 'bi-stopwatch';
                                $typeClass = 'text-info';
                                $rowClass = '';

                                if ($isPackage) {
                                    $typeLabel = 'Paquete Turístico';
                                    $typeIcon = 'bi-box-seam-fill';
                                    $typeClass = 'text-primary';
                                    $rowClass = 'bg-primary bg-opacity-10'; // Sutil fondo azul para paquetes
                                } elseif ($isFullDay) {
                                    $typeLabel = 'Full Day';
                                    $typeIcon = 'bi-sun-fill';
                                    $typeClass = 'text-warning';
                                }
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light p-2 me-3 d-flex align-items-center justify-content-center"
                                                style="width: 45px; height: 45px;">
                                                <i class="bi <?php echo $typeIcon; ?> fs-5 <?php echo $typeClass; ?>"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark mb-1">
                                                    <?php echo htmlspecialchars($tour['nombre']); ?></div>
                                                <?php if ($tour['tags']): ?>
                                                    <div class="d-flex gap-1 flex-wrap">
                                                        <?php foreach (explode(',', $tour['tags']) as $tag): ?>
                                                            <span class="badge bg-light text-muted border fw-normal px-2 py-1"
                                                                style="font-size: 0.7rem;">
                                                                <?php echo trim($tag); ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold <?php echo $typeClass; ?> small mb-1">
                                                <?php echo $typeLabel; ?>
                                            </span>
                                            <span class="text-muted small">
                                                <i class="bi bi-clock me-1"></i>
                                                <?php
                                                if ($isPackage)
                                                    echo $tour['duracion'] . ' Días / ' . ($tour['duracion'] - 1) . ' Noches';
                                                elseif ($isFullDay)
                                                    echo 'Todo el día';
                                                else
                                                    echo ($tour['duracion'] * 24 < 24) ? ($tour['duracion'] * 24) . ' Horas' : $tour['duracion'] . ' Días';
                                                // Nota: Asumiendo que duracion se guarda en días. Si es 0.5 son 12 horas.
                                                // Ajuste visual simple:
                                                if ($tour['duracion'] < 1)
                                                    echo 'Medio Día / Corto';
                                                ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="bi bi-geo-alt-fill me-2 text-danger opacity-75"></i>
                                            <span class="small"><?php echo htmlspecialchars($tour['ubicacion']); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="fw-bold text-success mb-0">S/
                                            <?php echo number_format($tour['precio'], 2); ?></h6>
                                        <small class="text-muted" style="font-size: 0.75rem;">por persona</small>
                                    </td>
                                    <td>
                                        <?php
                                        $diffColor = match ($tour['nivel_dificultad']) {
                                            'facil' => 'success',
                                            'medio' => 'warning',
                                            'alto' => 'danger',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <span
                                            class="badge bg-<?php echo $diffColor; ?> bg-opacity-10 text-<?php echo $diffColor; ?> border border-<?php echo $diffColor; ?> rounded-pill px-3">
                                            <?php echo ucfirst($tour['nivel_dificultad']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="<?php echo BASE_URL; ?>agency/tours/edit?id=<?php echo $tour['id']; ?>"
                                                class="btn btn-sm btn-light text-primary rounded-circle me-2 shadow-sm"
                                                title="Editar"
                                                style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="<?php echo BASE_URL; ?>agency/tours/delete?id=<?php echo $tour['id']; ?>"
                                                class="btn btn-sm btn-light text-danger rounded-circle shadow-sm"
                                                title="Eliminar"
                                                style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;"
                                                onclick="return confirm('¿Estás seguro de eliminar este tour?');">
                                                <i class="bi bi-trash"></i>
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
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
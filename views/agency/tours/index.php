<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary fw-bold mb-0">Mis Tours</h2>
            <p class="text-muted mb-0">Gestiona el catálogo de experiencias turísticas</p>
        </div>
        <a href="<?php echo BASE_URL; ?>agency/tours/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Nuevo Tour
        </a>
    </div>

    <div class="glass-card border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tour</th>
                            <th>Ubicación</th>
                            <th>Duración</th>
                            <th>Precio</th>
                            <th>Dificultad</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tours)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-map fs-1 d-block mb-3"></i>
                                    No tienes tours registrados aún. ¡Crea el primero!
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($tours as $tour): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($tour['nombre']); ?></div>
                                        <?php if ($tour['tags']): ?>
                                            <div class="small text-muted">
                                                <i class="bi bi-tags me-1"></i>
                                                <?php echo htmlspecialchars($tour['tags']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <i class="bi bi-geo-alt me-1 text-danger"></i>
                                        <?php echo htmlspecialchars($tour['ubicacion']); ?>
                                    </td>
                                    <td>
                                        <i class="bi bi-clock me-1 text-primary"></i>
                                        <?php echo $tour['duracion']; ?> días
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">S/
                                            <?php echo number_format($tour['precio'], 2); ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        $diffColor = 'success';
                                        if ($tour['nivel_dificultad'] === 'medio')
                                            $diffColor = 'warning';
                                        if ($tour['nivel_dificultad'] === 'alto')
                                            $diffColor = 'danger';
                                        ?>
                                        <span
                                            class="badge bg-<?php echo $diffColor; ?> bg-opacity-25 text-<?php echo $diffColor; ?> border border-<?php echo $diffColor; ?>">
                                            <?php echo ucfirst($tour['nivel_dificultad']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="<?php echo BASE_URL; ?>agency/tours/edit?id=<?php echo $tour['id']; ?>"
                                            class="btn btn-sm btn-outline-primary me-1" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>agency/tours/delete?id=<?php echo $tour['id']; ?>"
                                            class="btn btn-sm btn-outline-danger" title="Eliminar"
                                            onclick="return confirm('¿Estás seguro de eliminar este tour?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
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
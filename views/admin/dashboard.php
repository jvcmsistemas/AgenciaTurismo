<?php include BASE_PATH . '/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold text-primary">Panel de Administración</h2>
        <p class="text-muted">Resumen global del sistema</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card glass-card border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="bi bi-building fs-3 text-primary"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">Agencias Totales</h6>
                    <h3 class="fw-bold mb-0"><?php echo count($agencies); ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card glass-card border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="bi bi-check-circle fs-3 text-success"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">Activas</h6>
                    <h3 class="fw-bold mb-0">
                        <?php echo count(array_filter($agencies, fn($a) => $a['estado'] === 'activa')); ?>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <!-- Placeholder for future stats -->
    <div class="col-md-3">
        <div class="card glass-card border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="bi bi-people fs-3 text-warning"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">Usuarios</h6>
                    <h3 class="fw-bold mb-0">-</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info glass-card border-0">
            <i class="bi bi-info-circle me-2"></i>
            Bienvenido al panel de administración. Selecciona una opción del menú lateral para comenzar.
        </div>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
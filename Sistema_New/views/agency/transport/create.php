<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="container-fluid py-4 fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1">Registrar Nueva Unidad</h2>
            <p class="text-muted small mb-0"><i class="bi bi-plus-circle me-1"></i> Añade un nuevo vehículo a tu flota
                logística.</p>
        </div>
        <a href="<?php echo BASE_URL; ?>agency/transport" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Volver al Listado
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card glass-card border-0 shadow-sm anim-slide-up">
                <div class="card-body p-4">
                    <form action="<?php echo BASE_URL; ?>agency/transport/store" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row g-4">
                            <!-- Datos del Vehículo -->
                            <div class="col-12">
                                <h5 class="fw-bold border-bottom pb-2 mb-3"><i
                                        class="bi bi-info-circle me-2 text-primary"></i>Información del Vehículo</h5>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Placa <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill px-3"><i
                                            class="bi bi-card-heading text-primary"></i></span>
                                    <input type="text" name="placa"
                                        class="form-control border-start-0 rounded-end-pill text-uppercase"
                                        placeholder="Ej: ABC-123" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Modelo / Descripción</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill px-3"><i
                                            class="bi bi-truck text-primary"></i></span>
                                    <input type="text" name="modelo"
                                        class="form-control border-start-0 rounded-end-pill"
                                        placeholder="Ej: Toyota Hiace 2023">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Capacidad (Asientos) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill px-3"><i
                                            class="bi bi-people text-primary"></i></span>
                                    <input type="number" name="capacidad"
                                        class="form-control border-start-0 rounded-end-pill" placeholder="Ej: 15"
                                        required>
                                </div>
                            </div>

                            <!-- Datos del Chofer -->
                            <div class="col-12 mt-5">
                                <h5 class="fw-bold border-bottom pb-2 mb-3"><i
                                        class="bi bi-person me-2 text-primary"></i>Chofer Asignado (Opcional)</h5>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nombre del Chofer</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill px-3"><i
                                            class="bi bi-person-badge text-primary"></i></span>
                                    <input type="text" name="chofer_nombre"
                                        class="form-control border-start-0 rounded-end-pill"
                                        placeholder="Nombre completo">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Teléfono del Chofer</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill px-3"><i
                                            class="bi bi-telephone text-primary"></i></span>
                                    <input type="text" name="chofer_telefono"
                                        class="form-control border-start-0 rounded-end-pill"
                                        placeholder="Ej: 987654321">
                                </div>
                            </div>

                            <div class="col-12 mt-4 text-end">
                                <hr class="my-4">
                                <a href="<?php echo BASE_URL; ?>agency/transport"
                                    class="btn btn-light rounded-pill px-4 me-2">
                                    <i class="bi bi-x-lg me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">
                                    <i class="bi bi-check-lg me-2"></i>Guardar Vehículo
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
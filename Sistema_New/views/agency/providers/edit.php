<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="container-fluid py-4 fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1">Editar Proveedor</h2>
            <p class="text-muted small mb-0"><i class="bi bi-pencil-square me-1"></i> Modifica la información de
                <strong>
                    <?php echo htmlspecialchars($provider['nombre']); ?>
                </strong>.
            </p>
        </div>
        <a href="<?php echo BASE_URL; ?>agency/providers" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Volver al Listado
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card glass-card border-0 shadow-sm anim-slide-up">
                <div class="card-body p-4">
                    <form action="<?php echo BASE_URL; ?>agency/providers/update" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo $provider['id']; ?>">

                        <div class="row g-4">
                            <div class="col-12">
                                <h5 class="fw-bold border-bottom pb-2 mb-3"><i
                                        class="bi bi-info-circle me-2 text-primary"></i>Información del Proveedor</h5>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold">Nombre del Establecimiento <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill px-3"><i
                                            class="bi bi-building text-primary"></i></span>
                                    <input type="text" name="nombre"
                                        class="form-control border-start-0 rounded-end-pill"
                                        value="<?php echo htmlspecialchars($provider['nombre']); ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Tipo de Servicio <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill px-3"><i
                                            class="bi bi-tag text-primary"></i></span>
                                    <select name="tipo" class="form-select border-start-0 rounded-end-pill" required>
                                        <option value="restaurante" <?php echo ($provider['tipo'] === 'restaurante') ? 'selected' : ''; ?>>Restaurante / Alimentación</option>
                                        <option value="hotel" <?php echo ($provider['tipo'] === 'hotel') ? 'selected' : ''; ?>>Hotel / Hospedaje</option>
                                        <option value="ticket" <?php echo ($provider['tipo'] === 'ticket') ? 'selected' : ''; ?>>Entradas / Tickets</option>
                                        <option value="transporte_externo" <?php echo ($provider['tipo'] === 'transporte_externo') ? 'selected' : ''; ?>>Transporte
                                            Externo</option>
                                        <option value="otro" <?php echo ($provider['tipo'] === 'otro') ? 'selected' : ''; ?>>
                                            Otro Servicio</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Teléfono de Contacto</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill px-3"><i
                                            class="bi bi-telephone text-primary"></i></span>
                                    <input type="text" name="telefono"
                                        class="form-control border-start-0 rounded-end-pill"
                                        value="<?php echo htmlspecialchars($provider['telefono'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill px-3"><i
                                            class="bi bi-envelope text-primary"></i></span>
                                    <input type="email" name="email"
                                        class="form-control border-start-0 rounded-end-pill"
                                        value="<?php echo htmlspecialchars($provider['email'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Estado</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill px-3"><i
                                            class="bi bi-toggle-on text-primary"></i></span>
                                    <select name="estado" class="form-select border-start-0 rounded-end-pill">
                                        <option value="activo" <?php echo ($provider['estado'] === 'activo') ? 'selected' : ''; ?>>Activo / Operativo</option>
                                        <option value="inactivo" <?php echo ($provider['estado'] === 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">Dirección</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill px-3"><i
                                            class="bi bi-geo-alt text-primary"></i></span>
                                    <input type="text" name="direccion"
                                        class="form-control border-start-0 rounded-end-pill"
                                        value="<?php echo htmlspecialchars($provider['ubicacion'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">Notas Adicionales</label>
                                <textarea name="notas" class="form-control rounded-3"
                                    rows="3"><?php echo htmlspecialchars($provider['notas'] ?? ''); ?></textarea>
                            </div>

                            <div class="col-12 mt-4 text-end">
                                <hr class="my-4">
                                <a href="<?php echo BASE_URL; ?>agency/providers"
                                    class="btn btn-light rounded-pill px-4 me-2">
                                    <i class="bi bi-x-lg me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">
                                    <i class="bi bi-save me-2"></i>Actualizar Información
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
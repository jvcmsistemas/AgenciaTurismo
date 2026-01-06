<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="row mb-4 fade-in">
    <div class="col-12">
        <h2 class="fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i>Editar Cliente</h2>
        <p class="text-muted">Actualizar datos del viajero.</p>
    </div>
</div>

<form action="<?php echo BASE_URL; ?>agency/clients/update" method="POST">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id" value="<?php echo $client['id']; ?>">

    <div class="card glass-card border-0 shadow-sm">
        <div class="card-header glass-header bg-transparent border-0 pt-4 pb-2">
            <h5 class="fw-bold text-primary"><i class="bi bi-person-vcard me-2"></i>Datos Personales</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label fw-bold small text-muted">NOMBRES</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="bi bi-person text-secondary"></i></span>
                        <input type="text" class="form-control border-start-0" id="nombre" name="nombre"
                            value="<?php echo htmlspecialchars($client['nombre']); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="apellido" class="form-label fw-bold small text-muted">APELLIDOS</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="bi bi-person text-secondary"></i></span>
                        <input type="text" class="form-control border-start-0" id="apellido" name="apellido"
                            value="<?php echo htmlspecialchars($client['apellido']); ?>" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="dni" class="form-label fw-bold small text-muted">DNI / PASAPORTE</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="bi bi-card-heading text-secondary"></i></span>
                        <input type="text" class="form-control border-start-0" id="dni" name="dni"
                            value="<?php echo htmlspecialchars($client['dni']); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="nacionalidad" class="form-label fw-bold small text-muted">NACIONALIDAD</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="bi bi-globe-americas text-secondary"></i></span>
                        <select class="form-select border-start-0" id="nacionalidad" name="nacionalidad">
                            <option value="Peruana" <?php echo ($client['nacionalidad'] == 'Peruana') ? 'selected' : ''; ?>>Peruana</option>
                            <option value="Extranjera" <?php echo ($client['nacionalidad'] == 'Extranjera') ? 'selected' : ''; ?>>Extranjera</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label fw-bold small text-muted">CORREO ELECTRÓNICO</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="bi bi-envelope text-secondary"></i></span>
                        <input type="email" class="form-control border-start-0" id="email" name="email"
                            value="<?php echo htmlspecialchars($client['email']); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="telefono" class="form-label fw-bold small text-muted">TELÉFONO / WHATSAPP</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="bi bi-whatsapp text-secondary"></i></span>
                        <input type="text" class="form-control border-start-0" id="telefono" name="telefono"
                            value="<?php echo htmlspecialchars($client['telefono']); ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="ruc" class="form-label fw-bold small text-muted">RUC (OPCIONAL)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="bi bi-building text-secondary"></i></span>
                        <input type="text" class="form-control border-start-0" id="ruc" name="ruc"
                            value="<?php echo htmlspecialchars($client['ruc']); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 mb-5">
        <div class="col-12 text-end">
            <a href="<?php echo BASE_URL; ?>agency/clients" class="btn btn-outline-secondary btn-lg me-2">Cancelar</a>
            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                <i class="bi bi-save me-2"></i>Guardar Cambios
            </button>
        </div>
    </div>
</form>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
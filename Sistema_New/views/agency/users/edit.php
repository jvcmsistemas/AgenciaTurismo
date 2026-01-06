<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="container container-custom py-5 fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card glass-card border-0 shadow-lg anim-slide-up">
                <div class="card-header bg-primary text-white text-center py-4 border-0 rounded-top-4">
                    <div class="rounded-circle bg-white bg-opacity-25 d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-person-gear fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-0">Editar Colaborador</h3>
                    <p class="text-white-50 small mb-0">Modifica los datos y permisos del trabajador</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="<?php echo BASE_URL; ?>agency/users/update" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nombre <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill px-3">
                                        <i class="bi bi-person text-primary"></i>
                                    </span>
                                    <input type="text" name="nombre"
                                        class="form-control border-start-0 rounded-end-pill"
                                        value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Apellido <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill px-3">
                                        <i class="bi bi-person text-primary"></i>
                                    </span>
                                    <input type="text" name="apellido"
                                        class="form-control border-start-0 rounded-end-pill"
                                        value="<?php echo htmlspecialchars($user['apellido']); ?>" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Correo Electrónico <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill px-3">
                                        <i class="bi bi-envelope text-primary"></i>
                                    </span>
                                    <input type="email" name="email"
                                        class="form-control border-start-0 rounded-end-pill"
                                        value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Estado de Acceso</label>
                                <select name="es_activo" class="form-select rounded-pill">
                                    <option value="1" <?php echo $user['es_activo'] ? 'selected' : ''; ?>>Activo (Con
                                        acceso)</option>
                                    <option value="0" <?php echo !$user['es_activo'] ? 'selected' : ''; ?>>Inactivo
                                        (Bloqueado)</option>
                                </select>
                            </div>

                            <div class="col-md-6 text-end d-flex align-items-end justify-content-end">
                                <span
                                    class="badge <?php echo $user['es_activo'] ? 'bg-success' : 'bg-danger'; ?> rounded-pill px-3 mb-2">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>
                                    <?php echo $user['es_activo'] ? 'Actualmente Activo' : 'Actualmente Inactivo'; ?>
                                </span>
                            </div>

                            <div class="col-12 mt-4 pt-3 border-top">
                                <label class="form-label small fw-bold text-danger">Cambiar Contraseña
                                    (Opcional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill px-3">
                                        <i class="bi bi-key text-danger"></i>
                                    </span>
                                    <input type="password" name="password"
                                        class="form-control border-start-0 rounded-end-pill"
                                        placeholder="Dejar en blanco para no cambiar">
                                </div>
                                <div class="form-text x-small ms-2">Solo completa este campo si deseas resetear la clave
                                    del colaborador.</div>
                            </div>

                            <div class="col-12 mt-4 text-center">
                                <hr class="my-4">
                                <a href="<?php echo BASE_URL; ?>agency/users"
                                    class="btn btn-light rounded-pill px-4 me-2">
                                    <i class="bi bi-x-lg me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">
                                    <i class="bi bi-save me-2"></i>Guardar Cambios
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
<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="container container-custom py-5 fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card glass-card border-0 shadow-lg anim-slide-up">
                <div class="card-header bg-primary text-white text-center py-4 border-0 rounded-top-4">
                    <div class="rounded-circle bg-white bg-opacity-25 d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-person-plus-fill fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-0">Nuevo Colaborador</h3>
                    <p class="text-white-50 small mb-0">Registra un nuevo miembro para tu equipo</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger rounded-4 small mb-4">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <?php echo $_SESSION['error'];
                            unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>agency/users/store" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nombre <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span
                                        class="input-group-text bg-light-dynamic border-dynamic border-end-0 rounded-start-pill px-3">
                                        <i class="bi bi-person text-primary"></i>
                                    </span>
                                    <input type="text" name="nombre"
                                        class="form-control border-dynamic border-start-0 rounded-end-pill bg-dynamic"
                                        placeholder="Ej: Juan" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Apellido <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span
                                        class="input-group-text bg-light-dynamic border-dynamic border-end-0 rounded-start-pill px-3">
                                        <i class="bi bi-person text-primary"></i>
                                    </span>
                                    <input type="text" name="apellido"
                                        class="form-control border-dynamic border-start-0 rounded-end-pill bg-dynamic"
                                        placeholder="Ej: Pérez" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Correo Electrónico <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span
                                        class="input-group-text bg-light-dynamic border-dynamic border-end-0 rounded-start-pill px-3">
                                        <i class="bi bi-envelope text-primary"></i>
                                    </span>
                                    <input type="email" name="email"
                                        class="form-control border-dynamic border-start-0 rounded-end-pill bg-dynamic"
                                        placeholder="correo@agencia.com" required>
                                </div>
                                <div class="form-text x-small ms-2">Este será su usuario para iniciar sesión.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Contraseña Temporal <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span
                                        class="input-group-text bg-light-dynamic border-dynamic border-end-0 rounded-start-pill px-3">
                                        <i class="bi bi-lock text-primary"></i>
                                    </span>
                                    <input type="password" name="password"
                                        class="form-control border-dynamic border-start-0 rounded-end-pill bg-dynamic"
                                        placeholder="Crea una clave inicial" required>
                                </div>
                            </div>

                            <div class="col-12 mt-4 text-center">
                                <hr class="my-4">
                                <a href="<?php echo BASE_URL; ?>agency/users"
                                    class="btn btn-light rounded-pill px-4 me-2">
                                    <i class="bi bi-x-lg me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">
                                    <i class="bi bi-check-lg me-2"></i>Registrar Colaborador
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted small">
                    <i class="bi bi-info-circle me-1"></i> El trabajador tendrá rol de <strong>Empleado</strong>
                    automáticamente.
                </p>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
<?php include BASE_PATH . '/views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">Mi Perfil</h2>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                Perfil actualizado correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card glass-card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-5">
                    <div class="avatar-lg bg-primary bg-gradient text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center shadow"
                        style="width: 100px; height: 100px; font-size: 2.5rem;">
                        <?php echo strtoupper(substr($user['nombre'], 0, 1)); ?>
                    </div>
                    <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?>
                    </h4>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">Administrador
                        General</span>
                </div>

                <form action="<?php echo BASE_URL; ?>admin/profile/update" method="POST">
                    <h5 class="text-primary fw-bold mb-4"><i class="bi bi-person me-2"></i>Información Personal</h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control bg-light"
                                value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control bg-light"
                                value="<?php echo htmlspecialchars($user['apellido']); ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control bg-light"
                                value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                    </div>

                    <h5 class="text-primary fw-bold mb-4 mt-5"><i class="bi bi-shield-lock me-2"></i>Seguridad</h5>
                    <div class="alert alert-light border shadow-sm mb-4">
                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Deje los campos de contraseña en
                            blanco si no desea cambiarla.</small>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" name="password" class="form-control bg-light" minlength="6">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmar Contraseña</label>
                            <input type="password" name="password_confirm" class="form-control bg-light" minlength="6">
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                        <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm hover-scale">
                            <i class="bi bi-check-circle-fill me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
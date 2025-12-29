<?php include BASE_PATH . '/views/layouts/header.php'; ?>

<div class="login-page-agency d-flex align-items-center justify-content-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="glass-card p-4 p-md-5 animate__animated animate__fadeInUp">
                    <div class="text-center mb-4">
                        <div class="login-icon-container mb-3">
                            <i class="bi bi-key fs-1 text-white"></i>
                        </div>
                        <h3 class="fw-bold text-white">Nueva Contraseña</h3>
                        <p class="text-white-50 small">Ingresa tu nueva contraseña segura.</p>
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger bg-danger bg-opacity-25 text-white border-0 small mb-4">
                            <i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>reset-password/update" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

                        <div class="mb-3">
                            <div class="input-group glass-input-group">
                                <span class="input-group-text bg-transparent border-0 text-white-50">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" name="password"
                                    class="form-control bg-transparent border-0 text-white placeholder-white-50"
                                    placeholder="Nueva Contraseña" required minlength="6">
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="input-group glass-input-group">
                                <span class="input-group-text bg-transparent border-0 text-white-50">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" name="password_confirm"
                                    class="form-control bg-transparent border-0 text-white placeholder-white-50"
                                    placeholder="Confirmar Contraseña" required minlength="6">
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-light fw-bold py-2 text-primary shadow-sm">
                                Cambiar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
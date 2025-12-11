<?php include BASE_PATH . '/views/layouts/header.php'; ?>

<div class="login-page-admin">
    <div class="glass-card login-card">
        <div class="text-center mb-5">
            <h2 class="login-title fw-bold text-white mb-1">TURISMO</h2>
            <p class="text-white-50 small text-uppercase letter-spacing-2">Sistema de Gestión</p>
        </div>

        <?php if (isset($error)): ?>
            <div
                class="alert alert-danger border-0 bg-danger bg-opacity-25 text-white shadow-sm mb-4 d-flex align-items-center">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <div><?php echo $error; ?></div>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <div class="login-input-group d-flex align-items-center">
                    <i class="bi bi-person-badge fs-5"></i>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Usuario Administrador"
                        required>
                </div>
            </div>

            <div class="mb-5">
                <div class="login-input-group d-flex align-items-center">
                    <i class="bi bi-lock-fill fs-5"></i>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Contraseña de Acceso" required>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit"
                    class="btn btn-primary btn-lg rounded-pill shadow-lg hover-scale fw-bold text-uppercase"
                    style="letter-spacing: 1px;">
                    Ingresar
                </button>
            </div>

            <div class="text-center mt-5">
                <a href="<?php echo BASE_URL; ?>login"
                    class="text-decoration-none small text-white-50 hover-white transition-all">
                    <i class="bi bi-arrow-left me-1"></i>Acceso a Agencias
                </a>
            </div>
        </form>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
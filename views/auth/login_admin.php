<?php include BASE_PATH . '/views/layouts/header.php'; ?>

<div class="login-page-admin">
    <div class="glass-card login-card bg-dark bg-opacity-50 text-white border-secondary">
        <div class="text-center mb-4">
            <h3 class="login-title fw-bold">Administración</h3>
            <p class="text-white-50">Acceso Restringido</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger border-0 shadow-sm"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label for="email" class="form-label text-white-50 small fw-bold">EMAIL</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-white-50 rounded-start"><i
                            class="bi bi-shield-lock"></i></span>
                    <input type="email" class="form-control bg-dark border-secondary text-white ps-2" id="email"
                        name="email" placeholder="admin@agencia.com" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label text-white-50 small fw-bold">CONTRASEÑA</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-white-50 rounded-start"><i
                            class="bi bi-key"></i></span>
                    <input type="password" class="form-control bg-dark border-secondary text-white ps-2" id="password"
                        name="password" placeholder="••••••••" required>
                </div>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg text-white">ACCEDER</button>
            </div>
            <div class="text-center mt-3">
                <a href="<?php echo BASE_URL; ?>login" class="text-decoration-none small text-white-50 hover-white">Ir
                    al Login de Agencias</a>
            </div>
        </form>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
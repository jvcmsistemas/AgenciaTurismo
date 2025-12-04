<?php include BASE_PATH . '/views/layouts/header.php'; ?>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="glass-card p-5" style="width: 100%; max-width: 400px;">
        <h2 class="login-title">Iniciar Sesión</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label for="email" class="form-label text-muted small fw-bold">EMAIL</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start"><i
                            class="bi bi-envelope text-primary"></i></span>
                    <input type="email" class="form-control border-start-0 ps-0" id="email" name="email"
                        placeholder="nombre@ejemplo.com" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label text-muted small fw-bold">CONTRASEÑA</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start"><i
                            class="bi bi-lock text-primary"></i></span>
                    <input type="password" class="form-control border-start-0 ps-0" id="password" name="password"
                        placeholder="••••••••" required>
                </div>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg text-white">INGRESAR</button>
            </div>
            <!-- <div class="text-center mt-3">
                <a href="<?php echo BASE_URL; ?>forgot-password"
                    class="text-decoration-none small text-muted">¿Olvidaste tu contraseña?</a>
            </div> -->
        </form>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
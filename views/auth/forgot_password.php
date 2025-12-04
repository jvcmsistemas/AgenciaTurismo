<?php include BASE_PATH . '/views/layouts/header.php'; ?>

<div class="login-page-agency d-flex align-items-center justify-content-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="glass-card p-4 p-md-5 animate__animated animate__fadeInUp">
                    <div class="text-center mb-4">
                        <div class="login-icon-container mb-3">
                            <i class="bi bi-envelope-exclamation fs-1 text-white"></i>
                        </div>
                        <h3 class="fw-bold text-white">Recuperar Contraseña</h3>
                        <p class="text-white-50 small">Ingresa tu correo para recibir el enlace de recuperación.</p>
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger bg-danger bg-opacity-25 text-white border-0 small mb-4">
                            <i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($success)): ?>
                        <div class="alert alert-success bg-success bg-opacity-25 text-white border-0 small mb-4">
                            <i class="bi bi-check-circle me-2"></i><?php echo $success; ?>
                        </div>

                        <?php if (isset($resetLink)): ?>
                            <div class="alert alert-info bg-info bg-opacity-25 text-white border-0 small mb-4 text-break">
                                <strong>¡Simulación Local!</strong><br>
                                Haz clic aquí para restablecer:<br>
                                <a href="<?php echo $resetLink; ?>"
                                    class="text-white fw-bold text-decoration-underline">Restablecer Contraseña</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>forgot-password/send" method="POST">
                        <div class="mb-4">
                            <div class="input-group glass-input-group">
                                <span class="input-group-text bg-transparent border-0 text-white-50">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" name="email"
                                    class="form-control bg-transparent border-0 text-white placeholder-white-50"
                                    placeholder="Correo electrónico" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-light fw-bold py-2 text-primary shadow-sm">
                                Enviar Enlace
                            </button>
                            <a href="<?php echo BASE_URL; ?>login" class="btn btn-outline-light btn-sm mt-2 border-0">
                                <i class="bi bi-arrow-left me-1"></i> Volver al Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
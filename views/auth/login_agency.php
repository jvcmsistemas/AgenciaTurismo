<?php include BASE_PATH . '/views/layouts/header.php'; ?>

<div class="login-page-agency">
    <!-- Animated Background Elements -->
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <div class="container d-flex justify-content-center align-items-center min-vh-100 position-relative"
        style="z-index: 10;">
        <div class="row w-100 justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card glass-card-premium border-0 overflow-hidden animate-fade-in-up">
                    <!-- Card Glow Effect -->
                    <div class="card-glow"></div>

                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <div class="logo-container mb-3">
                                <i class="bi bi-compass-fill text-primary fs-1"></i>
                            </div>
                            <h2 class="fw-bold text-white mb-1">Portal de Agencias</h2>
                            <p class="text-white-50">Bienvenido de nuevo, aventurero.</p>
                        </div>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger-glass border-0 shadow-sm animate-shake mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-octagon-fill me-2 fs-5"></i>
                                    <div><?php echo $error; ?></div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST" class="auth-form">
                            <?php echo csrf_field(); ?>

                            <div class="mb-4">
                                <label for="email" class="form-label-glass">Email Corporativo</label>
                                <div class="input-group-glass">
                                    <span class="input-group-text-glass"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control-glass shadow-none" id="email" name="email"
                                        placeholder="nombre@agencia.com" required focus>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="password" class="form-label-glass mb-0">Contraseña</label>
                                    <a href="<?php echo BASE_URL; ?>forgot-password"
                                        class="text-primary-glass small">¿Olvidaste tu contraseña?</a>
                                </div>
                                <div class="input-group-glass">
                                    <span class="input-group-text-glass"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control-glass shadow-none" id="password"
                                        name="password" placeholder="••••••••" required>
                                    <button class="btn btn-link-glass" type="button" onclick="togglePassword()">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-5">
                                <div class="form-check">
                                    <input class="form-check-input-glass" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label-glass" for="remember">Mantener sesión
                                        iniciada</label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary-premium btn-lg shadow-lg">
                                    <span>Ingresar al Sistema</span>
                                    <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>

                        <div class="mt-5 pt-4 border-top border-white border-opacity-10 text-center">
                            <p class="text-white-50 small mb-0">¿No tienes acceso? Contacta con el <a href="#"
                                    class="text-white fw-bold">soporte central</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-glass: #10b981;
        /* Emerald 500 */
        --secondary-glass: #34d399;
        /* Emerald 400 */
        --accent-glass: #059669;
        /* Emerald 600 */
        --bg-glass: rgba(6, 78, 59, 0.4);
        /* Dark Greenish Tint */
        --border-glass: rgba(255, 255, 255, 0.15);
    }

    .login-page-agency {
        min-height: 100vh;
        background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)),
            url('<?php echo BASE_URL; ?>public/img/agency_login_background.png') no-repeat center center fixed;
        background-size: cover;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-page-agency::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 30% 20%, rgba(16, 185, 129, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(5, 150, 105, 0.1) 0%, transparent 50%);
        backdrop-filter: blur(8px);
    }

    /* Floating Shapes - More organic for Nature */
    .bg-shape {
        position: absolute;
        border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
        /* Organic blob */
        filter: blur(100px);
        z-index: 1;
        opacity: 0.4;
        animation: float-shapes 25s infinite alternate ease-in-out;
    }

    .shape-1 {
        width: 500px;
        height: 500px;
        background: var(--primary-glass);
        top: -150px;
        left: -150px;
    }

    .shape-2 {
        width: 400px;
        height: 400px;
        background: var(--accent-glass);
        bottom: -100px;
        right: -100px;
        animation-delay: -7s;
    }

    @keyframes float-shapes {
        0% {
            transform: translate(0, 0) scale(1);
        }

        100% {
            transform: translate(100px, 100px) scale(1.2);
        }
    }

    /* Glass Card Premium - Forced Override of global .card */
    .glass-card-premium.card {
        background: var(--bg-glass) !important;
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid var(--border-glass) !important;
        border-radius: 24px;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .card-glow {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, transparent 100%);
        pointer-events: none;
    }

    /* Form Elements Glass */
    .form-label-glass {
        color: rgba(255, 255, 255, 0.9) !important;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        display: block;
    }

    .input-group-glass {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid var(--border-glass) !important;
        border-radius: 12px;
        transition: all 0.3s ease;
        display: flex;
        overflow: hidden;
    }

    .input-group-glass:focus-within {
        border-color: var(--primary-glass) !important;
        box-shadow: 0 0 15px rgba(16, 185, 129, 0.2);
        background: rgba(255, 255, 255, 0.08) !important;
    }

    .input-group-text-glass {
        background: transparent !important;
        border: none !important;
        color: rgba(255, 255, 255, 0.6) !important;
        padding: 0.75rem 1rem;
    }

    .form-control-glass {
        background: transparent !important;
        border: none !important;
        color: white !important;
        padding: 0.75rem 0.5rem;
        width: 100%;
    }

    .form-control-glass::placeholder {
        color: rgba(255, 255, 255, 0.3) !important;
    }

    .btn-link-glass {
        background: transparent !important;
        border: none !important;
        color: rgba(255, 255, 255, 0.4) !important;
        padding: 0 1rem;
    }

    .btn-link-glass:hover {
        color: var(--primary-glass) !important;
    }

    .text-primary-glass {
        color: var(--primary-glass) !important;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.8rem;
    }

    .text-primary-glass:hover {
        text-decoration: underline;
    }

    /* Checkbox Glass */
    .form-check-input-glass {
        background-color: rgba(255, 255, 255, 0.1) !important;
        border: 1px solid var(--border-glass) !important;
    }

    .form-check-label-glass {
        color: rgba(255, 255, 255, 0.7) !important;
        font-size: 0.9rem;
    }

    /* Button Premium - Switched to Emerald Theme */
    .btn-primary-premium {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        border: none !important;
        border-radius: 12px;
        padding: 0.8rem;
        font-weight: 700;
        color: white !important;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-primary-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        filter: brightness(1.1);
    }

    /* Alert Glass */
    .alert-danger-glass {
        background: rgba(239, 68, 68, 0.2) !important;
        color: #fecaca !important;
        backdrop-filter: blur(10px);
        padding: 1rem;
        border-radius: 12px;
        border: 1px solid rgba(239, 68, 68, 0.3) !important;
    }

    /* Animations */
    .animate-shake {
        animation: shake 0.5s cubic-bezier(.36, .07, .19, .97) both;
    }

    @keyframes shake {

        10%,
        90% {
            transform: translate3d(-1px, 0, 0);
        }

        20%,
        80% {
            transform: translate3d(2px, 0, 0);
        }

        30%,
        50%,
        70% {
            transform: translate3d(-4px, 0, 0);
        }

        40%,
        60% {
            transform: translate3d(4px, 0, 0);
        }
    }
</style>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
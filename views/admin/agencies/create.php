<?php include BASE_PATH . '/views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-1">Registrar Nueva Agencia</h2>
                <p class="text-muted mb-0">Complete la información para dar de alta una nueva empresa turística.</p>
            </div>
            <a href="<?php echo BASE_URL; ?>admin/agencies"
                class="btn btn-light rounded-pill px-4 shadow-sm text-primary fw-medium">
                <i class="bi bi-arrow-left me-2"></i>Volver al Listado
            </a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-circle-fill fs-4 me-3"></i>
                    <div>
                        <strong>¡Atención!</strong> <?php echo $error; ?>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>admin/agencies/store" method="POST"
            class="card glass-card border-0 shadow-lg overflow-hidden">

            <!-- Progress Header (Visual only) -->
            <div class="card-header bg-primary bg-gradient text-white p-4 border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="bi bi-shop-window fs-3 text-white"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 text-white">Formulario de Alta</h5>
                        <small class="text-white-50">Los campos marcados con <span class="text-white">*</span> son
                            obligatorios</small>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">

                <!-- Sección 1: Datos de la Agencia -->
                <div class="mb-5">
                    <h5 class="text-primary fw-bold mb-4 d-flex align-items-center">
                        <span
                            class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center me-3"
                            style="width: 32px; height: 32px;">1</span>
                        Información Corporativa
                    </h5>

                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-medium text-secondary">Nombre Comercial <span
                                    class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                        class="bi bi-building"></i></span>
                                <input type="text" name="nombre_agencia" class="form-control bg-light border-start-0"
                                    required placeholder="Ej. Cusco Expeditions">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary">Teléfono de Contacto</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                        class="bi bi-telephone"></i></span>
                                <input type="text" name="telefono" class="form-control bg-light border-start-0"
                                    placeholder="+51 999 999 999">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary">Email Corporativo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                        class="bi bi-envelope"></i></span>
                                <input type="email" name="email_agencia" class="form-control bg-light border-start-0"
                                    placeholder="contacto@empresa.com">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-medium text-secondary">Dirección Física</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                        class="bi bi-geo-alt"></i></span>
                                <input type="text" name="direccion" class="form-control bg-light border-start-0"
                                    placeholder="Av. Principal 123, Oficina 204">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-secondary opacity-10 my-5">

                <!-- Sección 2: Suscripción -->
                <div class="mb-5">
                    <h5 class="text-primary fw-bold mb-4 d-flex align-items-center">
                        <span
                            class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center me-3"
                            style="width: 32px; height: 32px;">2</span>
                        Plan y Suscripción
                    </h5>

                    <div class="card bg-light border-0 p-3 mb-3">
                        <div class="row g-4 align-items-center">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Seleccione un Plan <span
                                        class="text-danger">*</span></label>
                                <select name="tipo_suscripcion" id="tipo_suscripcion"
                                    class="form-select form-select-lg shadow-sm border-0" required
                                    onchange="updateVencimiento()">
                                    <option value="prueba">🚀 Prueba Gratuita (1 Mes)</option>
                                    <option value="basico">⭐ Plan Básico (6 Meses)</option>
                                    <option value="premium">👑 Plan Premium (1 Año)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Vencimiento Estimado</label>
                                <div class="d-flex align-items-center bg-white rounded-3 p-2 shadow-sm border">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                                        <i class="bi bi-calendar-check fs-4"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block text-uppercase"
                                            style="font-size: 0.7rem; letter-spacing: 1px;">Válido Hasta</small>
                                        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento"
                                            class="form-control border-0 bg-transparent fw-bold text-dark p-0"
                                            style="font-size: 1.1rem;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-secondary opacity-10 my-5">

                <!-- Sección 3: Datos del Dueño -->
                <div class="mb-5">
                    <h5 class="text-primary fw-bold mb-4 d-flex align-items-center">
                        <span
                            class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center me-3"
                            style="width: 32px; height: 32px;">3</span>
                        Credenciales de Acceso
                    </h5>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary">Nombre <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="nombre_dueno" class="form-control bg-light" required
                                placeholder="Nombre del administrador">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary">Apellido <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="apellido_dueno" class="form-control bg-light" required
                                placeholder="Apellido paterno">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-medium text-secondary">Email de Login <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                        class="bi bi-person-badge"></i></span>
                                <input type="email" name="email_dueno" class="form-control bg-light border-start-0"
                                    required placeholder="usuario@sistema.com">
                            </div>
                            <div class="form-text ms-2"><i class="bi bi-info-circle me-1"></i>Este será el usuario para
                                ingresar al panel de agencia.</div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-medium text-secondary">Contraseña Temporal <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                        class="bi bi-key"></i></span>
                                <input type="password" name="password" id="password"
                                    class="form-control bg-light border-start-0 border-end-0" required minlength="6"
                                    placeholder="••••••••">
                                <button class="btn btn-light border border-start-0 text-muted" type="button"
                                    onclick="togglePassword('password')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="form-text ms-2">Mínimo 6 caracteres. El usuario podrá cambiarla en su primer
                                inicio de sesión.</div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5 pt-3">
                    <a href="<?php echo BASE_URL; ?>admin/agencies"
                        class="btn btn-light btn-lg px-5 rounded-pill me-md-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm hover-scale">
                        <i class="bi bi-check-circle-fill me-2"></i>Registrar Agencia
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        if (input.type === "password") {
            input.type = "text";
        } else {
            input.type = "password";
        }
    }

    function updateVencimiento() {
        const plan = document.getElementById('tipo_suscripcion').value;
        const display = document.getElementById('fecha_vencimiento');
        const date = new Date();

        if (plan === 'prueba') {
            date.setMonth(date.getMonth() + 1);
        } else if (plan === 'basico') {
            date.setMonth(date.getMonth() + 6);
        } else if (plan === 'premium') {
            date.setFullYear(date.getFullYear() + 1);
        }

        // Formato YYYY-MM-DD para input type="date"
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();

        display.value = `${year}-${month}-${day}`;
    }

    // Inicializar fecha
    document.addEventListener('DOMContentLoaded', updateVencimiento);
</script>

<style>
    .hover-scale {
        transition: transform 0.2s;
    }

    .hover-scale:hover {
        transform: translateY(-2px);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.1);
    }

    .input-group-text {
        border-color: #dee2e6;
    }
</style>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
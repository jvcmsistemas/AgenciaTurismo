<?php include BASE_PATH . '/views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-1">Editar Agencia</h2>
                <p class="text-muted mb-0">Modifique la información de la agencia y su suscripción.</p>
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

        <form action="<?php echo BASE_URL; ?>admin/agencies/update" method="POST"
            class="card glass-card border-0 shadow-lg overflow-hidden">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo $agency['id']; ?>">
            <input type="hidden" name="dueno_id" value="<?php echo $agency['dueno_id']; ?>">

            <!-- Header Visual -->
            <div class="card-header bg-primary bg-gradient text-white p-4 border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="bi bi-pencil-square fs-3 text-white"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 text-white">Edición de Datos</h5>
                        <small class="text-white-50">ID de Agencia: #<?php echo $agency['id']; ?></small>
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
                            <label class="form-label fw-medium text-secondary">Nombre Comercial</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                        class="bi bi-building"></i></span>
                                <input type="text" name="nombre_agencia" class="form-control bg-light border-start-0"
                                    required value="<?php echo htmlspecialchars($agency['nombre']); ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary">Teléfono de Contacto</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                        class="bi bi-telephone"></i></span>
                                <input type="text" name="telefono" class="form-control bg-light border-start-0"
                                    value="<?php echo htmlspecialchars($agency['telefono']); ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary">Email Corporativo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                        class="bi bi-envelope"></i></span>
                                <input type="email" name="email_agencia" class="form-control bg-light border-start-0"
                                    value="<?php echo htmlspecialchars($agency['email']); ?>">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-medium text-secondary">Dirección Física</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                        class="bi bi-geo-alt"></i></span>
                                <input type="text" name="direccion" class="form-control bg-light border-start-0"
                                    value="<?php echo htmlspecialchars($agency['direccion']); ?>">
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
                                <label class="form-label fw-medium text-secondary">Plan Actual</label>
                                <select name="tipo_suscripcion" class="form-select form-select-lg shadow-sm border-0">
                                    <option value="prueba" <?php echo $agency['tipo_suscripcion'] === 'prueba' ? 'selected' : ''; ?>>🚀 Prueba Gratuita</option>
                                    <option value="basico" <?php echo $agency['tipo_suscripcion'] === 'basico' ? 'selected' : ''; ?>>⭐ Plan Básico</option>
                                    <option value="premium" <?php echo $agency['tipo_suscripcion'] === 'premium' ? 'selected' : ''; ?>>👑 Plan Premium</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Fecha de Vencimiento</label>
                                <div class="d-flex align-items-center bg-white rounded-3 p-2 shadow-sm border">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                                        <i class="bi bi-calendar-check fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block text-uppercase"
                                            style="font-size: 0.7rem; letter-spacing: 1px;">Válido Hasta</small>
                                        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento"
                                            class="form-control border-0 p-0 fw-bold text-primary"
                                            value="<?php echo date('Y-m-d', strtotime($agency['fecha_vencimiento'])); ?>">
                                        <small class="text-muted fst-italic" style="font-size: 0.75rem;">
                                            Actual:
                                            <?php echo date('d/m/Y', strtotime($agency['fecha_vencimiento'])); ?>
                                        </small>
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
                            <label class="form-label fw-medium text-secondary">Nombre</label>
                            <input type="text" name="nombre_dueno" class="form-control bg-light" required
                                value="<?php echo htmlspecialchars($agency['dueno_nombre']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary">Apellido</label>
                            <input type="text" name="apellido_dueno" class="form-control bg-light" required
                                value="<?php echo htmlspecialchars($agency['dueno_apellido']); ?>">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-medium text-secondary">Email de Login</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                        class="bi bi-person-badge"></i></span>
                                <input type="email" name="email_dueno" class="form-control bg-light border-start-0"
                                    required value="<?php echo htmlspecialchars($agency['dueno_email']); ?>">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-medium text-secondary">Nueva Contraseña (Opcional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                        class="bi bi-key"></i></span>
                                <input type="password" name="password_dueno" id="password"
                                    class="form-control bg-light border-start-0 border-end-0"
                                    placeholder="Dejar en blanco para mantener la actual" autocomplete="new-password">
                                <button class="btn btn-light border border-start-0 text-muted" type="button"
                                    onclick="togglePassword('password')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="form-text ms-2">Solo llene este campo si desea cambiar la contraseña del
                                usuario.</div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5 pt-3">
                    <a href="<?php echo BASE_URL; ?>admin/agencies"
                        class="btn btn-light btn-lg px-5 rounded-pill me-md-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm hover-scale">
                        <i class="bi bi-save-fill me-2"></i>Guardar Cambios
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

    document.addEventListener('DOMContentLoaded', function () {
        const planSelect = document.querySelector('select[name="tipo_suscripcion"]');
        const dateInput = document.getElementById('fecha_vencimiento');

        // Save initial date to prevent accumulation errors
        // If empty, use today
        const originalDateValue = dateInput.value || new Date().toISOString().split('T')[0];

        planSelect.addEventListener('change', function () {
            // Re-parse original date every time
            const parts = originalDateValue.split('-');
            // Month is 0-indexed in JS Date constructor
            const baseDate = new Date(parts[0], parts[1] - 1, parts[2]);

            const plan = this.value;

            if (plan === 'prueba') {
                baseDate.setMonth(baseDate.getMonth() + 1);
            } else if (plan === 'basico') {
                baseDate.setMonth(baseDate.getMonth() + 6);
            } else if (plan === 'premium') {
                baseDate.setFullYear(baseDate.getFullYear() + 1);
            }

            // Format to YYYY-MM-DD
            const y = baseDate.getFullYear();
            const m = String(baseDate.getMonth() + 1).padStart(2, '0');
            const d = String(baseDate.getDate()).padStart(2, '0');

            dateInput.value = `${y}-${m}-${d}`;
        });
    });
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
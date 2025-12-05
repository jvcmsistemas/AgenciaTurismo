<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="row mb-4 fade-in">
    <div class="col-12">
        <h2 class="fw-bold text-primary"><i class="bi bi-person-gear me-2"></i>Mi Perfil</h2>
        <p class="text-muted">Gestiona la información de tu agencia y tu cuenta personal.</p>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show glass-card border-0 text-success fw-bold" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> ¡Perfil actualizado correctamente!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form action="<?php echo BASE_URL; ?>agency/profile/update" method="POST">
    <!-- Campos ocultos para mantener datos que no se editan aquí pero requiere el modelo -->
    <input type="hidden" name="tipo_suscripcion" value="<?php echo $agency['tipo_suscripcion']; ?>">
    <input type="hidden" name="fecha_vencimiento" value="<?php echo $agency['fecha_vencimiento']; ?>">

    <div class="row g-4">
        <!-- Columna Agencia -->
        <div class="col-md-6">
            <div class="card glass-card h-100 border-0">
                <div class="card-header glass-header bg-transparent border-0 pt-4 pb-2">
                    <h4 class="card-title fw-bold text-primary"><i class="bi bi-building me-2"></i>Datos de la Agencia
                    </h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="nombre_agencia" class="form-label fw-bold text-muted">Nombre Comercial</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i
                                    class="bi bi-shop text-primary"></i></span>
                            <input type="text" class="form-control bg-light border-0" id="nombre_agencia"
                                name="nombre_agencia" value="<?php echo htmlspecialchars($agency['nombre']); ?>"
                                required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email_agencia" class="form-label fw-bold text-muted">Email Corporativo</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i
                                    class="bi bi-envelope-at text-primary"></i></span>
                            <input type="email" class="form-control bg-light border-0" id="email_agencia"
                                name="email_agencia" value="<?php echo htmlspecialchars($agency['email']); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label fw-bold text-muted">Teléfono / WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i
                                    class="bi bi-whatsapp text-primary"></i></span>
                            <input type="text" class="form-control bg-light border-0" id="telefono" name="telefono"
                                value="<?php echo htmlspecialchars($agency['telefono']); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label fw-bold text-muted">Dirección Fiscal</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i
                                    class="bi bi-geo-alt text-primary"></i></span>
                            <input type="text" class="form-control bg-light border-0" id="direccion" name="direccion"
                                value="<?php echo htmlspecialchars($agency['direccion']); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Dueño -->
        <div class="col-md-6">
            <div class="card glass-card h-100 border-0">
                <div class="card-header glass-header bg-transparent border-0 pt-4 pb-2">
                    <h4 class="card-title fw-bold text-secondary"><i class="bi bi-person-badge me-2"></i>Datos del Dueño
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre_usuario" class="form-label fw-bold text-muted">Nombre</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i
                                        class="bi bi-person text-secondary"></i></span>
                                <input type="text" class="form-control bg-light border-0" id="nombre_usuario"
                                    name="nombre_usuario"
                                    value="<?php echo htmlspecialchars($agency['dueno_nombre']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido_usuario" class="form-label fw-bold text-muted">Apellido</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i
                                        class="bi bi-person text-secondary"></i></span>
                                <input type="text" class="form-control bg-light border-0" id="apellido_usuario"
                                    name="apellido_usuario"
                                    value="<?php echo htmlspecialchars($agency['dueno_apellido']); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email_usuario" class="form-label fw-bold text-muted">Email Personal (Login)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i
                                    class="bi bi-envelope text-secondary"></i></span>
                            <input type="email" class="form-control bg-light border-0" id="email_usuario"
                                name="email_usuario" value="<?php echo htmlspecialchars($agency['dueno_email']); ?>"
                                required>
                        </div>
                    </div>

                    <hr class="my-4 text-muted">

                    <h5 class="fw-bold text-danger mb-3"><i class="bi bi-shield-lock me-2"></i>Seguridad</h5>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold text-muted">Nueva Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i
                                    class="bi bi-key text-danger"></i></span>
                            <input type="password" class="form-control bg-light border-0" id="password" name="password"
                                placeholder="Dejar en blanco para no cambiar">
                        </div>
                        <div class="form-text">Solo llena este campo si deseas cambiar tu contraseña actual.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 mb-5">
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                <i class="bi bi-save me-2"></i>Guardar Cambios
            </button>
        </div>
    </div>
</form>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
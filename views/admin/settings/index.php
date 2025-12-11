<?php
// Sistema_New/views/admin/settings/index.php
include BASE_PATH . '/views/layouts/header.php';
?>

<div class="row mb-4 animate-fade-in">
    <div class="col-md-6">
        <h2 class="mb-0">⚙️ Configuración del Sistema</h2>
        <p class="text-muted">Ajustes globales y reglas de negocio</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= BASE_URL ?>admin/settings/backup" class="btn btn-outline-primary">
            <i class="fas fa-download me-2"></i>Descargar Backup BD
        </a>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Configuración actualizada correctamente.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        Error al procesar la solicitud.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form action="<?= BASE_URL ?>admin/settings/update" method="POST">
    <div class="row animate-fade-in-up">

        <!-- Identidad (Grupo: General) -->
        <div class="col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="mb-0"><i class="fas fa-building me-2"></i>Identidad & Sistema</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nombre del Sitio</label>
                            <input type="text" class="form-control" name="site_name"
                                value="<?= htmlspecialchars($groupedSettings['general'][0]['valor'] ?? '') ?>">
                            <div class="form-text">Nombre visible en correos y títulos.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email de Soporte</label>
                            <input type="email" class="form-control" name="support_email"
                                value="<?= htmlspecialchars($groupedSettings['general'][1]['valor'] ?? '') ?>">
                            <div class="form-text">Para contacto de agencias.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Moneda Base</label>
                            <select class="form-select" name="currency">
                                <option value="PEN" <?= ($groupedSettings['general'][2]['valor'] ?? '') === 'PEN' ? 'selected' : '' ?>>Soles (PEN)</option>
                                <option value="USD" <?= ($groupedSettings['general'][2]['valor'] ?? '') === 'USD' ? 'selected' : '' ?>>Dólares (USD)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reglas de Negocio (Grupo: Negocio) -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Reglas de Negocio</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Días de Gracia (Post-Vencimiento)</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="grace_period_days"
                                value="<?= htmlspecialchars($groupedSettings['negocio'][0]['valor'] ?? '15') ?>">
                            <span class="input-group-text">Días</span>
                        </div>
                        <div class="form-text">Tiempo antes de inactivar una cuenta vencida.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Plan por Defecto (ID)</label>
                        <input type="number" class="form-control" name="default_plan_id"
                            value="<?= htmlspecialchars($groupedSettings['negocio'][1]['valor'] ?? '1') ?>">
                        <div class="form-text">ID del plan asignado al crear cuenta.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mantenimiento (Grupo: Sistema) -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="mb-0"><i class="fas fa-server me-2"></i>Sistema</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode"
                            value="1" <?= ($groupedSettings['sistema'][0]['valor'] ?? '') === '1' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="maintenance_mode">Modo Mantenimiento</label>
                        <div class="form-text">Si se activa, solo los administradores podrán acceder.</div>
                    </div>
                    <!-- Hidden input for checkbox '0' value handling -->
                    <input type="hidden" name="maintenance_mode" value="0">

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="allow_registrations"
                            name="allow_registrations" value="1" <?= ($groupedSettings['sistema'][1]['valor'] ?? '') === '1' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="allow_registrations">Permitir Nuevos Registros</label>
                    </div>
                    <input type="hidden" name="allow_registrations" value="0">
                </div>
            </div>
        </div>

        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-2"></i>Guardar Cambios
            </button>
        </div>
    </div>
</form>

<script>
    // Fix for checkbox sending value '1' only when checked
    document.querySelectorAll('.form-check-input').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            // Find hidden input with same name
            const hiddenInput = this.parentNode.nextElementSibling;
            if (hiddenInput && hiddenInput.type === 'hidden' && hiddenInput.name === this.name) {
                hiddenInput.disabled = this.checked;
            }
        });
        // Initial state
        const hiddenInput = checkbox.parentNode.nextElementSibling;
        if (hiddenInput && hiddenInput.type === 'hidden' && hiddenInput.name === checkbox.name) {
            hiddenInput.disabled = checkbox.checked;
        }
    });
</script>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
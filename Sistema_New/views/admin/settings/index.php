<?php
// Sistema_New/views/admin/settings/index.php
include BASE_PATH . '/views/layouts/header.php';

// Helper to find a setting value in the grouped array by its key
function getSettingValue($grouped, $group, $key) {
    if (!isset($grouped[$group])) return '';
    foreach ($grouped[$group] as $setting) {
        if ($setting['clave'] === $key) return $setting['valor'];
    }
    return '';
}

$siteName = getSettingValue($groupedSettings, 'general', 'site_name');
$supportEmail = getSettingValue($groupedSettings, 'general', 'support_email');
$currency = getSettingValue($groupedSettings, 'general', 'currency');
$gracePeriod = getSettingValue($groupedSettings, 'negocio', 'grace_period_days');
$defaultPlan = getSettingValue($groupedSettings, 'negocio', 'default_plan_id');
$maintenance = getSettingValue($groupedSettings, 'sistema', 'maintenance_mode');
$registrations = getSettingValue($groupedSettings, 'sistema', 'allow_registrations');
?>

<div class="container-fluid py-4 animate-fade-in">
    <!-- Header Section -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-7">
            <h1 class="fw-bold text-dynamic mb-1">
                <i class="bi bi-gear-wide-connected me-2 text-primary"></i>Configuración Global
            </h1>
            <p class="text-muted-dynamic mb-0">Controla el motor del sistema y las reglas de negocio principales.</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <div class="bg-card glass-premium-card d-inline-flex p-2 rounded-4 shadow-sm border border-light-subtle">
                <div class="me-3 ps-2 text-start">
                    <small class="text-muted d-block">Base de Datos</small>
                    <span class="fw-bold small text-dynamic">Último backup: Reciente</span>
                </div>
                <a href="<?= BASE_URL ?>admin/settings/backup" class="btn btn-primary rounded-3 px-3">
                    <i class="bi bi-cloud-download me-2"></i>Descargar .SQL
                </a>
            </div>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success shadow-sm rounded-4 animate-fade-in mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                <div>
                    <strong class="d-block">¡Éxito!</strong>
                    <span>Los cambios en la configuración se han guardado correctamente.</span>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3">
            <div class="card glass-premium-card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body p-2">
                    <div class="nav flex-column nav-pills custom-pills-v" id="settingsTabs" role="tablist">
                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-general" type="button">
                            <i class="bi bi-building me-2"></i>Identidad & Localización
                        </button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-business" type="button">
                            <i class="bi bi-briefcase me-2"></i>Reglas de Negocio
                        </button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-system" type="button">
                            <i class="bi bi-shield-lock me-2"></i>Sistema & Seguridad
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Form Areas -->
        <div class="col-lg-9">
            <form action="<?= BASE_URL ?>admin/settings/update" method="POST" id="settingsForm">
                <?php echo csrf_field(); ?>
                <div class="tab-content" id="settingsTabsContent">
                    
                    <!-- TAB: GENERAL -->
                    <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
                        <div class="card glass-premium-card border-0 shadow-sm overflow-hidden">
                            <div class="card-header bg-transparent border-0 p-4 pb-0">
                                <h4 class="fw-bold text-dynamic mb-3">Identidad del Sistema</h4>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Nombre del Sitio</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-soft-dynamic border-0"><i class="bi bi-info-circle text-primary"></i></span>
                                            <input type="text" class="form-control" name="site_name" value="<?= htmlspecialchars($siteName) ?>" placeholder="Ej: AgenciaTurismo Pro">
                                        </div>
                                        <small class="text-muted mt-2 d-block">Aparece en el pie de página, correos electrónicos y títulos de navegador.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Email de Contacto Oficial</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-soft-dynamic border-0"><i class="bi bi-envelope text-primary"></i></span>
                                            <input type="email" class="form-control" name="support_email" value="<?= htmlspecialchars($supportEmail) ?>" placeholder="soporte@empresa.com">
                                        </div>
                                        <small class="text-muted mt-2 d-block">Dirección donde las agencias enviarán sus dudas técnicas.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dynamic">Moneda del Sistema</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-primary bg-opacity-10 border-0">
                                                <i class="bi bi-cash-coin text-primary fs-5"></i>
                                            </span>
                                            <select class="form-select" name="currency">
                                                <option value="PEN" <?= $currency === 'PEN' ? 'selected' : '' ?>>🇵🇪 Soles (PEN)</option>
                                                <option value="USD" <?= $currency === 'USD' ? 'selected' : '' ?>>🇺🇸 Dólares (USD)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: BUSINESS -->
                    <div class="tab-pane fade" id="tab-business" role="tabpanel">
                        <div class="card glass-premium-card border-0 shadow-sm overflow-hidden">
                            <div class="card-header bg-transparent border-0 p-4 pb-0">
                                <h4 class="fw-bold text-dynamic mb-3">Suscripciones y Reglas</h4>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="p-4 rounded-4 border border-light-subtle bg-soft-dynamic h-100">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="icon-circle bg-warning bg-opacity-10 text-warning me-3">
                                                    <i class="bi bi-clock-history"></i>
                                                </div>
                                                <h5 class="mb-0 fw-bold">Período de Gracia</h5>
                                            </div>
                                            <p class="text-muted small mb-3">Días adicionales que se le dan a una agencia antes de suspender su cuenta por falta de pago.</p>
                                            <div class="input-group">
                                                <input type="number" class="form-control bg-white" name="grace_period_days" value="<?= htmlspecialchars($gracePeriod) ?>">
                                                <span class="input-group-text bg-white">Días</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-4 rounded-4 border border-light-subtle bg-soft-dynamic h-100">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="icon-circle bg-info bg-opacity-10 text-info me-3">
                                                    <i class="bi bi-person-badge"></i>
                                                </div>
                                                <h5 class="mb-0 fw-bold">Plan por Defecto</h5>
                                            </div>
                                            <p class="text-muted small mb-3">ID del plan que se asignará automáticamente a las nuevas agencias registradas.</p>
                                            <input type="number" class="form-control bg-white" name="default_plan_id" value="<?= htmlspecialchars($defaultPlan) ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: SYSTEM -->
                    <div class="tab-pane fade" id="tab-system" role="tabpanel">
                        <div class="card glass-premium-card border-0 shadow-sm overflow-hidden">
                            <div class="card-header bg-transparent border-0 p-4 pb-0">
                                <h4 class="fw-bold text-dynamic mb-3">Control de Acceso</h4>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="p-4 rounded-4 border <?= $maintenance === '1' ? 'border-danger bg-danger' : 'border-light-subtle bg-soft-dynamic' ?> bg-opacity-10 transition-all">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-circle <?= $maintenance === '1' ? 'bg-danger text-white' : 'bg-secondary text-secondary bg-opacity-10' ?> me-3">
                                                        <i class="bi bi-cone-striped"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-0 fw-bold <?= $maintenance === '1' ? 'text-danger' : 'text-dynamic' ?>">Modo Mantenimiento</h5>
                                                        <p class="text-muted small mb-0">Solo los Super Administradores podrán entrar si está activo.</p>
                                                    </div>
                                                </div>
                                                <div class="form-check form-switch form-switch-xl">
                                                    <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" <?= $maintenance === '1' ? 'checked' : '' ?>>
                                                </div>
                                                <input type="hidden" name="maintenance_mode" value="0" id="maintenance_mode_hidden" <?= $maintenance === '1' ? 'disabled' : '' ?>>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="p-4 rounded-4 border border-light-subtle bg-soft-dynamic">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-circle bg-success bg-opacity-10 text-success me-3">
                                                        <i class="bi bi-person-plus"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-0 fw-bold">Nuevos Registros</h5>
                                                        <p class="text-muted small mb-0">Permite que agencias externas creen una cuenta desde la landing page.</p>
                                                    </div>
                                                </div>
                                                <div class="form-check form-switch form-switch-xl">
                                                    <input class="form-check-input" type="checkbox" id="allow_registrations" name="allow_registrations" value="1" <?= $registrations === '1' ? 'checked' : '' ?>>
                                                </div>
                                                <input type="hidden" name="allow_registrations" value="0" id="allow_registrations_hidden" <?= $registrations === '1' ? 'disabled' : '' ?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- FAB Button for Saving -->
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-lg px-5 py-3 border-0 transition-up">
                        <i class="bi bi-save2 me-2"></i>Aplicar Todos los Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .custom-pills-v .nav-link {
        padding: 1rem 1.25rem;
        color: var(--text-secondary);
        text-align: left;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
        margin-bottom: 5px;
        background: transparent;
    }

    .custom-pills-v .nav-link:hover {
        background: var(--bg-soft-dynamic);
        color: var(--brand-primary);
    }

    .custom-pills-v .nav-link.active {
        background: var(--brand-primary) !important;
        color: white !important;
        box-shadow: 0 4px 15px rgba(233, 69, 96, 0.3);
    }

    .form-switch-xl .form-check-input {
        width: 3.5rem;
        height: 1.75rem;
        cursor: pointer;
    }

    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .bg-soft-dynamic {
        background-color: var(--bg-tertiary);
        color: var(--text-primary);
    }

    .transition-up:hover {
        transform: translateY(-5px);
    }

    .form-control, .form-select {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        background-color: var(--bg-secondary);
        border-color: var(--border-color);
        color: var(--text-primary);
    }

    .form-control:focus, .form-select:focus {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
        box-shadow: 0 0 0 0.25rem rgba(var(--primary-rgb), 0.1);
    }

    .text-dynamic {
        color: var(--text-primary) !important;
    }

    .text-muted-dynamic {
        color: var(--text-secondary) !important;
    }
</style>

<script>
    // Robust handler for checkboxes with hidden fallback
    document.addEventListener('DOMContentLoaded', function() {
        const toggleHidden = (checkbox) => {
            const hiddenInput = document.getElementById(checkbox.id + '_hidden');
            if (hiddenInput) {
                hiddenInput.disabled = checkbox.checked;
            }
        };

        const switches = document.querySelectorAll('.form-switch .form-check-input');
        switches.forEach(sw => {
            sw.addEventListener('change', () => toggleHidden(sw));
            // Run initial state
            toggleHidden(sw);
        });

        // Maintenance visual feedback
        const maintSwitch = document.getElementById('maintenance_mode');
        maintSwitch.addEventListener('change', function() {
            const container = this.closest('.p-4');
            if (this.checked) {
                container.classList.add('border-danger', 'bg-danger');
                container.classList.remove('border-light-subtle', 'bg-soft-dynamic');
                this.previousElementSibling.firstElementChild.classList.add('text-danger');
            } else {
                container.classList.remove('border-danger', 'bg-danger');
                container.classList.add('border-light-subtle', 'bg-soft-dynamic');
                this.previousElementSibling.firstElementChild.classList.remove('text-danger');
            }
        });
    });
</script>




<?php include BASE_PATH . '/views/layouts/footer.php'; ?>

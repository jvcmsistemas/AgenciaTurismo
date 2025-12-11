<?php
// Sistema_New/views/admin/plans/create.php
include BASE_PATH . '/views/layouts/header.php';
?>

<div class="row mb-4 animate-fade-in">
    <div class="col-md-6">
        <h2 class="mb-0">✨ Crear Nuevo Plan</h2>
        <p class="text-muted">Define un nuevo paquete de suscripción</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= BASE_URL ?>admin/plans" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger animate-fade-in"><?= $error ?></div>
<?php endif; ?>

<form action="<?= BASE_URL ?>admin/plans/store" method="POST" class="animate-fade-in-up">
    <div class="row">
        <!-- Información Básica -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Información General</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre del Plan</label>
                            <input type="text" name="nombre" class="form-control" required placeholder="Ej. Plan Gold">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Código (Único)</label>
                            <input type="text" name="codigo" class="form-control" required placeholder="ej. gold_2025"
                                pattern="[a-z0-9_]+" title="Solo minúsculas, números y guión bajo">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción Publica</label>
                            <textarea name="descripcion" class="form-control" rows="3"
                                placeholder="Descripción corta para la landing page..."></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Precio (USD/PEN)</label>
                            <input type="number" name="precio" class="form-control" step="0.01" required min="0"
                                value="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Duración (Meses)</label>
                            <input type="number" name="duracionmeses" class="form-control" required min="1" value="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Orden de Visualización</label>
                            <input type="number" name="orden" class="form-control" value="0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Límites -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Límites de Recursos</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info py-2">
                        <i class="fas fa-info-circle me-1"></i> Dejar vacío para <strong>ILIMITADO</strong>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Límite de Clientes</label>
                            <input type="number" name="limiteclientes" class="form-control" min="1"
                                placeholder="Ilimitado">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Límite de Tours</label>
                            <input type="number" name="limitetours" class="form-control" min="1"
                                placeholder="Ilimitado">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Límite de Usuarios (Staff)</label>
                            <input type="number" name="limiteusuarios" class="form-control" min="1"
                                placeholder="Ilimitado">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Límite de Empleados</label>
                            <input type="number" name="limiteempleados" class="form-control" min="1"
                                placeholder="Ilimitado">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Características y Configuración -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Características Incluidas</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="incluye_auditorias" checked>
                        <label class="form-check-label">Auditorías</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="incluye_reportes" checked>
                        <label class="form-check-label">Reportes Avanzados</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="incluye_api">
                        <label class="form-check-label">Acceso a API</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="incluye_integraciones">
                        <label class="form-check-label">Integraciones (WhatsApp)</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="incluye_soporte_premium">
                        <label class="form-check-label">Soporte Premium 24/7</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="incluye_backup_automatico">
                        <label class="form-check-label">Backups Automáticos</label>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Configuración</h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="activo" checked>
                        <label class="form-check-label">Plan Activo</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="destacado">
                        <label class="form-check-label fw-bold text-warning">Destacar Plan</label>
                        <div class="form-text">Aparecerá resaltado en la UI</div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Guardar Plan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
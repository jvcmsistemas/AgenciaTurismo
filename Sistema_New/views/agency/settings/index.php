<?php
// Sistema_New/views/agency/settings/index.php
include BASE_PATH . '/views/layouts/header_agency.php';
?>

<div class="row mb-4 fade-in">
    <div class="col-12 text-center">
        <h2 class="fw-bold text-primary"><i class="bi bi-gear-wide-connected me-2"></i>Configuración de la Agencia</h2>
        <p class="text-muted">Personaliza la información pública y comercial de tu empresa.</p>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show glass-card border-0 text-success fw-bold mx-auto mb-4"
        style="max-width: 800px;" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> ¡Configuración guardada correctamente!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="container" style="max-width: 900px;">
    <form action="<?php echo BASE_URL; ?>agency/settings/update" method="POST">
        <?php echo csrf_field(); ?>

        <!-- SECCIÓN: DATOS IDENTITARIOS -->
        <div class="card glass-card border-0 mb-4 shadow-sm">
            <div class="card-header glass-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold text-primary mb-0"><i class="bi bi-card-heading me-2"></i>Identidad Corporativa</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Nombre Comercial</label>
                        <input type="text" name="nombre" class="form-control bg-light border-0"
                            value="<?php echo htmlspecialchars($agency['nombre']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Número de RUC / Registro</label>
                        <input type="text" name="ruc" class="form-control bg-light border-0"
                            value="<?php echo htmlspecialchars($agency['ruc']); ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold text-muted">Descripción de la Agencia</label>
                        <textarea name="descripcion" class="form-control bg-light border-0" rows="3"
                            placeholder="Resumen breve que aparecerá en itinerarios y documentos..."><?php echo htmlspecialchars($agency['descripcion']); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN: CONTACTO Y WEB -->
        <div class="card glass-card border-0 mb-4 shadow-sm">
            <div class="card-header glass-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold text-secondary mb-0"><i class="bi bi-globe me-2"></i>Canales de Contacto</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Email Corporativo</label>
                        <input type="email" name="email" class="form-control bg-light border-0"
                            value="<?php echo htmlspecialchars($agency['email']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Teléfono Central</label>
                        <input type="text" name="telefono" class="form-control bg-light border-0"
                            value="<?php echo htmlspecialchars($agency['telefono']); ?>">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label small fw-bold text-muted">Sitio Web (URL)</label>
                        <input type="url" name="web" class="form-control bg-light border-0"
                            value="<?php echo htmlspecialchars($agency['web']); ?>"
                            placeholder="https://www.tuagencia.com">
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN: UBICACIÓN -->
        <div class="card glass-card border-0 mb-4 shadow-sm">
            <div class="card-header glass-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold text-info mb-0"><i class="bi bi-geo-alt me-2"></i>Ubicación Física</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">País</label>
                        <input type="text" name="pais" class="form-control bg-light border-0"
                            value="<?php echo htmlspecialchars($agency['pais']); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Ciudad</label>
                        <input type="text" name="ciudad" class="form-control bg-light border-0"
                            value="<?php echo htmlspecialchars($agency['ciudad']); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Dirección</label>
                        <input type="text" name="direccion" class="form-control bg-light border-0"
                            value="<?php echo htmlspecialchars($agency['direccion']); ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN: BRANDING -->
        <div class="card glass-card border-0 mb-4 shadow-sm border-start border-warning border-4">
            <div class="card-header glass-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold text-warning mb-0"><i class="bi bi-image me-2"></i>Imagen y Logo</h5>
            </div>
            <div class="card-body p-4 text-center">
                <?php if ($agency['logo_url']): ?>
                    <div class="mb-3">
                        <img src="<?php echo htmlspecialchars($agency['logo_url']); ?>" alt="Logo actual"
                            class="rounded shadow-sm" style="max-height: 100px;">
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">URL del Logo</label>
                    <input type="text" name="logo_url" class="form-control bg-light border-0 text-center"
                        value="<?php echo htmlspecialchars($agency['logo_url']); ?>"
                        placeholder="URL de la imagen del logo...">
                    <div class="form-text mt-2 small">Próximamente: Carga de archivos directa desde el servidor.</div>
                </div>
            </div>
        </div>

        <div class="text-center mb-5 mt-4">
            <button type="submit" class="btn btn-primary btn-lg px-5 shadow rounded-pill">
                <i class="bi bi-check-lg me-2"></i>Actualizar Configuración
            </button>
        </div>
    </form>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
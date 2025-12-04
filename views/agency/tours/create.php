<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary fw-bold mb-0">Nuevo Tour</h2>
                <a href="<?php echo BASE_URL; ?>agency/tours" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
            </div>

            <div class="glass-card p-4">
                <form action="<?php echo BASE_URL; ?>agency/tours/store" method="POST">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="nombre" class="form-label fw-bold text-muted small">NOMBRE DEL TOUR</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required
                                placeholder="Ej. Aventura en Pozuzo">
                        </div>

                        <div class="col-md-12">
                            <label for="descripcion" class="form-label fw-bold text-muted small">DESCRIPCIÓN</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4"
                                placeholder="Describe la experiencia..."></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="ubicacion" class="form-label fw-bold text-muted small">UBICACIÓN
                                PRINCIPAL</label>
                            <input type="text" class="form-control" id="ubicacion" name="ubicacion"
                                placeholder="Ej. Oxapampa, Pozuzo">
                        </div>

                        <div class="col-md-6">
                            <label for="tags" class="form-label fw-bold text-muted small">ETIQUETAS (TAGS)</label>
                            <input type="text" class="form-control" id="tags" name="tags"
                                placeholder="Ej. Aventura, Familia, Relax">
                        </div>

                        <div class="col-md-4">
                            <label for="duracion" class="form-label fw-bold text-muted small">DURACIÓN (DÍAS)</label>
                            <input type="number" class="form-control" id="duracion" name="duracion" min="1" value="1"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label for="precio" class="form-label fw-bold text-muted small">PRECIO BASE (S/)</label>
                            <input type="number" class="form-control" id="precio" name="precio" min="0" step="0.01"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label for="nivel_dificultad" class="form-label fw-bold text-muted small">DIFICULTAD</label>
                            <select class="form-select" id="nivel_dificultad" name="nivel_dificultad">
                                <option value="bajo">Bajo</option>
                                <option value="medio" selected>Medio</option>
                                <option value="alto">Alto</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                                <i class="bi bi-save me-2"></i>GUARDAR TOUR
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
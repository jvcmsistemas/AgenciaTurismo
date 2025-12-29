<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="text-success fw-bold mb-1">Editar Tour</h2>
                    <p class="text-muted mb-0">Modifica los detalles de la experiencia.</p>
                </div>
                <a href="<?php echo BASE_URL; ?>agency/tours" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
            </div>

            <div class="card glass-card border-0 shadow-lg">
                <div class="card-body p-4">
                    <form action="<?php echo BASE_URL; ?>agency/tours/update" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo $tour['id']; ?>">

                        <div class="row g-3">
                            <!-- Nombre -->
                            <div class="col-md-12">
                                <label for="nombre" class="form-label fw-semibold text-dark">Nombre del Tour <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-signpost-2"></i></span>
                                    <input type="text" class="form-control border-start-0" id="nombre" name="nombre"
                                        required value="<?php echo htmlspecialchars($tour['nombre']); ?>"
                                        placeholder="Ej. Aventura en Pozuzo">
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="col-md-12">
                                <label for="descripcion" class="form-label fw-semibold text-dark">Descripción
                                    Detallada</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-card-text"></i></span>
                                    <textarea class="form-control border-start-0" id="descripcion" name="descripcion"
                                        rows="3"
                                        placeholder="Describe el itinerario..."><?php echo htmlspecialchars($tour['descripcion']); ?></textarea>
                                </div>
                            </div>

                            <!-- Ubicación -->
                            <div class="col-md-6">
                                <label for="ubicacion" class="form-label fw-semibold text-dark">Ubicación
                                    Principal</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-geo-alt"></i></span>
                                    <input type="text" class="form-control border-start-0" id="ubicacion"
                                        name="ubicacion" value="<?php echo htmlspecialchars($tour['ubicacion']); ?>"
                                        placeholder="Ej. Oxapampa">
                                </div>
                            </div>

                            <!-- Tags -->
                            <div class="col-md-6">
                                <label for="tags" class="form-label fw-semibold text-dark">Etiquetas (Tags)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-tags"></i></span>
                                    <input type="text" class="form-control border-start-0" id="tags" name="tags"
                                        value="<?php echo htmlspecialchars($tour['tags']); ?>"
                                        placeholder="Ej. Aventura, Familia">
                                </div>
                            </div>

                            <div class="col-12">
                                <hr class="text-muted opacity-25">
                            </div>

                            <!-- Duración -->
                            <?php
                            // Lógica para determinar unidad y valor
                            $duracion_valor = $tour['duracion'];
                            $duracion_unidad = 'dias';
                            if ($tour['duracion'] < 1) {
                                $duracion_valor = $tour['duracion'] * 24; // Convertir a horas
                                $duracion_unidad = 'horas';
                            }
                            ?>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark">Duración <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-clock"></i></span>
                                    <input type="number" class="form-control border-start-0" id="duracion_valor"
                                        name="duracion_valor" min="0.5" step="0.5" required
                                        value="<?php echo $duracion_valor; ?>">
                                    <select class="form-select bg-light border-start-0" name="duracion_unidad"
                                        style="max-width: 100px;">
                                        <option value="dias" <?php echo $duracion_unidad === 'dias' ? 'selected' : ''; ?>>
                                            Días</option>
                                        <option value="horas" <?php echo $duracion_unidad === 'horas' ? 'selected' : ''; ?>>Horas</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Precio -->
                            <div class="col-md-4">
                                <label for="precio" class="form-label fw-semibold text-dark">Precio Base (S/) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-currency-dollar"></i></span>
                                    <input type="number" class="form-control border-start-0" id="precio" name="precio"
                                        min="0" step="0.01" required value="<?php echo $tour['precio']; ?>"
                                        placeholder="0.00">
                                </div>
                            </div>

                            <!-- Dificultad -->
                            <div class="col-md-4">
                                <label for="nivel_dificultad" class="form-label fw-semibold text-dark">Nivel de
                                    Dificultad</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-activity"></i></span>
                                    <select class="form-select border-start-0" id="nivel_dificultad"
                                        name="nivel_dificultad">
                                        <option value="bajo" <?php echo $tour['nivel_dificultad'] === 'bajo' ? 'selected' : ''; ?>>Bajo (Familiar)</option>
                                        <option value="medio" <?php echo $tour['nivel_dificultad'] === 'medio' ? 'selected' : ''; ?>>Medio (Estándar)</option>
                                        <option value="alto" <?php echo $tour['nivel_dificultad'] === 'alto' ? 'selected' : ''; ?>>Alto (Exigente)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-success w-100 py-2 rounded-pill fw-bold shadow-sm">
                                    <i class="bi bi-save me-2"></i>Actualizar Tour
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
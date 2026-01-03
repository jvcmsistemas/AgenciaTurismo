<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="row mb-4 fade-in">
    <div class="col-12">
        <h2 class="fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i>Editar Salida</h2>
        <p class="text-muted">Modifica los detalles de esta programación.</p>
    </div>
</div>

<form action="<?php echo BASE_URL; ?>agency/departures/update" method="POST">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id" value="<?php echo $departure['id']; ?>">

    <div class="row g-4">
        <!-- Columna Principal -->
        <div class="col-md-8">
            <div class="card glass-card border-0 h-100">
                <div class="card-header glass-header bg-transparent border-0 pt-4 pb-2">
                    <h5 class="fw-bold text-primary"><i class="bi bi-calendar-event me-2"></i>Detalles del Evento</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="tour_id" class="form-label fw-bold">Tour a Realizar</label>
                            <select class="form-select form-select-lg" id="tour_id" name="tour_id" required>
                                <option value="">-- Seleccionar Tour --</option>
                                <?php foreach ($tours as $tour): ?>
                                    <option value="<?php echo $tour['id']; ?>" 
                                        <?php echo ($departure['tour_id'] == $tour['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($tour['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <?php 
                            // Fecha viene de columna DATE
                            $dateVal = $departure['fecha_salida'];
                            // Hora viene de columna TIME
                            // Puede venir con segundos H:i:s, recortamos a H:i
                            $timeVal = substr($departure['hora_salida'], 0, 5);
                        ?>

                        <div class="col-md-6">
                            <label for="fecha_salida" class="form-label fw-bold">Fecha</label>
                            <input type="date" class="form-control" id="fecha_salida" name="fecha_salida" 
                                   value="<?php echo $dateVal; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="hora_salida" class="form-label fw-bold">Hora</label>
                            <input type="time" class="form-control" id="hora_salida" name="hora_salida" 
                                   value="<?php echo $timeVal; ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="cupos_totales" class="form-label fw-bold">Capacidad Total</label>
                            <input type="number" class="form-control" id="cupos_totales" name="cupos_totales" 
                                   value="<?php echo $departure['cupos_totales']; ?>" required>
                            <div class="form-text">Cupos Disponibles Actuales: <?php echo $departure['cupos_disponibles']; ?></div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="precio_actual" class="form-label fw-bold">Precio Especial (Opcional)</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="number" step="0.01" class="form-control" id="precio_actual" name="precio_actual" 
                                       value="<?php echo $departure['precio_actual']; ?>" placeholder="Original del Tour">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="estado" class="form-label fw-bold">Estado</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="programada" <?php echo ($departure['estado'] == 'programada') ? 'selected' : ''; ?>>Programada (Abierta)</option>
                                <option value="confirmada" <?php echo ($departure['estado'] == 'confirmada') ? 'selected' : ''; ?>>Confirmada (Confirmada)</option>
                                <option value="cerrada" <?php echo ($departure['estado'] == 'cerrada') ? 'selected' : ''; ?>>Cerrada (Sin cupos)</option>
                                <option value="cancelada" <?php echo ($departure['estado'] == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Recursos -->
        <div class="col-md-4">
            <div class="card glass-card border-0 h-100">
                <div class="card-header glass-header bg-transparent border-0 pt-4 pb-2">
                    <h5 class="fw-bold text-secondary"><i class="bi bi-people-fill me-2"></i>Recursos</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="guia_id" class="form-label fw-bold">Guía Asignado</label>
                        <select class="form-select" id="guia_id" name="guia_id">
                            <option value="">-- Sin asignar --</option>
                            <?php foreach ($guides as $guide): ?>
                                <option value="<?php echo $guide['id']; ?>" 
                                    <?php echo ($departure['guia_id'] == $guide['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($guide['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="transporte_id" class="form-label fw-bold">Transporte</label>
                        <select class="form-select" id="transporte_id" name="transporte_id" onchange="updateCapacity()">
                            <option value="" data-capacidad="0">-- Sin transporte (Movilidad externa/propia) --</option>
                            <?php foreach ($transports as $transport): ?>
                                <option value="<?php echo $transport['id']; ?>" 
                                    data-capacidad="<?php echo $transport['capacidad']; ?>"
                                    <?php echo ($departure['transporte_id'] == $transport['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($transport['placa']); ?> - 
                                    <?php echo htmlspecialchars($transport['chofer_nombre']); ?> 
                                    (<?php echo $transport['capacidad']; ?> asientos)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 mb-5">
        <div class="col-12 text-end">
            <a href="<?php echo BASE_URL; ?>agency/departures" class="btn btn-outline-secondary btn-lg me-2">Cancelar</a>
            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                <i class="bi bi-save me-2"></i>Guardar Cambios
            </button>
        </div>
    </div>
</form>

<script>
    function updateCapacity() {
        const select = document.getElementById('transporte_id');
        const capacityInput = document.getElementById('cupos_totales');
        const selectedOption = select.options[select.selectedIndex];
        const capacity = selectedOption.getAttribute('data-capacidad');

        if (capacity && capacity > 0) {
            capacityInput.value = capacity;
        }
    }
</script>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>

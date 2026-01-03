<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="text-success fw-bold mb-1">Programar Salida</h2>
                    <p class="text-muted mb-0">Define fecha y recursos para un tour.</p>
                </div>
                <a href="<?php echo BASE_URL; ?>agency/departures" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
            </div>

            <div class="card glass-card border-0 shadow-lg">
                <div class="card-body p-4">
                    <form action="<?php echo BASE_URL; ?>agency/departures/store" method="POST">
                        <?php echo csrf_field(); ?>

                        <!-- Paso 1: Selección de Tour -->
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-map me-2 text-success"></i>1. ¿Qué
                            experiencia ofrecerás?</h5>
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small">SELECCIONA UN TOUR</label>
                            <div class="input-group">
                                <span
                                    class="input-group-text bg-light-dynamic border-end-0 border-dynamic text-primary"><i
                                        class="bi bi-signpost-2"></i></span>
                                <select class="form-select border-start-0 border-dynamic bg-dynamic" name="tour_id"
                                    required>
                                    <option value="" selected disabled>Elige un tour del catálogo...</option>
                                    <?php foreach ($tours as $tour): ?>
                                        <option value="<?php echo $tour['id']; ?>">
                                            <?php echo htmlspecialchars($tour['nombre']); ?>
                                            (<?php echo $tour['duracion'] < 1 ? ($tour['duracion'] * 24) . 'h' : $tour['duracion'] . 'd'; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <hr class="text-muted opacity-25 my-4">

                        <!-- Paso 2: Fecha y Hora -->
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-calendar-event me-2 text-success"></i>2.
                            ¿Cuándo salimos?</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">FECHA DE SALIDA</label>
                                <div class="input-group">
                                    <span
                                        class="input-group-text bg-light-dynamic border-end-0 border-dynamic text-primary"><i
                                            class="bi bi-calendar3"></i></span>
                                    <input type="date" class="form-control border-start-0 border-dynamic bg-dynamic"
                                        name="fecha_salida" required min="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">HORA DE ENCUENTRO</label>
                                <div class="input-group">
                                    <span
                                        class="input-group-text bg-light-dynamic border-end-0 border-dynamic text-primary"><i
                                            class="bi bi-clock"></i></span>
                                    <input type="time" class="form-control border-start-0 border-dynamic bg-dynamic"
                                        name="hora_salida" required>
                                </div>
                            </div>
                        </div>

                        <hr class="text-muted opacity-25 my-4">

                        <!-- Paso 3: Recursos (Opcional) -->
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-people me-2 text-success"></i>3. Asignación
                            de Recursos</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">GUÍA RESPONSABLE</label>
                                <div class="input-group">
                                    <span
                                        class="input-group-text bg-light-dynamic border-end-0 border-dynamic text-primary"><i
                                            class="bi bi-person-badge"></i></span>
                                    <select class="form-select border-start-0 border-dynamic bg-dynamic" name="guia_id">
                                        <option value="">-- Sin asignar por ahora --</option>
                                        <?php foreach ($guides as $guide): ?>
                                            <option value="<?php echo $guide['id']; ?>">
                                                <?php echo htmlspecialchars($guide['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">TRANSPORTE</label>
                                <div class="input-group">
                                    <span
                                        class="input-group-text bg-light-dynamic border-end-0 border-dynamic text-primary"><i
                                            class="bi bi-bus-front"></i></span>
                                    <select class="form-select border-start-0 border-dynamic bg-dynamic"
                                        name="transporte_id" id="selectTransporte" onchange="updateCapacity()">
                                        <option value="" data-capacidad="0">-- Sin transporte (Movilidad externa/propia)
                                            --</option>
                                        <?php foreach ($transports as $transport): ?>
                                            <option value="<?php echo $transport['id']; ?>"
                                                data-capacidad="<?php echo $transport['capacidad']; ?>">
                                                <?php echo htmlspecialchars($transport['placa']); ?> -
                                                <?php echo htmlspecialchars($transport['chofer_nombre']); ?>
                                                (<?php echo $transport['capacidad']; ?> asientos)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Paso 4: Capacidad y Precio -->
                        <div class="row g-3 bg-light-dynamic p-3 rounded-3 border border-dynamic">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Cupos Totales</label>
                                <div class="input-group">
                                    <span
                                        class="input-group-text bg-dynamic border-dynamic border-end-0 text-primary"><i
                                            class="bi bi-people-fill"></i></span>
                                    <input type="number" class="form-control border-start-0 border-dynamic bg-dynamic"
                                        name="cupos_totales" id="cupos_totales" required min="1" value="10">
                                </div>
                                <div class="form-text small">Se actualiza al elegir transporte.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Precio Oferta (Opcional)</label>
                                <div class="input-group">
                                    <span
                                        class="input-group-text bg-dynamic border-dynamic border-end-0 text-primary"><i
                                            class="bi bi-currency-dollar"></i></span>
                                    <input type="number" class="form-control border-start-0 border-dynamic bg-dynamic"
                                        name="precio_actual" min="0" step="0.01"
                                        placeholder="Dejar vacío para usar precio base">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow">
                                <i class="bi bi-check-circle-fill me-2"></i>Programar Salida
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateCapacity() {
        const select = document.getElementById('selectTransporte');
        const capacityInput = document.getElementById('cupos_totales');
        const selectedOption = select.options[select.selectedIndex];
        const capacity = selectedOption.getAttribute('data-capacidad');

        if (capacity && capacity > 0) {
            capacityInput.value = capacity;
        }
    }
</script>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
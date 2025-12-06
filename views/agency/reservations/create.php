<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="row mb-4 fade-in">
    <div class="col-12">
        <h2 class="fw-bold text-primary"><i class="bi bi-calendar-plus me-2"></i>Nueva Reserva</h2>
        <p class="text-muted">Registra una reserva vinculada a una salida programada.</p>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form action="<?php echo BASE_URL; ?>agency/reservations/store" method="POST" id="reservationForm">
    <div class="row g-4">
        <!-- Paso 1: Selección de Salida -->
        <div class="col-md-5">
            <div class="card glass-card border-0 h-100">
                <div class="card-header glass-header bg-transparent border-0 pt-4 pb-2">
                    <h5 class="fw-bold text-primary"><i class="bi bi-1-circle-fill me-2"></i>Selecciona la Salida</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label for="tour_id" class="form-label fw-bold">Tour / Paquete</label>
                        <select class="form-select form-select-lg" id="tour_id" name="tour_id" required>
                            <option value="">-- Selecciona un Tour --</option>
                            <?php foreach ($tours as $tour): ?>
                                <option value="<?php echo $tour['id']; ?>" data-precio="<?php echo $tour['precio']; ?>">
                                    <?php echo htmlspecialchars($tour['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="departures-container" class="d-none">
                        <label class="form-label fw-bold mb-3">Salidas Disponibles</label>
                        <div id="departures-list" class="d-flex flex-column gap-2">
                            <!-- Se llena con AJAX -->
                        </div>
                        <div id="no-departures" class="alert alert-warning d-none">
                            <i class="bi bi-info-circle me-2"></i> No hay salidas programadas para este tour.
                            <a href="<?php echo BASE_URL; ?>agency/departures/create" class="alert-link">Programar una
                                ahora</a>.
                        </div>
                    </div>

                    <!-- Inputs Hidden seleccionados -->
                    <input type="hidden" id="salida_id" name="salida_id" required>
                    <input type="hidden" id="fecha_salida" name="fecha_salida">
                    <input type="hidden" id="precio_unitario" name="precio_unitario">
                </div>
            </div>
        </div>

        <!-- Paso 2: Datos del Cliente y Pago -->
        <div class="col-md-7">
            <div class="card glass-card border-0 h-100 opacity-50" id="client-card" style="pointer-events: none;">
                <div class="card-header glass-header bg-transparent border-0 pt-4 pb-2">
                    <h5 class="fw-bold text-secondary"><i class="bi bi-2-circle-fill me-2"></i>Datos de Reserva</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="cliente_nombre" class="form-label fw-bold">Nombre Cliente</label>
                            <input type="text" class="form-control" id="cliente_nombre" name="cliente_nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="cliente_apellido" class="form-label fw-bold">Apellido Cliente</label>
                            <input type="text" class="form-control" id="cliente_apellido" name="cliente_apellido"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="cliente_email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="cliente_email" name="cliente_email">
                        </div>
                        <div class="col-md-6">
                            <label for="cliente_telefono" class="form-label fw-bold">Teléfono</label>
                            <input type="text" class="form-control" id="cliente_telefono" name="cliente_telefono">
                        </div>
                    </div>

                    <hr class="text-muted">

                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="cantidad" class="form-label fw-bold">Pasajeros</label>
                            <input type="number" class="form-control form-control-lg text-center fw-bold" id="cantidad"
                                name="cantidad" value="1" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted">Precio Unit.</label>
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light">S/</span>
                                <input type="text" class="form-control border-0 bg-light fw-bold" id="display_precio"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-primary">Total a Pagar</label>
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-primary text-white">S/</span>
                                <input type="text" class="form-control border-0 bg-primary text-white fw-bold"
                                    id="precio_total" name="precio_total" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="notas" class="form-label fw-bold">Notas Adicionales</label>
                        <textarea class="form-control" id="notas" name="notas" rows="2"
                            placeholder="Alergias, requerimientos especiales, etc."></textarea>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm">
                            <i class="bi bi-check-lg me-2"></i>Confirmar Reserva
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tourSelect = document.getElementById('tour_id');
        const departuresContainer = document.getElementById('departures-container');
        const departuresList = document.getElementById('departures-list');
        const noDepartures = document.getElementById('no-departures');
        const clientCard = document.getElementById('client-card');

        // Inputs
        const salidaIdInput = document.getElementById('salida_id');
        const fechaSalidaInput = document.getElementById('fecha_salida');
        const precioUnitInput = document.getElementById('precio_unitario');
        const displayPrecio = document.getElementById('display_precio');
        const cantidadInput = document.getElementById('cantidad');
        const precioTotalInput = document.getElementById('precio_total');

        // Al cambiar Tour
        tourSelect.addEventListener('change', function () {
            const tourId = this.value;
            const defaultPrice = this.options[this.selectedIndex].dataset.precio;

            // Reset
            departuresList.innerHTML = '';
            departuresContainer.classList.add('d-none');
            clientCard.classList.add('opacity-50');
            clientCard.style.pointerEvents = 'none';
            salidaIdInput.value = '';

            if (!tourId) return;

            // Fetch Salidas
            fetch('<?php echo BASE_URL; ?>agency/reservations/get-departures?tour_id=' + tourId)
                .then(response => response.json())
                .then(data => {
                    departuresContainer.classList.remove('d-none');

                    if (data.length === 0) {
                        noDepartures.classList.remove('d-none');
                    } else {
                        noDepartures.classList.add('d-none');

                        data.forEach(salida => {
                            const date = new Date(salida.fecha_salida);
                            const formattedDate = date.toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long', hour: '2-digit', minute: '2-digit' });
                            const price = salida.precio_actual ? salida.precio_actual : defaultPrice;

                            const btn = document.createElement('div');
                            btn.className = 'card p-3 cursor-pointer hover-shadow border-start border-4 border-primary';
                            btn.style.cursor = 'pointer';
                            btn.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-1 text-capitalize">${formattedDate}</h6>
                                    <small class="text-muted">Cupos: ${salida.cupos_disponibles} / ${salida.cupos_totales}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success rounded-pill mb-1">S/ ${price}</span>
                                    <br>
                                    <small class="text-primary fw-bold">Seleccionar</small>
                                </div>
                            </div>
                        `;

                            btn.addEventListener('click', function () {
                                // Deseleccionar otros
                                Array.from(departuresList.children).forEach(c => {
                                    c.classList.remove('bg-light', 'border-success');
                                    c.classList.add('border-primary');
                                });

                                // Seleccionar este
                                this.classList.remove('border-primary');
                                this.classList.add('bg-light', 'border-success');

                                // Llenar datos
                                salidaIdInput.value = salida.id;
                                fechaSalidaInput.value = salida.fecha_salida;
                                precioUnitInput.value = price;
                                displayPrecio.value = price;

                                // Habilitar form cliente
                                clientCard.classList.remove('opacity-50');
                                clientCard.style.pointerEvents = 'auto';

                                // Calcular total inicial
                                calculateTotal();
                            });

                            departuresList.appendChild(btn);
                        });
                    }
                });
        });

        // Calcular Total
        cantidadInput.addEventListener('input', calculateTotal);

        function calculateTotal() {
            const qty = parseFloat(cantidadInput.value) || 0;
            const price = parseFloat(precioUnitInput.value) || 0;
            const total = qty * price;
            precioTotalInput.value = total.toFixed(2);
        }
    });
</script>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="row mb-4 fade-in">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-primary"><i class="bi bi-cart-plus me-2"></i>Nueva Reserva</h2>
            <p class="text-muted">Registra una venta con múltiples items.</p>
        </div>
        <a href="<?php echo BASE_URL; ?>agency/reservations" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Volver
        </a>
    </div>
</div>

<form action="<?php echo BASE_URL; ?>agency/reservations/store" method="POST" id="reservationForm">

    <!-- SECCIÓN CLIENTE -->
    <div class="card glass-card border-0 shadow-sm mb-4">
        <div class="card-header glass-header bg-transparent border-0 pt-4 pb-2">
            <h5 class="fw-bold text-primary"><i class="bi bi-person-circle me-2"></i>Datos del Cliente</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-bold small text-muted">BUSCAR O CREAR CLIENTE</label>
                    <div class="position-relative">
                        <input type="text" class="form-control form-control-lg" id="searchClient"
                            placeholder="Escribe DNI o Nombre para buscar..." autocomplete="off">
                        <div id="clientSuggestions" class="list-group position-absolute w-100 shadow-lg"
                            style="z-index: 1000; display: none;"></div>
                    </div>
                    <input type="hidden" name="cliente_id" id="cliente_id">
                </div>

                <!-- Campos de Cliente (se llenan auto o manual) -->
                <div class="col-md-6">
                    <input type="text" class="form-control" name="cliente_nombre" id="cliente_nombre"
                        placeholder="Nombres" required>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="cliente_apellido" id="cliente_apellido"
                        placeholder="Apellidos" required>
                </div>
                <div class="col-md-6">
                    <input type="email" class="form-control" name="cliente_email" id="cliente_email" placeholder="Email"
                        required>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="cliente_telefono" id="cliente_telefono"
                        placeholder="Teléfono">
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN ITEMS -->
    <div class="card glass-card border-0 shadow-sm mb-4">
        <div
            class="card-header glass-header bg-transparent border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-primary"><i class="bi bi-list-check me-2"></i>Detalle de Servicios</h5>
            <button type="button" class="btn btn-sm btn-primary rounded-circle shadow-sm" onclick="addItemRow()">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="itemsTable">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 30%;" class="text-secondary small fw-bold"><i class="bi bi-map me-1"></i>EXPERIENCIA (TOUR)</th>
                            <th style="width: 30%;" class="text-secondary small fw-bold"><i class="bi bi-calendar-event me-1"></i>SALIDA DISPONIBLE</th>
                            <th style="width: 10%;" class="text-secondary small fw-bold"><i class="bi bi-people me-1"></i>CANT.</th>
                            <th style="width: 15%;" class="text-secondary small fw-bold"><i class="bi bi-tag me-1"></i>PRECIO (S/)</th>
                            <th style="width: 10%;" class="text-end text-secondary small fw-bold">SUBTOTAL</th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Fila Template (se clonará) -->
                        <tr class="item-row" id="row-0">
                            <td>
                                <select class="form-select tour-select border pe-4" onchange="loadDepartures(this)" style="background-color: #f8f9fa;">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($tours as $tour): ?>
                                        <option value="<?php echo $tour['id']; ?>">
                                            <?php echo htmlspecialchars($tour['nombre']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-select departure-select" name="salidas[]" onchange="setPrice(this)"
                                    disabled>
                                    <option value="">Primero elija tour...</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control qty-input" name="cantidades[]" value="1"
                                    min="1" onchange="calcSubtotal(this)" disabled>
                            </td>
                            <td>
                                <input type="number" class="form-control price-input" name="precios[]" step="0.01"
                                    onchange="calcSubtotal(this)" readonly>
                            </td>
                            <td class="fw-bold text-end subtotal-display">S/ 0.00</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm text-danger" onclick="removeRow(this)"><i
                                        class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td colspan="4" class="text-end text-muted text-uppercase">Total General</td>
                            <td class="text-end fs-5 text-primary" id="grandTotal">S/ 0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- NOTAS Y GUARDAR -->
    <div class="row">
        <div class="col-md-12 text-end">
            <button type="submit" class="btn btn-success btn-lg px-5 shadow rounded-pill">
                <i class="bi bi-check-circle-fill me-2"></i>Confirmar Reserva
            </button>
        </div>
    </div>
</form>

<script>
    // --- LÓGICA DE AUTOCOMPLETADO DE CLIENTE ---
    const searchInput = document.getElementById('searchClient');
    const suggestionsBox = document.getElementById('clientSuggestions');

    searchInput.addEventListener('input', async function () {
        const query = this.value;
        if (query.length < 2) {
            suggestionsBox.style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`<?php echo BASE_URL; ?>agency/clients/search-api?q=${query}`);
            const clients = await response.json();

            suggestionsBox.innerHTML = '';
            if (clients.length > 0) {
                suggestionsBox.style.display = 'block';
                clients.forEach(client => {
                    const item = document.createElement('a');
                    item.className = 'list-group-item list-group-item-action';
                    item.innerHTML = `<strong>${client.nombre} ${client.apellido}</strong> <small class='text-muted'>(${client.dni})</small>`;
                    item.onclick = () => fillClientData(client);
                    suggestionsBox.appendChild(item);
                });
            } else {
                suggestionsBox.style.display = 'none';
            }
        } catch (e) {
            console.error('Error fetching clients', e);
        }
    });

    function fillClientData(client) {
        document.getElementById('cliente_id').value = client.id;
        document.getElementById('cliente_nombre').value = client.nombre;
        document.getElementById('cliente_apellido').value = client.apellido;
        document.getElementById('cliente_email').value = client.email;
        document.getElementById('cliente_telefono').value = client.telefono;
        suggestionsBox.style.display = 'none';
        searchInput.value = ''; // Limpiar buscador
    }

    // --- LÓGICA DE ITEMS (Venta) ---

    async function loadDepartures(select) {
        const row = select.closest('tr');
        const departureSelect = row.querySelector('.departure-select');
        const qtyInput = row.querySelector('.qty-input');
        const tourId = select.value;

        departureSelect.innerHTML = '<option>Cargando...</option>';
        departureSelect.disabled = true;

        if (!tourId) return;

        try {
            const response = await fetch(`<?php echo BASE_URL; ?>agency/reservations/get-departures?tour_id=${tourId}`);
            const departures = await response.json();

            departureSelect.innerHTML = '<option value="">Seleccione Salida...</option>';
            departures.forEach(dep => {
                const date = new Date(dep.fecha_salida + 'T' + dep.hora_salida);
                const dateStr = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                const option = document.createElement('option');
                option.value = dep.id;
                option.dataset.price = dep.precio_actual > 0 ? dep.precio_actual : 0; // Se debería traer precio tour base si es 0, pero asumimos lógica
                // AJUSTE: Necesitamos precio del tour si el de salida es 0. 
                // Por ahora asumimos que la API trae el precio correcto o lo ajustamos.
                option.textContent = `${dateStr} - Cupos: ${dep.cupos_disponibles}`;
                departureSelect.appendChild(option);
            });
            departureSelect.disabled = false;
        } catch (e) {
            console.error(e);
            departureSelect.innerHTML = '<option>Error al cargar</option>';
        }
    }

    function setPrice(select) {
        const row = select.closest('tr');
        const priceInput = row.querySelector('.price-input');
        const qtyInput = row.querySelector('.qty-input');
        const selectedOption = select.options[select.selectedIndex];

        let price = parseFloat(selectedOption.dataset.price) || 0;
        // Fix temporal: si precio es 0, poner 100 por defecto para probar (idealmente traer precio base del tour)
        if (price === 0) price = 100.00;

        priceInput.value = price.toFixed(2);
        qtyInput.disabled = false;
        calcSubtotal(qtyInput);
    }

    function calcSubtotal(element) {
        const row = element.closest('tr');
        const qty = parseInt(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const subtotal = qty * price;

        row.querySelector('.subtotal-display').textContent = 'S/ ' + subtotal.toFixed(2);
        calcGrandTotal();
    }

    function calcGrandTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseInt(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            total += qty * price;
        });
        document.getElementById('grandTotal').textContent = 'S/ ' + total.toFixed(2);
    }

    function addItemRow() {
        const tbody = document.querySelector('#itemsTable tbody');
        const firstRow = tbody.querySelector('tr');
        const newRow = firstRow.cloneNode(true);

        // Limpiar valores
        newRow.querySelector('.tour-select').value = '';
        newRow.querySelector('.departure-select').innerHTML = '<option value="">Primero elija tour...</option>';
        newRow.querySelector('.departure-select').disabled = true;
        newRow.querySelector('.qty-input').value = 1;
        newRow.querySelector('.qty-input').disabled = true;
        newRow.querySelector('.price-input').value = '';
        newRow.querySelector('.subtotal-display').textContent = 'S/ 0.00';

        tbody.appendChild(newRow);
    }

    function removeRow(btn) {
        const row = btn.closest('tr');
        if (document.querySelectorAll('.item-row').length > 1) {
            row.remove();
            calcGrandTotal();
        } else {
            alert("Debe haber al menos un item.");
        }
    }
</script>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
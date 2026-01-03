<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<style>
    .service-card {
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid var(--border-color);
        background: var(--bg-secondary);
    }

    .service-card:hover {
        transform: translateY(-2px);
        background: var(--hover-bg);
    }

    .service-card.active {
        border-color: var(--brand-primary);
        background-color: var(--hover-bg);
    }

    .composer-panel {
        background: var(--bg-secondary);
        border-right: 1px solid var(--border-color);
        min-height: 80vh;
    }

    .cart-panel {
        background: var(--bg-primary);
        min-height: 80vh;
    }

    .cart-table th {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background-color: var(--bg-secondary) !important;
        color: var(--text-muted) !important;
    }

    .cart-total-section {
        background: var(--bg-secondary);
        border-top: 2px solid var(--border-color);
        color: var(--text-primary);
    }

    .bg-light-dynamic {
        background-color: var(--bg-tertiary) !important;
    }
</style>

<div class="container-fluid p-0">
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-0 mb-0 border-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>¡Error!</strong> <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>agency/reservations/update" method="POST" id="reservationForm">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id" value="<?php echo $reservation['id']; ?>">

        <div class="row g-0">

            <!-- LEFT PANEL: COMPOSER (Constructor) -->
            <div class="col-lg-5 p-4 composer-panel">

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0 text-primary"><i class="bi bi-pencil-square me-2"></i>Editar Reserva
                        <?php echo $reservation['codigo_reserva']; ?>
                    </h4>
                    <a href="<?php echo BASE_URL; ?>agency/reservations/show?id=<?php echo $reservation['id']; ?>"
                        class="btn btn-sm btn-outline-secondary rounded-pill">
                        <i class="bi bi-arrow-left"></i> Volver al Detalle
                    </a>
                </div>

                <!-- 1. Cliente -->
                <div class="card shadow-sm border-0 mb-4 bg-light-dynamic">
                    <div class="card-body p-3">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-2"><i
                                class="bi bi-person-circle me-1"></i> Cliente</label>
                        <div class="position-relative">
                            <input type="text" class="form-control" id="searchClient"
                                placeholder="Buscar cliente por Nombre o DNI..." autocomplete="off"
                                style="display:none;">
                            <div id="clientSuggestions" class="list-group position-absolute w-100 shadow-lg mt-1"
                                style="z-index: 1000; display: none;"></div>
                        </div>

                        <!-- Hidden Inputs Real -->
                        <input type="hidden" name="cliente_id" id="cliente_id"
                            value="<?php echo $reservation['cliente_id']; ?>" required>
                        <div id="clientPreview" class="mt-2 small text-primary fw-bold">
                            <i class="bi bi-check-circle-fill"></i> <span id="clientNameDisplay">
                                <?php echo htmlspecialchars($reservation['cliente_nombre'] . ' ' . $reservation['cliente_apellido'] . ' (' . ($reservation['cliente_telefono'] ?: 'S/T') . ')'); ?>
                            </span>
                            <button type="button" class="btn btn-sm btn-link p-0 ms-2 text-muted"
                                onclick="enableClientSearch()">Cambiar</button>
                        </div>
                    </div>
                </div>

                <!-- 2. Tipo de Servicio -->
                <label class="form-label fw-bold small text-muted text-uppercase mb-2">1. Elige Tipo</label>
                <div class="row g-2 mb-3">
                    <div class="col-4">
                        <div class="p-3 border rounded text-center service-card active"
                            onclick="selectType('tour', this)">
                            <i class="bi bi-map fs-4 d-block mb-1 text-primary"></i>
                            <span class="small fw-bold">Tour</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 border rounded text-center service-card" onclick="selectType('hotel', this)">
                            <i class="bi bi-building fs-4 d-block mb-1 text-warning"></i>
                            <span class="small fw-bold">Hotel</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 border rounded text-center service-card"
                            onclick="selectType('restaurante', this)">
                            <i class="bi bi-cup-hot fs-4 d-block mb-1 text-danger"></i>
                            <span class="small fw-bold">Restaur.</span>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="selectedType" value="tour">

                <!-- 3. Configuración del Servicio -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">

                        <!-- SECCION TOUR -->
                        <div id="config-tour">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Selecciona Tour</label>
                                <select class="form-select" id="tourSelect" onchange="loadDepartures(this.value)">
                                    <option value="">-- Elige Tour --</option>
                                    <?php foreach ($tours as $tour): ?>
                                        <option value="<?php echo $tour['id']; ?>">
                                            <?php echo htmlspecialchars($tour['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Salida Programada</label>
                                <select class="form-select" id="departureSelect" disabled onchange="updateTourPrice()">
                                    <option value="">-- Primero elige Tour --</option>
                                </select>
                            </div>
                        </div>

                        <!-- SECCION PROVEEDOR (Hotel/Rest) -->
                        <div id="config-provider" style="display:none;">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Selecciona Proveedor</label>
                                <select class="form-select" id="providerSelect">
                                    <option value="">Cargando...</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Fecha / Detalle</label>
                                <input type="text" class="form-control" id="providerDetail"
                                    placeholder="Ej. Noche del 12/10">
                            </div>
                        </div>

                        <!-- PRECIO Y CANTIDAD -->
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Cantidad</label>
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="adjustQty(-1)">-</button>
                                    <input type="number" class="form-control text-center fw-bold" id="itemQty" value="1"
                                        min="1">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="adjustQty(1)">+</button>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">Precio Unit. (S/)</label>
                                <input type="number" step="0.01" class="form-control fw-bold" id="itemPrice"
                                    value="0.00">
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-primary btn-lg shadow-sm" onclick="addItemToCart()">
                                <i class="bi bi-cart-plus me-2"></i> AGREGAR A LA LISTA
                            </button>
                        </div>

                    </div>
                </div>

                <!-- 4. Notas -->
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Notas Adicionales</label>
                    <textarea name="notas" class="form-control bg-light-dynamic border-dynamic"
                        rows="2"><?php echo htmlspecialchars($reservation['notas']); ?></textarea>
                </div>
            </div>

            <!-- RIGHT PANEL: CART (Carrito) -->
            <div class="col-lg-7 p-4 cart-panel d-flex flex-column">

                <div
                    class="bg-surface-dynamic rounded shadow-sm flex-grow-1 d-flex flex-column overflow-hidden border border-dynamic">
                    <div
                        class="p-3 border-bottom border-dynamic bg-surface-dynamic d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dynamic ls-1">DETALLE DE VENTA MODIFICADO</h6>
                        <span class="badge border border-dynamic text-dynamic bg-soft-dynamic" id="itemCountBadge">0
                            items</span>
                    </div>

                    <!-- Table Scrollable -->
                    <div class="table-responsive flex-grow-1" style="background: var(--bg-secondary);">
                        <table class="table table-hover align-middle mb-0 cart-table" id="cartTable">
                            <thead class="bg-surface-dynamic sticky-top">
                                <tr>
                                    <th class="ps-4">Descripción</th>
                                    <th class="text-center" style="width: 15%;">Cant.</th>
                                    <th class="text-end" style="width: 20%;">Precio</th>
                                    <th class="text-end" style="width: 20%;">Total</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody id="cartBody">
                                <!-- Pre-populated by JS -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Totals -->
                    <div class="cart-total-section p-4">
                        <div class="row mb-2 text-dynamic">
                            <div class="col-6 text-end text-muted">Subtotal:</div>
                            <div class="col-6 text-end fw-bold" id="lblSubtotal">S/ 0.00</div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-6 text-end text-danger small">Descuento Global (-):</div>
                            <div class="col-6 text-end">
                                <input type="number" name="descuento"
                                    class="form-control form-control-sm d-inline-block text-end text-danger fw-bold border-0 bg-light-dynamic"
                                    style="width: 100px;"
                                    value="<?php echo number_format($reservation['descuento'], 2, '.', ''); ?>" min="0"
                                    step="0.01" oninput="recalcCart()">
                            </div>
                        </div>
                        <div class="row pt-3 border-top border-dynamic pb-3">
                            <div class="col-6 text-end fs-5 fw-bold text-dynamic">Nuevo Total:</div>
                            <div class="col-6 text-end fs-4 fw-bold text-primary" id="lblTotal">S/ 0.00</div>
                        </div>

                        <!-- Summary info (Recalculated) -->
                        <div class="bg-light-dynamic p-3 rounded border">
                            <div class="row g-2">
                                <div class="col-6 text-muted small">Pagado hasta ahora:</div>
                                <div class="col-6 text-end fw-bold text-success" id="lblPaid">
                                    S/
                                    <?php echo number_format($reservation['precio_total'] - $reservation['saldo_pendiente'], 2); ?>
                                </div>
                                <div class="col-6 text-muted small">Saldo a Cubrir:</div>
                                <div class="col-6 text-end fw-bold text-danger" id="lblSaldo">S/ 0.00</div>
                            </div>
                        </div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary fw-bold py-2">
                                ACTUALIZAR RESERVA <i class="bi bi-save ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- SCRIPTS -->
<script>
    // --- STATE ---
    let cart = []; // Array of objects: {type, id, name, detail, qty, price, subtotal, detail_id(if tour)}
    let currentType = 'tour';
    const totalPaid = <?php echo ($reservation['precio_total'] - $reservation['saldo_pendiente']); ?>;

    // --- INITIAL LOAD ---
    document.addEventListener('DOMContentLoaded', function () {
        // Load existing details into cart array
        <?php foreach ($details as $d): ?>
            cart.push({
                type: '<?php echo $d['tipo_servicio']; ?>',
                id: <?php echo ($d['tipo_servicio'] === 'tour') ? '0' : $d['servicio_id']; ?>,
                detail_id: <?php echo ($d['tipo_servicio'] === 'tour') ? $d['servicio_id'] : '0'; ?>,
                name: '<?php echo addslashes($d['servicio_nombre']); ?>',
                detail: '<?php echo ($d['tipo_servicio'] === 'tour') ? ($d['fecha_salida'] . ' ' . substr($d['hora_salida'] ?? '', 0, 5)) : 'Extra'; ?>',
                qty: <?php echo $d['cantidad']; ?>,
                price: <?php echo $d['precio_unitario']; ?>
            });
        <?php endforeach; ?>
        renderCart();
    });

    // Same logic as create.php but adapted for updates
    function enableClientSearch() {
        document.getElementById('searchClient').style.display = 'block';
        document.getElementById('clientPreview').style.display = 'none';
        document.getElementById('cliente_id').value = '';
    }

    function selectType(type, element) {
        currentType = type;
        document.getElementById('selectedType').value = type;
        document.querySelectorAll('.service-card').forEach(el => el.classList.remove('active'));
        if (element) element.classList.add('active');

        const tourConfig = document.getElementById('config-tour');
        const providerConfig = document.getElementById('config-provider');

        if (type === 'tour') {
            tourConfig.style.display = 'block';
            providerConfig.style.display = 'none';
            document.getElementById('itemPrice').readOnly = false;
        } else {
            tourConfig.style.display = 'none';
            providerConfig.style.display = 'block';
            document.getElementById('itemPrice').readOnly = false;
            loadProviders(type);
        }
    }

    async function loadDepartures(tourId) {
        const depSelect = document.getElementById('departureSelect');
        depSelect.innerHTML = '<option>Cargando...</option>';
        depSelect.disabled = true;
        if (!tourId) return;
        try {
            const res = await fetch(`<?php echo BASE_URL; ?>agency/reservations/get-departures?tour_id=${tourId}`);
            const data = await res.json();
            depSelect.innerHTML = '<option value="">-- Selecciona Salida --</option>';
            data.forEach(d => {
                const date = new Date(d.fecha_salida + 'T' + d.hora_salida);
                const str = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                const opt = document.createElement('option');
                opt.value = d.id;
                opt.dataset.price = d.precio_actual;
                opt.text = `${str} (Cupos: ${d.cupos_disponibles})`;
                depSelect.appendChild(opt);
            });
            depSelect.disabled = false;
        } catch (e) { console.error(e); }
    }

    function updateTourPrice() {
        const depSelect = document.getElementById('departureSelect');
        const opt = depSelect.options[depSelect.selectedIndex];
        if (opt && opt.dataset.price) {
            document.getElementById('itemPrice').value = parseFloat(opt.dataset.price).toFixed(2);
        }
    }

    async function loadProviders(type) {
        const sel = document.getElementById('providerSelect');
        sel.innerHTML = '<option>Cargando...</option>';
        try {
            const res = await fetch(`<?php echo BASE_URL; ?>agency/resources/get-by-type?type=${type}`);
            const data = await res.json();
            sel.innerHTML = '<option value="">-- Elige Opción --</option>';
            data.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.text = p.nombre;
                sel.appendChild(opt);
            });
        } catch (e) { console.error(e); }
    }

    function adjustQty(delta) {
        const el = document.getElementById('itemQty');
        let v = parseInt(el.value) || 1;
        v += delta;
        if (v < 1) v = 1;
        el.value = v;
    }

    function addItemToCart() {
        const type = currentType;
        const qty = parseInt(document.getElementById('itemQty').value) || 1;
        const price = parseFloat(document.getElementById('itemPrice').value) || 0;
        let id = 0, name = '', detail = '', detail_id = 0;

        if (type === 'tour') {
            const tourSel = document.getElementById('tourSelect');
            const depSel = document.getElementById('departureSelect');
            if (!tourSel.value || !depSel.value) { alert('Selecciona el Tour y la Salida'); return; }
            detail_id = depSel.value;
            name = tourSel.options[tourSel.selectedIndex].text;
            detail = depSel.options[depSel.selectedIndex].text;
        } else {
            const provSel = document.getElementById('providerSelect');
            const detailInput = document.getElementById('providerDetail');
            if (!provSel.value) { alert('Selecciona el Proveedor'); return; }
            id = provSel.value;
            name = provSel.options[provSel.selectedIndex].text;
            detail = detailInput.value || 'General';
            detail_id = 0;
        }

        cart.push({ type, id, detail_id, name, detail, qty, price });
        renderCart();
        document.getElementById('itemQty').value = 1;
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function renderCart() {
        const tbody = document.getElementById('cartBody');
        tbody.innerHTML = '';
        if (cart.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-5 text-muted">Lista vacía.</td></tr>`;
            updateTotals(0);
            return;
        }
        let subtotalSum = 0;
        cart.forEach((item, idx) => {
            const itemSubtotal = item.qty * item.price;
            subtotalSum += itemSubtotal;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="ps-4">
                    <div class="fw-bold text-dark mb-0">${item.name}</div>
                    <small class="text-muted"><span class="badge bg-light text-secondary border me-1">${item.type.toUpperCase()}</span> ${item.detail}</small>
                    <input type="hidden" name="tipos[]" value="${item.type}">
                    <input type="hidden" name="servicios[]" value="${item.id}"> 
                    <input type="hidden" name="detalles[]" value="${item.detail_id}">
                    <input type="hidden" name="cantidades[]" value="${item.qty}">
                    <input type="hidden" name="precios[]" value="${item.price}">
                </td>
                <td class="text-center fw-bold">${item.qty}</td>
                <td class="text-end">S/ ${item.price.toFixed(2)}</td>
                <td class="text-end fw-bold text-dark">S/ ${itemSubtotal.toFixed(2)}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm text-danger p-0" onclick="removeItem(${idx})"><i class="bi bi-x-circle-fill"></i></button>
                </td>
            `;
            tbody.appendChild(row);
        });
        document.getElementById('itemCountBadge').textContent = `${cart.length} items`;
        updateTotals(subtotalSum);
    }

    function updateTotals(subtotal) {
        document.getElementById('lblSubtotal').textContent = 'S/ ' + subtotal.toFixed(2);
        const discountInput = document.querySelector('input[name="descuento"]');
        let discount = parseFloat(discountInput.value) || 0;
        const total = Math.max(0, subtotal - discount);
        document.getElementById('lblTotal').textContent = 'S/ ' + total.toFixed(2);
        const balance = Math.max(0, total - totalPaid);
        const balEl = document.getElementById('lblSaldo');
        balEl.textContent = 'S/ ' + balance.toFixed(2);
        balEl.className = balance <= 0 ? 'fw-bold text-success' : 'fw-bold text-danger';
    }

    function recalcCart() {
        let subtotal = 0;
        cart.forEach(i => subtotal += i.qty * i.price);
        updateTotals(subtotal);
    }

    // Client search logic same as create.php
    const searchInput = document.getElementById('searchClient');
    const suggestionsBox = document.getElementById('clientSuggestions');
    searchInput.addEventListener('input', async function () {
        const q = this.value;
        if (q.length < 2) { suggestionsBox.style.display = 'none'; return; }
        try {
            const res = await fetch(`<?php echo BASE_URL; ?>agency/clients/search-api?q=${q}`);
            const data = await res.json();
            suggestionsBox.innerHTML = '';
            if (data.length > 0) {
                suggestionsBox.style.display = 'block';
                data.forEach(c => {
                    const a = document.createElement('a');
                    a.className = 'list-group-item list-group-item-action small';
                    a.innerHTML = `<i class="bi bi-person me-2"></i><strong>${c.nombre} ${c.apellido}</strong> <span class="text-muted">(${c.dni})</span>`;
                    a.onclick = () => selectClient(c);
                    suggestionsBox.appendChild(a);
                });
            } else { suggestionsBox.style.display = 'none'; }
        } catch (e) { }
    });
    function selectClient(c) {
        document.getElementById('cliente_id').value = c.id;
        document.getElementById('searchClient').style.display = 'none';
        document.getElementById('clientSuggestions').style.display = 'none';
        const preview = document.getElementById('clientPreview');
        preview.style.display = 'block';
        document.getElementById('clientNameDisplay').textContent = `${c.nombre} ${c.apellido} (${c.dni})`;
    }

    // --- PREVENT ENTER ON SEARCH & FORM VALIDATION ---
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            return false;
        }
    });

    document.getElementById('reservationForm').addEventListener('submit', function (e) {
        const clienteId = document.getElementById('cliente_id').value;
        if (!clienteId) {
            e.preventDefault();
            alert('Por favor, selecciona un cliente de la lista sugerida antes de actualizar la reserva.');
            if (document.getElementById('searchClient').style.display !== 'none') {
                searchInput.focus();
            }
            return false;
        }

        if (cart.length === 0) {
            e.preventDefault();
            alert('Debes mantener al menos un servicio en la lista.');
            return false;
        }
    });
</script>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
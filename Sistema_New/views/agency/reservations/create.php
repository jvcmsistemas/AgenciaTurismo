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

    .cart-total-section {
        background: var(--bg-secondary);
        border-top: 2px solid var(--border-color);
        color: var(--text-primary);
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

    <form action="<?php echo BASE_URL; ?>agency/reservations/store" method="POST" id="reservationForm">
        <?php echo csrf_field(); ?>
        <div class="row g-0">

            <!-- LEFT PANEL: COMPOSER (Constructor) -->
            <div class="col-lg-5 p-4 composer-panel">

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0 text-primary"><i class="bi bi-magic me-2"></i>Nueva Reserva</h4>
                    <a href="<?php echo BASE_URL; ?>agency/reservations"
                        class="btn btn-sm btn-outline-secondary rounded-pill">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>

                <!-- 1. Cliente -->
                <div class="card shadow-sm border-0 mb-4 bg-light-dynamic">
                    <div class="card-body p-3">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-2"><i
                                class="bi bi-person-circle me-1"></i> Cliente</label>
                        <div class="position-relative">
                            <input type="text" class="form-control" id="searchClient"
                                placeholder="Buscar cliente por Nombre o DNI..." autocomplete="off">
                            <div id="clientSuggestions" class="list-group position-absolute w-100 shadow-lg mt-1"
                                style="z-index: 1000; display: none;"></div>
                        </div>

                        <!-- Hidden Inputs Real -->
                        <input type="hidden" name="cliente_id" id="cliente_id" required>
                        <div id="clientPreview" class="mt-2 small text-primary fw-bold" style="display:none;">
                            <i class="bi bi-check-circle-fill"></i> <span id="clientNameDisplay"></span>
                        </div>

                        <!-- Fallback Fields (Hidden by default, shown if new) -->
                        <div class="row g-2 mt-2" id="newClientFields" style="display:none;">
                            <!-- Podríamos expandirlo si queremos creación rápida manual -->
                            <!-- Por simplicidad en este diseño split, asumimos búsqueda o creación externa, pero dejaremos esto clean -->
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
                    <!-- Otros tipos ocultos en select si fuera necesario -->
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
            </div>

            <!-- RIGHT PANEL: CART (Carrito) -->
            <div class="col-lg-7 p-4 cart-panel d-flex flex-column">

                <div
                    class="bg-surface-dynamic rounded shadow-sm flex-grow-1 d-flex flex-column overflow-hidden border border-dynamic">
                    <div
                        class="p-3 border-bottom border-dynamic bg-surface-dynamic d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dynamic ls-1">DETALLE DE VENTA</h6>
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
                                <!-- Empty State -->
                                <tr id="emptyCartRow">
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-cart-x display-4 d-block mb-3 opacity-25"></i>
                                        La lista está vacía. Agrega servicios desde el panel izquierdo.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Totals -->
                    <div class="cart-total-section p-4">
                        <div class="row mb-2">
                            <div class="col-6 text-end text-muted">Subtotal:</div>
                            <div class="col-6 text-end fw-bold text-dynamic" id="lblSubtotal">S/ 0.00</div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-6 text-end text-danger small">Descuento Global (-):</div>
                            <div class="col-6 text-end">
                                <input type="number" name="descuento"
                                    class="form-control form-control-sm d-inline-block text-end text-danger fw-bold border-0 bg-light"
                                    style="width: 100px;" value="0.00" min="0" step="0.01" oninput="recalcCart()"
                                    placeholder="0.00">
                            </div>
                        </div>
                        <div class="row pt-3 border-top border-dynamic pb-3">
                            <div class="col-6 text-end fs-5 fw-bold text-dynamic">Total a Pagar:</div>
                            <div class="col-6 text-end fs-4 fw-bold text-primary" id="lblTotal">S/ 0.00</div>
                        </div>

                        <!-- Payment Box -->
                        <div class="bg-light-dynamic p-3 rounded border">
                            <h6 class="fw-bold fs-7 text-uppercase mb-2 text-success"><i
                                    class="bi bi-cash-coin me-1"></i> Confirmar Pago Inicial</h6>
                            <div class="row g-2">
                                <div class="col-md-5">
                                    <select class="form-select form-select-sm bg-dynamic border-dynamic"
                                        name="metodo_pago">
                                        <option value="efectivo">Efectivo</option>
                                        <option value="yape">Yape / Plin</option>
                                        <option value="tarjeta">Tarjeta</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number"
                                        class="form-control form-control-sm fw-bold text-success bg-dynamic border-dynamic"
                                        name="pago_inicial" placeholder="A cuenta" min="0" step="0.01"
                                        oninput="calcBalance()">
                                </div>
                                <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
                                    <small class="text-muted me-2">Saldo:</small>
                                    <span class="fw-bold text-danger" id="lblSaldo">S/ 0.00</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-success fw-bold py-2">
                                CONFIRMAR Y GENERAR <i class="bi bi-arrow-right-circle-fill ms-2"></i>
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

    // --- COMPOSER LOGIC ---

    function selectType(type, element) {
        currentType = type;
        document.getElementById('selectedType').value = type;

        // UI Active Class
        document.querySelectorAll('.service-card').forEach(el => el.classList.remove('active'));
        if (element) element.classList.add('active');

        // Toggle Config Views
        const tourConfig = document.getElementById('config-tour');
        const providerConfig = document.getElementById('config-provider');

        if (type === 'tour') {
            tourConfig.style.display = 'block';
            providerConfig.style.display = 'none';
            // document.getElementById('itemPrice').readOnly = true; // Permitemos editar precio
            document.getElementById('itemPrice').readOnly = false;
        } else {
            tourConfig.style.display = 'none';
            providerConfig.style.display = 'block';
            document.getElementById('itemPrice').readOnly = false; // Providers manual price
            document.getElementById('itemPrice').value = '';
            document.getElementById('itemPrice').focus();

            loadProviders(type);
        }
    }

    // --- DATA LOADING ---

    // TOURS: Ya cargados en PHP loop, solo manejamos Departures
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

    // PROVIDERS
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

    // --- CART LOGIC ---

    function adjustQty(delta) {
        const el = document.getElementById('itemQty');
        let v = parseInt(el.value) || 1;
        v += delta;
        if (v < 1) v = 1;
        el.value = v;
    }

    function addItemToCart() {
        // 1. Gather Data
        const type = currentType;
        const qty = parseInt(document.getElementById('itemQty').value) || 1;
        const price = parseFloat(document.getElementById('itemPrice').value) || 0;

        let id = 0; // The Main Service ID (Provider ID or Tour ID logic)
        let name = '';
        let detail = ''; // Description
        let detail_id = 0; // Departure ID for tours

        if (type === 'tour') {
            const tourSel = document.getElementById('tourSelect');
            const depSel = document.getElementById('departureSelect');

            if (!tourSel.value || !depSel.value) { alert('Selecciona el Tour y la Salida'); return; }

            id = 0; // For Tour logic, we send Departure ID as detail. Logic in Controller: tipo='tour' -> detail has value.
            // Wait, old controller logic: if type=tour, servicio_id comes from 'detalles[]' (which is DepartureID).
            // So we need to map correctly. 
            // Controller: $servicioId = $detalles[$i] (Departure) if tour.

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

        // 2. Add to Array
        cart.push({
            type: type,
            id: id,            // Provider ID (or 0 for tour)
            detail_id: detail_id, // Departure ID (or 0 for prov)
            name: name,
            detail: detail,
            qty: qty,
            price: price
        });

        // 3. Render & Clear
        renderCart();

        // Reset Inputs
        document.getElementById('itemQty').value = 1;
        if (type !== 'tour') document.getElementById('providerDetail').value = '';
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function renderCart() {
        const tbody = document.getElementById('cartBody');
        tbody.innerHTML = '';

        if (cart.length === 0) {
            tbody.innerHTML = `<tr id="emptyCartRow"><td colspan="5" class="text-center py-5 text-muted"><i class="bi bi-cart-x display-4 d-block mb-3 opacity-25"></i>Lista vacía.</td></tr>`;
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
                    <!-- HIDDEN INPUTS FOR POST -->
                    <input type="hidden" name="tipos[]" value="${item.type}">
                    <input type="hidden" name="servicios[]" value="${item.id}"> 
                    <input type="hidden" name="detalles[]" value="${item.detail_id}">
                    <input type="hidden" name="cantidades[]" value="${item.qty}">
                    <input type="hidden" name="precios[]" value="${item.price}">
                </td>
                <td class="text-center fw-bold">${item.qty}</td>
                <td class="text-end">${CURRENCY_SYMBOL} ${item.price.toFixed(2)}</td>
                <td class="text-end fw-bold text-dark">${CURRENCY_SYMBOL} ${itemSubtotal.toFixed(2)}</td>
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
        document.getElementById('lblSubtotal').textContent = CURRENCY_SYMBOL + ' ' + subtotal.toFixed(2);

        const discountInput = document.querySelector('input[name="descuento"]');
        let discount = parseFloat(discountInput.value) || 0;

        if (discount > subtotal) {
            discount = subtotal;
            // discountInput.value = discount.toFixed(2);
        }

        const total = subtotal - discount;
        document.getElementById('lblTotal').textContent = CURRENCY_SYMBOL + ' ' + total.toFixed(2);

        // Update Balance
        const paidInput = document.querySelector('input[name="pago_inicial"]');
        let paid = parseFloat(paidInput.value) || 0;
        if (paid > total) paid = total;

        const balance = total - paid;
        const balEl = document.getElementById('lblSaldo');
        balEl.textContent = CURRENCY_SYMBOL + ' ' + balance.toFixed(2);

        if (balance <= 0) {
            balEl.classList.remove('text-danger');
            balEl.classList.add('text-success');
        } else {
            balEl.classList.remove('text-success');
            balEl.classList.add('text-danger');
        }
    }

    function recalcCart() {
        // Triggered by oninput in discount/paid
        // We need to re-sum everything from UI or Global State? 
        // Better re-use renderCart() logic but separated.
        // Quickest: Get subtotal from Label text? No, unsafe. 
        // Re-calculta from cart state.
        let subtotal = 0;
        cart.forEach(i => subtotal += i.qty * i.price);
        updateTotals(subtotal);
    }

    // Alias for payment input
    function calcBalance() { recalcCart(); }

    // --- CLIENT SEARCH ---
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

        // Add edit button logic if needed
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
            alert('Por favor, selecciona un cliente de la lista sugerida antes de confirmar la reserva.');
            searchInput.focus();
            return false;
        }

        if (cart.length === 0) {
            e.preventDefault();
            alert('Debes agregar al menos un servicio a la lista.');
            return false;
        }
    });

</script>
<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
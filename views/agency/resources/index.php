<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-success mb-1">Gestión de Recursos</h2>
        <p class="text-muted mb-0">Administra tu equipo, flota y aliados estratégicos.</p>
    </div>
</div>

<!-- Tabs de Navegación -->
<ul class="nav nav-pills mb-4 glass-card p-2 rounded-pill d-inline-flex" id="resourceTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill active px-4" id="guides-tab" data-bs-toggle="pill" data-bs-target="#guides" type="button" role="tab" onclick="updateUrlTab('guides')">
            <i class="bi bi-person-badge me-2"></i>Guías
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4" id="transport-tab" data-bs-toggle="pill" data-bs-target="#transport" type="button" role="tab" onclick="updateUrlTab('transport')">
            <i class="bi bi-bus-front me-2"></i>Flota / Transporte
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4" id="providers-tab" data-bs-toggle="pill" data-bs-target="#providers" type="button" role="tab" onclick="updateUrlTab('providers')">
            <i class="bi bi-shop me-2"></i>Proveedores
        </button>
    </li>
</ul>

<div class="tab-content" id="resourceTabsContent">
    
    <!-- TAB: GUÍAS -->
    <div class="tab-pane fade show active" id="guides" role="tabpanel">
        <div class="card glass-card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-success mb-0">Staff de Guías</h5>
                    <button class="btn btn-success rounded-pill" onclick="openGuideModal()">
                        <i class="bi bi-plus-lg me-2"></i>Nuevo Guía
                    </button>
                </div>
                <!-- Buscador Guías -->
                <form class="d-flex" method="GET" action="">
                    <input type="hidden" name="tab" value="guides">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Buscar guía por nombre o DNI..." value="<?php echo ($_GET['tab'] ?? '') === 'guides' ? htmlspecialchars($_GET['search'] ?? '') : ''; ?>">
                        <button class="btn btn-outline-success" type="submit">Buscar</button>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Nombre</th>
                                <th>DNI</th>
                                <th>Teléfono</th>
                                <th>Idiomas</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($guides)): ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">No hay guías registrados.</td></tr>
                            <?php else: ?>
                                <?php foreach ($guides as $guide): ?>
                                <tr>
                                    <td class="ps-4 fw-bold"><?php echo htmlspecialchars($guide['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($guide['dni']); ?></td>
                                    <td><?php echo htmlspecialchars($guide['telefono']); ?></td>
                                    <td><span class="badge bg-info bg-opacity-10 text-info"><?php echo htmlspecialchars($guide['idiomas']); ?></span></td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light text-primary rounded-circle me-1" 
                                                onclick='openGuideModal(<?php echo json_encode($guide); ?>)'>
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a href="<?php echo BASE_URL; ?>agency/resources/delete-guide?id=<?php echo $guide['id']; ?>" 
                                           class="btn btn-sm btn-light text-danger rounded-circle" onclick="return confirm('¿Eliminar guía?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB: TRANSPORTE -->
    <div class="tab-pane fade" id="transport" role="tabpanel">
        <div class="card glass-card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-success mb-0">Flota de Vehículos</h5>
                    <button class="btn btn-success rounded-pill" onclick="openTransportModal()">
                        <i class="bi bi-plus-lg me-2"></i>Nuevo Vehículo
                    </button>
                </div>
                <!-- Buscador Transporte -->
                <form class="d-flex" method="GET" action="">
                    <input type="hidden" name="tab" value="transport">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Buscar por placa, modelo o chofer..." value="<?php echo ($_GET['tab'] ?? '') === 'transport' ? htmlspecialchars($_GET['search'] ?? '') : ''; ?>">
                        <button class="btn btn-outline-success" type="submit">Buscar</button>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Placa</th>
                                <th>Modelo</th>
                                <th>Capacidad</th>
                                <th>Chofer Habitual</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($transports)): ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">No hay vehículos registrados.</td></tr>
                            <?php else: ?>
                                <?php foreach ($transports as $transport): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-uppercase"><?php echo htmlspecialchars($transport['placa']); ?></td>
                                    <td><?php echo htmlspecialchars($transport['modelo']); ?></td>
                                    <td><span class="badge bg-warning bg-opacity-10 text-warning text-dark"><?php echo $transport['capacidad']; ?> Asientos</span></td>
                                    <td>
                                        <div><?php echo htmlspecialchars($transport['chofer_nombre'] ?? '-'); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($transport['chofer_telefono'] ?? ''); ?></small>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light text-primary rounded-circle me-1" 
                                                onclick='openTransportModal(<?php echo json_encode($transport); ?>)'>
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a href="<?php echo BASE_URL; ?>agency/resources/delete-transport?id=<?php echo $transport['id']; ?>" 
                                           class="btn btn-sm btn-light text-danger rounded-circle" onclick="return confirm('¿Eliminar vehículo?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB: PROVEEDORES -->
    <div class="tab-pane fade" id="providers" role="tabpanel">
        <div class="card glass-card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-success mb-0">Proveedores (Hoteles/Restaurantes)</h5>
                    <button class="btn btn-success rounded-pill" onclick="openProviderModal()">
                        <i class="bi bi-plus-lg me-2"></i>Nuevo Proveedor
                    </button>
                </div>
                <!-- Buscador Proveedores -->
                <form class="d-flex" method="GET" action="">
                    <input type="hidden" name="tab" value="providers">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Buscar proveedor..." value="<?php echo ($_GET['tab'] ?? '') === 'providers' ? htmlspecialchars($_GET['search'] ?? '') : ''; ?>">
                        <button class="btn btn-outline-success" type="submit">Buscar</button>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Nombre Comercial</th>
                                <th>Tipo</th>
                                <th>Contacto</th>
                                <th>Ubicación</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($providers)): ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">No hay proveedores registrados.</td></tr>
                            <?php else: ?>
                                <?php foreach ($providers as $provider): ?>
                                <tr>
                                    <td class="ps-4 fw-bold"><?php echo htmlspecialchars($provider['nombre']); ?></td>
                                    <td>
                                        <?php 
                                        $icon = match($provider['tipo']) {
                                            'restaurante' => 'bi-cup-hot',
                                            'hotel' => 'bi-house-heart',
                                            'ticket' => 'bi-ticket-perforated',
                                            default => 'bi-box-seam'
                                        };
                                        ?>
                                        <i class="bi <?php echo $icon; ?> me-1 text-muted"></i>
                                        <?php echo ucfirst($provider['tipo']); ?>
                                    </td>
                                    <td>
                                        <div><?php echo htmlspecialchars($provider['contacto_nombre']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($provider['telefono']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($provider['ubicacion']); ?></td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light text-primary rounded-circle me-1" 
                                                onclick='openProviderModal(<?php echo json_encode($provider); ?>)'>
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a href="<?php echo BASE_URL; ?>agency/resources/delete-provider?id=<?php echo $provider['id']; ?>" 
                                           class="btn btn-sm btn-light text-danger rounded-circle" onclick="return confirm('¿Eliminar proveedor?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODALES MEJORADOS -->

<!-- Modal Guía -->
<div class="modal fade" id="modalGuide" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content glass-card border-0 shadow-lg" id="formGuide" action="<?php echo BASE_URL; ?>agency/resources/store-guide" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" id="guide_id">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold text-success" id="modalGuideTitle"><i class="bi bi-person-badge me-2"></i>Registrar Guía</h5>
                    <p class="text-muted small mb-0">Ingrese los datos del guía turístico.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre y Apellidos <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                        <input type="text" name="nombre" id="guide_nombre" class="form-control border-start-0" placeholder="Ej. Juan Pérez" required>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">DNI / Documento</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-card-heading"></i></span>
                            <input type="text" name="dni" id="guide_dni" class="form-control border-start-0" placeholder="Ej. 12345678">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Teléfono / WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-whatsapp"></i></span>
                            <input type="text" name="telefono" id="guide_telefono" class="form-control border-start-0" placeholder="Ej. 999 888 777">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="guide_email" class="form-control border-start-0" placeholder="correo@ejemplo.com">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Idiomas Dominados</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-translate"></i></span>
                        <input type="text" name="idiomas" id="guide_idiomas" class="form-control border-start-0" value="Español" placeholder="Ej. Español, Inglés, Francés">
                    </div>
                    <div class="form-text">Separe los idiomas con comas.</div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success rounded-pill px-4">Guardar Guía</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Transporte -->
<div class="modal fade" id="modalTransport" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content glass-card border-0 shadow-lg" id="formTransport" action="<?php echo BASE_URL; ?>agency/resources/store-transport" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" id="transport_id">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold text-success" id="modalTransportTitle"><i class="bi bi-bus-front me-2"></i>Registrar Vehículo</h5>
                    <p class="text-muted small mb-0">Gestione su flota de transporte.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Placa del Vehículo <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-card-text"></i></span>
                            <input type="text" name="placa" id="transport_placa" class="form-control border-start-0 text-uppercase" placeholder="ABC-123" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Capacidad (Pasajeros) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-people-fill"></i></span>
                            <input type="number" name="capacidad" id="transport_capacidad" class="form-control border-start-0" placeholder="Ej. 20" required min="1">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Modelo y Marca</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-car-front"></i></span>
                        <input type="text" name="modelo" id="transport_modelo" class="form-control border-start-0" placeholder="Ej. Toyota Coaster 2023">
                    </div>
                </div>
                
                <div class="bg-light rounded-3 p-3 mb-2">
                    <h6 class="fw-bold text-success mb-3"><i class="bi bi-person-vcard me-2"></i>Datos del Conductor Habitual</h6>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nombre del Conductor</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-person"></i></span>
                            <input type="text" name="chofer_nombre" id="transport_chofer_nombre" class="form-control border-start-0" placeholder="Nombre completo">
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Teléfono de Contacto</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="chofer_telefono" id="transport_chofer_telefono" class="form-control border-start-0" placeholder="Celular del conductor">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success rounded-pill px-4">Guardar Vehículo</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Proveedor -->
<div class="modal fade" id="modalProvider" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content glass-card border-0 shadow-lg" id="formProvider" action="<?php echo BASE_URL; ?>agency/resources/store-provider" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" id="provider_id">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold text-success" id="modalProviderTitle"><i class="bi bi-shop me-2"></i>Registrar Proveedor</h5>
                    <p class="text-muted small mb-0">Aliados estratégicos (Hoteles, Restaurantes, etc.)</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre del Establecimiento <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-building"></i></span>
                        <input type="text" name="nombre" id="provider_nombre" class="form-control border-start-0" placeholder="Ej. Restaurante El Valle" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tipo de Servicio</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-tag"></i></span>
                        <select name="tipo" id="provider_tipo" class="form-select border-start-0">
                            <option value="restaurante">Restaurante / Alimentación</option>
                            <option value="hotel">Hotel / Hospedaje</option>
                            <option value="ticket">Entradas / Tickets</option>
                            <option value="transporte_externo">Transporte Externo</option>
                            <option value="otro">Otro Servicio</option>
                        </select>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contacto / Gerente</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                            <input type="text" name="contacto_nombre" id="provider_contacto_nombre" class="form-control border-start-0" placeholder="Nombre del encargado">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="telefono" id="provider_telefono" class="form-control border-start-0" placeholder="Teléfono fijo o celular">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Ubicación / Dirección</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" name="ubicacion" id="provider_ubicacion" class="form-control border-start-0" placeholder="Ej. Av. Sol 345, Cusco">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success rounded-pill px-4">Guardar Proveedor</button>
            </div>
        </form>
    </div>
</div>

<script>
    const baseUrl = '<?php echo BASE_URL; ?>';

    // Mantener la pestaña activa al recargar si viene por URL
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab) {
            const tabTrigger = document.querySelector(`#${tab}-tab`);
            if (tabTrigger) {
                const tabInstance = new bootstrap.Tab(tabTrigger);
                tabInstance.show();
            }
        }
    });

    function updateUrlTab(tabName) {
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        // Limpiar search al cambiar de tab para evitar confusión
        url.searchParams.delete('search');
        window.history.pushState({}, '', url);
    }

    // --- MODAL LOGIC ---

    function openGuideModal(data = null) {
        const form = document.getElementById('formGuide');
        const title = document.getElementById('modalGuideTitle');
        const titleText = title.querySelector('i').outerHTML + (data ? ' Editar Guía' : ' Registrar Guía');
        
        title.innerHTML = titleText;
        
        if (data) {
            form.action = baseUrl + 'agency/resources/update-guide';
            document.getElementById('guide_id').value = data.id;
            document.getElementById('guide_nombre').value = data.nombre;
            document.getElementById('guide_dni').value = data.dni;
            document.getElementById('guide_telefono').value = data.telefono;
            document.getElementById('guide_email').value = data.email;
            document.getElementById('guide_idiomas').value = data.idiomas;
        } else {
            form.action = baseUrl + 'agency/resources/store-guide';
            form.reset();
            document.getElementById('guide_id').value = '';
        }
        new bootstrap.Modal(document.getElementById('modalGuide')).show();
    }

    function openTransportModal(data = null) {
        const form = document.getElementById('formTransport');
        const title = document.getElementById('modalTransportTitle');
        const titleText = title.querySelector('i').outerHTML + (data ? ' Editar Vehículo' : ' Registrar Vehículo');
        
        title.innerHTML = titleText;
        
        if (data) {
            form.action = baseUrl + 'agency/resources/update-transport';
            document.getElementById('transport_id').value = data.id;
            document.getElementById('transport_placa').value = data.placa;
            document.getElementById('transport_capacidad').value = data.capacidad;
            document.getElementById('transport_modelo').value = data.modelo;
            document.getElementById('transport_chofer_nombre').value = data.chofer_nombre;
            document.getElementById('transport_chofer_telefono').value = data.chofer_telefono;
        } else {
            form.action = baseUrl + 'agency/resources/store-transport';
            form.reset();
            document.getElementById('transport_id').value = '';
        }
        new bootstrap.Modal(document.getElementById('modalTransport')).show();
    }

    function openProviderModal(data = null) {
        const form = document.getElementById('formProvider');
        const title = document.getElementById('modalProviderTitle');
        const titleText = title.querySelector('i').outerHTML + (data ? ' Editar Proveedor' : ' Registrar Proveedor');
        
        title.innerHTML = titleText;
        
        if (data) {
            form.action = baseUrl + 'agency/resources/update-provider';
            document.getElementById('provider_id').value = data.id;
            document.getElementById('provider_nombre').value = data.nombre;
            document.getElementById('provider_tipo').value = data.tipo;
            document.getElementById('provider_contacto_nombre').value = data.contacto_nombre;
            document.getElementById('provider_telefono').value = data.telefono;
            document.getElementById('provider_ubicacion').value = data.ubicacion;
        } else {
            form.action = baseUrl + 'agency/resources/store-provider';
            form.reset();
            document.getElementById('provider_id').value = '';
        }
        new bootstrap.Modal(document.getElementById('modalProvider')).show();
    }
</script>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
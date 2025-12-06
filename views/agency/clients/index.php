<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary fw-bold mb-0">Cartera de Clientes</h2>
            <p class="text-muted mb-0">Gestiona los datos de tus viajeros y su historial.</p>
        </div>
        <a href="<?php echo BASE_URL; ?>agency/clients/create" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-person-plus me-2"></i>Nuevo Cliente
        </a>
    </div>

    <!-- Buscador -->
    <div class="card glass-card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="<?php echo BASE_URL; ?>agency/clients" method="GET" class="row g-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i
                                class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0"
                            placeholder="Buscar por nombre, DNI o email..."
                            value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>
                </div>
                <!-- Filtros futuros aquí -->
            </form>
        </div>
    </div>

    <div class="glass-card border-0 shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Cliente</th>
                            <th>Contacto</th>
                            <th>Documento</th>
                            <th>Nacionalidad</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($clients)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="mb-3"><i class="bi bi-people fs-1 text-secondary opacity-50"></i></div>
                                    <h5 class="fw-normal">No se encontraron clientes</h5>
                                    <p class="small">Comienza agregando uno nuevo.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($clients as $client): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-initial rounded-circle bg-primary bg-opacity-10 text-primary me-3 fw-bold d-flex justify-content-center align-items-center"
                                                style="width: 40px; height: 40px;">
                                                <?php echo substr($client['nombre'], 0, 1) . substr($client['apellido'], 0, 1); ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">
                                                    <?php echo htmlspecialchars($client['nombre'] . ' ' . $client['apellido']); ?>
                                                </div>
                                                <span class="text-muted small">Registrado:
                                                    <?php echo date('d/m/Y', strtotime($client['fecha_registro'])); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark small mb-1"><i
                                                    class="bi bi-envelope me-1 text-muted"></i><?php echo htmlspecialchars($client['email']); ?></span>
                                            <span class="text-muted small"><i
                                                    class="bi bi-telephone me-1"></i><?php echo htmlspecialchars($client['telefono']); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="badge bg-light text-dark border">DNI:
                                            <?php echo htmlspecialchars($client['dni']); ?></div>
                                        <?php if ($client['ruc']): ?>
                                            <div class="badge bg-light text-secondary border mt-1">RUC:
                                                <?php echo htmlspecialchars($client['ruc']); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($client['nacionalidad']); ?></td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="<?php echo BASE_URL; ?>agency/clients/edit?id=<?php echo $client['id']; ?>"
                                                class="btn btn-sm btn-light text-primary rounded-circle me-2 shadow-sm"
                                                title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <!-- Eliminación protegida -->
                                            <a href="<?php echo BASE_URL; ?>agency/clients/delete?id=<?php echo $client['id']; ?>"
                                                class="btn btn-sm btn-light text-danger rounded-circle shadow-sm"
                                                onclick="return confirm('¿Eliminar este cliente? Si tiene reservas no se podrá borrar.');"
                                                title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
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

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
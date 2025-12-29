<?php
// Sistema_New/views/admin/support/index.php
include BASE_PATH . '/views/layouts/header.php';
?>

<div class="row mb-4 animate-fade-in">
    <div class="col-md-6">
        <h2 class="mb-0">🆘 Centro de Soporte</h2>
        <p class="text-muted">Gestión de tickets y ayuda</p>
    </div>
    <div class="col-md-6 text-end">
        <!-- En futuro: Modal para abrir ticket manual -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTicketModal">
            <i class="fas fa-plus me-2"></i>Nuevo Ticket
        </button>
    </div>
</div>

<div class="row animate-fade-in-up">
    <!-- FAQs Panel -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>FAQs (Base de Conocimiento)</h5>
            </div>
            <div class="list-group list-group-flush">
                <?php foreach ($faqs as $faq): ?>
                    <a href="#"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold"><?= htmlspecialchars($faq['pregunta']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($faq['categoria']) ?></small>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="card-footer text-center">
                <button class="btn btn-sm btn-outline-secondary">Gestor de FAQs</button>
            </div>
        </div>
    </div>

    <!-- Tickets List -->
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Tickets Recientes</h5>
                <select class="form-select form-select-sm w-auto"
                    onchange="location.href='<?= BASE_URL ?>admin/support?status='+this.value">
                    <option value="">Todos</option>
                    <option value="abierto" <?= $filter === 'abierto' ? 'selected' : '' ?>>Abiertos</option>
                    <option value="resuelto" <?= $filter === 'resuelto' ? 'selected' : '' ?>>Resueltos</option>
                </select>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Agencia</th>
                            <th>Asunto</th>
                            <th>Prioridad</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tickets)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No hay tickets registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($tickets as $ticket): ?>
                                <tr onclick="location.href='<?= BASE_URL ?>admin/support/show?id=<?= $ticket['id'] ?>'"
                                    style="cursor:pointer">
                                    <td>#<?= $ticket['id'] ?></td>
                                    <td><?= htmlspecialchars($ticket['agencia']) ?></td>
                                    <td><?= htmlspecialchars($ticket['asunto']) ?></td>
                                    <td>
                                        <?php
                                        $prioClass = match ($ticket['prioridad']) {
                                            'alta', 'critica' => 'danger',
                                            'media' => 'warning',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <span class="badge bg-<?= $prioClass ?>"><?= ucfirst($ticket['prioridad']) ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = match ($ticket['estado']) {
                                            'abierto' => 'success',
                                            'cerrado' => 'secondary',
                                            'resuelto' => 'primary',
                                            default => 'info'
                                        };
                                        ?>
                                        <span
                                            class="badge bg-<?= $statusClass ?>"><?= ucfirst(str_replace('_', ' ', $ticket['estado'])) ?></span>
                                    </td>
                                    <td><i class="fas fa-chevron-right text-muted"></i></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create Ticket -->
<div class="modal fade" id="newTicketModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= BASE_URL ?>admin/support/create" method="POST" class="modal-content">
            <?php echo csrf_field(); ?>
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Asunto</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>
                <!-- Agencia Selector (Hidden for Demo, defaulting to 1 or logic in controller) -->
                <!-- En producción aqui iría un select de agencias si es Superadmin -->

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Prioridad</label>
                        <select name="priority" class="form-select">
                            <option value="baja">Baja</option>
                            <option value="media" selected>Media</option>
                            <option value="alta">Alta</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Categoría</label>
                        <select name="category" class="form-select">
                            <option value="tecnico">Técnico</option>
                            <option value="facturacion">Facturación</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mensaje Inicial</label>
                    <textarea name="message" class="form-control" rows="4" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear Ticket</button>
            </div>
        </form>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
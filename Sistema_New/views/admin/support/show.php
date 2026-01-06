<?php
// Sistema_New/views/admin/support/show.php
include BASE_PATH . '/views/layouts/header.php';
?>

<div class="mb-3 animate-fade-in">
    <a href="<?= BASE_URL ?>admin/support" class="text-decoration-none text-muted">
        <i class="fas fa-arrow-left me-2"></i>Volver a Soporte
    </a>
</div>

<div class="row animate-fade-in-up">
    <!-- Ticket Info -->
    <div class="col-md-4 mb-4 order-md-2">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h5 class="mb-0">Detalles del Ticket #<?= $ticket['id'] ?></h5>
            </div>
            <div class="card-body">
                <h6 class="fw-bold"><?= htmlspecialchars($ticket['asunto']) ?></h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="badge bg-secondary"><?= htmlspecialchars($ticket['categoria']) ?></span>
                    <span
                        class="badge bg-<?= $ticket['prioridad'] == 'alta' ? 'danger' : 'warning' ?>"><?= htmlspecialchars($ticket['prioridad']) ?></span>
                </div>
                <hr>
                <p class="mb-1"><small class="text-muted">Agencia:</small> <br>
                    <strong><?= htmlspecialchars($ticket['agencia']) ?></strong>
                </p>
                <p class="mb-1"><small class="text-muted">Estado:</small> <br>
                    <strong><?= strtoupper(str_replace('_', ' ', $ticket['estado'])) ?></strong>
                </p>
                <p class="mb-1"><small class="text-muted">Creado:</small> <br>
                    <?= date('d/m/Y H:i', strtotime($ticket['created_at'])) ?></p>

                <hr>
                <form action="<?= BASE_URL ?>admin/support/updateStatus" method="POST" class="mb-3">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                    <label class="form-label small text-muted">Gestión de Estado:</label>
                    <div class="input-group">
                        <select name="status" class="form-select form-select-sm">
                            <option value="abierto" <?= $ticket['estado'] == 'abierto' ? 'selected' : '' ?>>Abierto
                            </option>
                            <option value="esperando_cliente" <?= $ticket['estado'] == 'esperando_cliente' ? 'selected' : '' ?>>Esperando Cliente</option>
                            <option value="resuelto" <?= $ticket['estado'] == 'resuelto' ? 'selected' : '' ?>>Resuelto
                            </option>
                            <option value="cerrado" <?= $ticket['estado'] == 'cerrado' ? 'selected' : '' ?>>Cerrado
                            </option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="col-md-8 mb-4 order-md-1">
        <div class="card h-100 d-flex flex-column">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Conversación</h5>
            </div>

            <!-- Messages List -->
            <div class="card-body flex-grow-1 overflow-auto"
                style="min-height: 400px; max-height: 600px; background-color: #f8f9fa;">
                <?php foreach ($messages as $msg): ?>
                    <?php
                    $isMe = ($msg['usuario_id'] == $_SESSION['user_id']);
                    $align = $isMe ? 'end' : 'start';
                    $bg = $isMe ? 'bg-primary text-white' : 'bg-white border';
                    ?>
                    <div class="d-flex justify-content-<?= $align ?> mb-3">
                        <div class="card <?= $bg ?>" style="max-width: 75%;">
                            <div class="card-body p-3">
                                <div class="small fw-bold mb-1 opacity-75">
                                    <?= htmlspecialchars($msg['sender_name']) ?> <span
                                        class="fw-normal">(<?= date('H:i d/m', strtotime($msg['created_at'])) ?>)</span>
                                </div>
                                <div><?= nl2br(htmlspecialchars($msg['mensaje'])) ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Reply Form -->
            <div class="card-footer bg-white">
                <?php if ($ticket['estado'] !== 'cerrado'): ?>
                    <form action="<?= BASE_URL ?>admin/support/reply" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                        <div class="mb-3">
                            <textarea name="message" class="form-control mb-2" placeholder="Escribe tu respuesta..."
                                rows="3" required></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <label class="small text-muted me-2 mb-0">Cambiar estado a:</label>
                                    <select name="status" class="form-select form-select-sm" style="width: auto;">
                                        <option value="esperando_cliente" selected>Esperando Cliente</option>
                                        <option value="resuelto">Resuelto</option>
                                        <option value="cerrado">Cerrado</option>
                                        <option value="abierto">Permanecer Abierto</option>
                                    </select>
                                </div>
                                <button class="btn btn-primary px-4" type="submit">
                                    <i class="fas fa-paper-plane me-2"></i>Enviar Respuesta
                                </button>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-secondary mb-0 text-center">
                        Este ticket ha sido cerrado. No se permiten más respuestas.
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
<?php
// Sistema_New/views/admin/support/faqs/index.php
include BASE_PATH . '/views/layouts/header.php';
?>

<div class="row mb-4 animate-fade-in">
    <div class="col-md-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/support">Soporte</a></li>
                <li class="breadcrumb-item active">Gestor de FAQs</li>
            </ol>
        </nav>
        <h2 class="mb-0">📚 Gestor de FAQs</h2>
        <p class="text-muted">Administra la base de conocimientos</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= BASE_URL ?>admin/support/faqs/create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nueva FAQ
        </a>
    </div>
</div>

<div class="card animate-fade-in-up">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Orden</th>
                    <th>Pregunta</th>
                    <th>Categoría</th>
                    <th>Visible</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($faqs)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No hay FAQs registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($faqs as $faq): ?>
                        <tr>
                            <td>
                                <?= $faq['orden'] ?>
                            </td>
                            <td class="fw-bold">
                                <?= htmlspecialchars($faq['pregunta']) ?>
                            </td>
                            <td><span class="badge bg-info text-dark">
                                    <?= htmlspecialchars($faq['categoria']) ?>
                                </span></td>
                            <td>
                                <?php if ($faq['visible']): ?>
                                    <span class="badge bg-success">Sí</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">No</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= BASE_URL ?>admin/support/faqs/edit?id=<?= $faq['id'] ?>"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/support/faqs/delete?id=<?= $faq['id'] ?>"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('¿Está seguro de eliminar esta FAQ?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
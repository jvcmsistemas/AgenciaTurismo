<?php
// Sistema_New/views/admin/support/faqs/create.php
include BASE_PATH . '/views/layouts/header.php';
?>

<div class="row mb-4 animate-fade-in">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/support">Soporte</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/support/faqs">Gestor de FAQs</a></li>
                <li class="breadcrumb-item active">Nueva FAQ</li>
            </ol>
        </nav>
        <h2 class="mb-0">🆕 Nueva FAQ</h2>
    </div>
</div>

<div class="row justify-content-center animate-fade-in-up">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="<?= BASE_URL ?>admin/support/faqs/store" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label">Pregunta</label>
                        <input type="text" name="pregunta" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Respuesta</label>
                        <textarea name="respuesta" class="form-control" rows="6" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Categoría</label>
                            <input type="text" name="categoria" class="form-control"
                                placeholder="General, Facturación, etc." required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Orden de Visualización</label>
                            <input type="number" name="orden" class="form-control" value="0">
                        </div>
                    </div>

                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="visible" id="visibleCheck" checked>
                        <label class="form-check-label" for="visibleCheck">Visible en el Centro de Ayuda</label>
                    </div>

                    <hr>
                    <div class="text-end">
                        <a href="<?= BASE_URL ?>admin/support/faqs" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">Guardar FAQ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
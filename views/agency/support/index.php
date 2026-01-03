<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="row mb-5 fade-in">
    <div class="col-12 text-center py-4">
        <h1 class="fw-bold text-primary display-5 mb-3">
            <i class="bi bi-stars me-2 text-warning"></i>Centro de Ayuda
        </h1>
        <p class="lead text-muted mx-auto" style="max-width: 700px;">
            ¡Hola! Estamos aquí para ayudarte a sacar el máximo provecho de tu plataforma. Encuentra respuestas rápidas
            o contacta con nuestro equipo.
        </p>

        <!-- Buscador Predictivo Premium -->
        <div class="mt-4 mx-auto" style="max-width: 600px;">
            <div class="input-group input-group-lg shadow-sm glass-card border-0">
                <span class="input-group-text bg-transparent border-0 ps-4">
                    <i class="bi bi-search text-primary"></i>
                </span>
                <input type="text" id="faqSearch" class="form-control bg-transparent border-0 py-4"
                    placeholder="¿Cómo puedo crear una reserva? ¿Problemas con pagos?">
            </div>
        </div>
    </div>
</div>

<!-- Ruta del Éxito: Guía Visual para Empleados -->
<div class="row mb-5 fade-in">
    <div class="col-12">
        <div class="card glass-card border-0 shadow-lg overflow-hidden">
            <div class="card-body p-0">
                <div class="row g-0">
                    <div
                        class="col-lg-3 d-none d-lg-flex bg-primary align-items-center justify-content-center p-4 text-center text-white">
                        <div>
                            <i class="bi bi-rocket-takeoff display-4 mb-3"></i>
                            <h3 class="fw-bold">Ruta del Éxito</h3>
                            <p class="small mb-0 opacity-75">Sigue estos pasos para dominar la plataforma en minutos.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-9 p-4 bg-surface-dynamic">
                        <div class="row g-4">
                            <!-- Paso 1 -->
                            <div class="col-md-3">
                                <div
                                    class="text-center h-100 p-3 rounded-4 hover-up transition-base border border-dynamic border-opacity-10">
                                    <img src="<?php echo BASE_URL; ?>public/img/help/recursos.png"
                                        class="img-fluid mb-3 rounded-3" alt="Recursos">
                                    <h6 class="fw-bold text-primary mb-2">1. Recursos</h6>
                                    <p class="small text-muted mb-3">Registra tus guías, vehículos y proveedores
                                        aliados.</p>
                                    <a href="<?php echo BASE_URL; ?>agency/resources"
                                        class="btn btn-sm btn-outline-primary rounded-pill w-100">Configurar</a>
                                </div>
                            </div>
                            <!-- Paso 2 -->
                            <div class="col-md-3">
                                <div
                                    class="text-center h-100 p-3 rounded-4 hover-up transition-base border border-dynamic border-opacity-10">
                                    <img src="<?php echo BASE_URL; ?>public/img/help/catalogo.png"
                                        class="img-fluid mb-3 rounded-3" alt="Catálogo">
                                    <h6 class="fw-bold text-primary mb-2">2. Catálogo</h6>
                                    <p class="small text-muted mb-3">Diseña tus tours con itinerarios y precios base.
                                    </p>
                                    <a href="<?php echo BASE_URL; ?>agency/tours"
                                        class="btn btn-sm btn-outline-primary rounded-pill w-100">Crear Menu</a>
                                </div>
                            </div>
                            <!-- Paso 3 -->
                            <div class="col-md-3">
                                <div
                                    class="text-center h-100 p-3 rounded-4 hover-up transition-base border border-dynamic border-opacity-10">
                                    <img src="<?php echo BASE_URL; ?>public/img/help/salidas.png"
                                        class="img-fluid mb-3 rounded-3" alt="Salidas">
                                    <h6 class="fw-bold text-primary mb-2">3. Salidas</h6>
                                    <p class="small text-muted mb-3">Programa fechas y asigna recursos a tus tours.</p>
                                    <a href="<?php echo BASE_URL; ?>agency/departures"
                                        class="btn btn-sm btn-outline-primary rounded-pill w-100">Programar</a>
                                </div>
                            </div>
                            <!-- Paso 4 -->
                            <div class="col-md-3">
                                <div
                                    class="text-center h-100 p-3 rounded-4 hover-up transition-base border border-dynamic border-opacity-10">
                                    <img src="<?php echo BASE_URL; ?>public/img/help/ventas.png"
                                        class="img-fluid mb-3 rounded-3" alt="Reservas">
                                    <h6 class="fw-bold text-primary mb-2">4. Ventas</h6>
                                    <p class="small text-muted mb-3">¡Listo! Empieza a registrar reservas y cobros.</p>
                                    <a href="<?php echo BASE_URL; ?>agency/reservations/create"
                                        class="btn btn-sm btn-primary rounded-pill w-100 shadow-sm">Nueva Venta</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Columna Izquierda: FAQs por Categorías -->
    <div class="col-lg-8">
        <div class="d-flex align-items-center mb-4 px-2">
            <h3 class="fw-bold text-dark mb-0"><i class="bi bi-grid-fill me-2 text-primary"></i>Temas Populares</h3>
            <div class="ms-auto d-none d-sm-block">
                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 active filter-btn"
                    data-category="all">Todos</button>
                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 filter-btn"
                    data-category="reservas">Reservas</button>
                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 filter-btn"
                    data-category="pagos">Pagos</button>
                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 filter-btn"
                    data-category="cuenta">Cuenta</button>
            </div>
        </div>

        <div class="accordion accordion-flush glass-card rounded-4 shadow-sm overflow-hidden" id="faqAccordion">
            <?php if (empty($faqs)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-emoji-smile text-muted display-4"></i>
                    <p class="mt-3 text-muted">Aún no hay preguntas frecuentes registradas.</p>
                </div>
            <?php else: ?>
                <?php foreach ($faqs as $index => $faq): ?>
                    <div class="accordion-item bg-transparent border-dynamic border-opacity-10 faq-item"
                        data-category="<?php echo strtolower($faq['categoria'] ?? 'general'); ?>">
                        <h2 class="accordion-header" id="heading<?php echo $faq['id']; ?>">
                            <button
                                class="accordion-button collapsed bg-transparent py-4 px-4 fw-bold text-dark transition-base shadow-none"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $faq['id']; ?>">
                                <div class="d-flex align-items-center w-100 pe-3">
                                    <div class="btn btn-sm btn-light-primary rounded-3 me-3 flex-shrink-0">
                                        <i class="bi <?php echo getCategoryIcon($faq['categoria']); ?> fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1 faq-question">
                                        <?php echo htmlspecialchars($faq['pregunta']); ?>
                                    </div>
                                    <span
                                        class="badge bg-light text-primary rounded-pill text-uppercase ms-2 d-none d-sm-inline-block small"
                                        style="font-size: 0.6rem;">
                                        <?php echo htmlspecialchars($faq['categoria']); ?>
                                    </span>
                                </div>
                            </button>
                        </h2>
                        <div id="collapse<?php echo $faq['id']; ?>" class="accordion-collapse collapse"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body p-4 bg-light-soft text-muted leading-relaxed">
                                <?php
                                // Renderizar HTML si detectamos código, de lo contrario nl2br regular
                                if (strpos($faq['respuesta'], '<') !== false) {
                                    echo $faq['respuesta'];
                                } else {
                                    echo nl2br(htmlspecialchars($faq['respuesta']));
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Columna Derecha: Soporte Directo -->
    <div class="col-lg-4">
        <div class="card glass-card border-0 shadow-lg sticky-top" style="top: 100px; z-index: 10;">
            <div class="card-header glass-header bg-primary text-white p-4 border-0 rounded-top-4">
                <h4 class="fw-bold mb-1"><i class="bi bi-chat-dots-fill me-2"></i>Soporte Directo</h4>
                <p class="small mb-0 opacity-75">¿No encuentras lo que buscas? Envíanos un ticket.</p>
            </div>
            <div class="card-body p-4">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success border-0 glass-card text-success mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i> ¡Ticket enviado! Nuestro equipo te responderá pronto.
                    </div>
                <?php endif; ?>

                <form action="<?php echo BASE_URL; ?>agency/support/store" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">ASUNTO</label>
                        <input type="text" name="subject" class="form-control bg-light border-0 py-2"
                            placeholder="Ej: Problema al cargar reserva" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">CATEGORÍA</label>
                        <select name="category" class="form-select bg-light border-0 py-2">
                            <option value="tecnico">Problema Técnico</option>
                            <option value="pagos">Pagos y Facturación</option>
                            <option value="cuenta">Configuración de Cuenta</option>
                            <option value="sugerencia">Sugerencia de Mejora</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">DETALLES</label>
                        <textarea name="message" class="form-control bg-light border-0" rows="4"
                            placeholder="Explícanos brevemente qué sucede..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 shadow-sm fw-bold">
                        Crear Ticket de Ayuda <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </form>

                <hr class="my-4 opacity-50">

                <div class="d-grid gap-2">
                    <h6 class="fw-bold text-muted small mb-3">TUS TICKETS RECIENTES</h6>
                    <?php if (empty($tickets)): ?>
                        <p class="text-center text-muted small py-3">No has enviado tickets aún.</p>
                    <?php else: ?>
                        <?php foreach (array_slice($tickets, 0, 3) as $ticket): ?>
                            <div class="d-flex align-items-center p-2 rounded-3 bg-light-soft transition-base">
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="fw-bold text-dark text-truncate small">
                                        <?php echo htmlspecialchars($ticket['asunto']); ?>
                                    </div>
                                    <div class="text-muted" style="font-size: 0.7rem;">
                                        <?php echo date('d M, Y', strtotime($ticket['created_at'])); ?>
                                    </div>
                                </div>
                                <span
                                    class="badge <?php echo getTicketStatusColor($ticket['estado']); ?> py-1 px-2 rounded-pill ms-2"
                                    style="font-size: 0.6rem;">
                                    <?php echo ucfirst($ticket['estado']); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                        <?php if (count($tickets) > 3): ?>
                            <a href="#" class="text-center text-primary fw-bold small mt-2 opacity-75">Ver todo el historial</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-up:hover {
        transform: translateY(-8px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1) !important;
    }

    .transition-base {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-light-primary {
        background: rgba(var(--bs-primary-rgb), 0.1);
        color: var(--bs-primary);
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .bg-light-soft {
        background: rgba(0, 0, 0, 0.03);
    }

    .bg-light-soft:hover {
        background: rgba(0, 0, 0, 0.05);
    }

    #faqSearch:focus {
        box-shadow: none;
        outline: none;
    }

    /* Accordion Enhancements */
    .accordion-button:not(.collapsed) {
        background-color: rgba(var(--bs-primary-rgb), 0.03);
        color: var(--bs-primary);
    }

    .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23666'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    .rich-content ol,
    .rich-content ul {
        padding-left: 1.25rem;
    }

    .rich-content li {
        margin-bottom: 0.5rem;
    }

    .bg-soft-success {
        background: rgba(25, 135, 84, 0.1);
    }

    .bg-soft-warning {
        background: rgba(255, 193, 7, 0.1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('faqSearch');
        const faqItems = document.querySelectorAll('.faq-item');
        const filterBtns = document.querySelectorAll('.filter-btn');

        // Buscador
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question').innerText.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                if (question.includes(query)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Filtros de categoría
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const cat = this.getAttribute('data-category');
                faqItems.forEach(item => {
                    if (cat === 'all' || item.getAttribute('data-category') === cat) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
</script>

<?php
function getCategoryIcon($cat)
{
    $cat = strtolower($cat);
    switch ($cat) {
        case 'reservas':
            return 'bi-calendar-check';
        case 'pagos':
            return 'bi-credit-card';
        case 'cuenta':
            return 'bi-person-circle';
        default:
            return 'bi-info-circle';
    }
}

function getTicketStatusColor($status)
{
    switch ($status) {
        case 'abierto':
            return 'bg-warning text-dark';
        case 'esperando_cliente':
            return 'bg-info text-dark';
        case 'en_progreso':
            return 'bg-primary';
        case 'cerrado':
            return 'bg-success';
        default:
            return 'bg-secondary';
    }
}
?>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
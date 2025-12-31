<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="dashboard-agency container-fluid py-4 fade-in">
    <!-- Header/Welcome -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-8">
            <h6 class="text-uppercase tracking-wider text-muted mb-2 font-weight-bold">Panel de Control</h6>
            <h1 class="fw-800 text-gradient-emerald mb-1">¡Hola, <?php echo explode(' ', $_SESSION['user_name'])[0]; ?>!
                👋</h1>
            <p class="text-secondary opacity-75">Este es el pulso actual de tu agencia para hoy.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="glass-badge py-2 px-3 d-inline-block">
                <i class="bi bi-calendar3 me-2 text-brand"></i> <span
                    class="fw-bold"><?php echo date('d M, Y'); ?></span>
            </div>
        </div>
    </div>

    <!-- Panel de Alertas Críticas (Solo si hay alertas) -->
    <?php if (!empty($upcomingDepartures) || !empty($pendingPaymentAlerts)): ?>
        <div class="row mb-5 slide-up">
            <div class="col-12">
                <div class="premium-alert-card overflow-hidden">
                    <div class="card-header-premium d-flex align-items-center px-4 py-3">
                        <div class="pulse-red me-3"></div>
                        <h5 class="mb-0 fw-bold text-white">Alertas que requieren tu atención</h5>
                    </div>
                    <div class="card-body p-4 bg-glass-surface">
                        <div class="row g-4">
                            <!-- Alertas de Salidas -->
                            <div class="col-md-6 border-end-premium">
                                <h6 class="fw-bold mb-3 d-flex align-items-center text-brand">
                                    <i class="bi bi-bus-front me-2 p-2 rounded-circle bg-emerald-soft"></i> Próximas Salidas
                                    (48h)
                                </h6>
                                <?php if (empty($upcomingDepartures)): ?>
                                    <div class="text-center py-3 opacity-50">
                                        <p class="small mb-0 italic">No hay salidas programadas para las próximas horas.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="list-group list-group-flush gap-2">
                                        <?php foreach ($upcomingDepartures as $dep): ?>
                                            <div
                                                class="alert-list-item d-flex justify-content-between align-items-center p-3 rounded-3">
                                                <div>
                                                    <div class="fw-bold mb-1"><?php echo htmlspecialchars($dep['tour_nombre']); ?>
                                                    </div>
                                                    <div class="small opacity-75">
                                                        <i class="bi bi-clock me-1"></i>
                                                        <?php echo date('d/m H:i', strtotime($dep['fecha_salida'] . ' ' . $dep['hora_salida'])); ?>
                                                        <span class="mx-2">|</span>
                                                        <i class="bi bi-people me-1"></i> <?php echo $dep['cupos_disponibles']; ?>
                                                        libres
                                                    </div>
                                                </div>
                                                <a href="<?php echo BASE_URL; ?>agency/departures/show/<?php echo $dep['id']; ?>"
                                                    class="btn-action-glass">Gestionar</a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!-- Alertas de Pagos -->
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3 d-flex align-items-center text-warning">
                                    <i class="bi bi-exclamation-triangle me-2 p-2 rounded-circle bg-warning-soft"></i> Pagos
                                    Pendientes Críticos
                                </h6>
                                <?php if (empty($pendingPaymentAlerts)): ?>
                                    <div class="text-center py-3 opacity-50">
                                        <p class="small mb-0 italic">Todo al día. No hay cobros urgentes pendientes.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="list-group list-group-flush gap-2">
                                        <?php foreach ($pendingPaymentAlerts as $alert): ?>
                                            <div
                                                class="alert-list-item d-flex justify-content-between align-items-center p-3 rounded-3">
                                                <div>
                                                    <div class="fw-bold mb-1">
                                                        <?php echo htmlspecialchars($alert['cliente_nombre'] . ' ' . $alert['cliente_apellido']); ?>
                                                    </div>
                                                    <div class="small text-danger fw-bold">
                                                        S/ <?php echo number_format($alert['saldo_pendiente'], 2); ?>
                                                        <span class="mx-2 text-muted fw-normal">|</span>
                                                        Inicia: <?php echo date('d/m', strtotime($alert['fecha_inicio_tour'])); ?>
                                                    </div>
                                                </div>
                                                <a href="<?php echo BASE_URL; ?>agency/reservations/show/<?php echo $alert['id']; ?>"
                                                    class="btn-action-glass warning">Cobrar</a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- KPI Cards Grid -->
    <div class="row mb-5 g-4 slide-up-delayed">
        <div class="col-md-4">
            <div class="kpi-premium-card emerald">
                <div class="kpi-content">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <span class="kpi-label">Ingresos Totales</span>
                            <h2 class="kpi-value">S/ <?php echo number_format($totalRevenue, 2); ?></h2>
                        </div>
                        <div class="kpi-icon-wrapper"><i class="bi bi-wallet2"></i></div>
                    </div>
                    <div class="kpi-footer d-flex align-items-center mt-auto">
                        <span class="trend-badge positive"><i class="bi bi-graph-up-arrow me-1"></i> +12%</span>
                        <span class="ms-2 opacity-50 small">Vs mes anterior</span>
                    </div>
                </div>
                <div class="kpi-decoration"></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-premium-card blue">
                <div class="kpi-content">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <span class="kpi-label">Reservas Totales</span>
                            <h2 class="kpi-value"><?php echo $totalReservations; ?></h2>
                        </div>
                        <div class="kpi-icon-wrapper"><i class="bi bi-calendar-check"></i></div>
                    </div>
                    <div class="kpi-footer d-flex align-items-center mt-auto">
                        <span class="trend-badge neutralize"><i class="bi bi-people me-1"></i> Activas</span>
                        <span class="ms-2 opacity-50 small">Total histórico</span>
                    </div>
                </div>
                <div class="kpi-decoration"></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-premium-card indigo">
                <div class="kpi-content">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <span class="kpi-label">Tours Disponibles</span>
                            <h2 class="kpi-value"><?php echo $totalTours; ?></h2>
                        </div>
                        <div class="kpi-icon-wrapper"><i class="bi bi-map"></i></div>
                    </div>
                    <div class="kpi-footer d-flex align-items-center mt-auto">
                        <span class="trend-badge positive"><i class="bi bi-patch-check me-1"></i> Online</span>
                        <span class="ms-2 opacity-50 small">Publicados en web</span>
                    </div>
                </div>
                <div class="kpi-decoration"></div>
            </div>
        </div>
    </div>

    <div class="row slide-up-delayed-2">
        <!-- Recent Activity Table -->
        <div class="col-lg-8">
            <div class="glass-container h-100">
                <div class="d-flex justify-content-between align-items-center p-4 border-bottom border-glass">
                    <h5 class="fw-bold mb-0">Actividad Reciente</h5>
                    <a href="<?php echo BASE_URL; ?>agency/reservations" class="btn-text-link">Ver todo el historial <i
                            class="bi bi-chevron-right ms-1"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Cliente / Experiencia</th>
                                <th>Fecha Tour</th>
                                <th>Estado</th>
                                <th class="text-end pe-4">Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentReservations)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25 text-brand"></i>
                                            <p class="text-muted">No se detectó actividad en los últimos días.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentReservations as $res): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-ui me-3">
                                                    <?php echo strtoupper(substr($res['cliente_nombre'], 0, 1)); ?></div>
                                                <div>
                                                    <div class="fw-bold fs-14">
                                                        <?php echo htmlspecialchars($res['cliente_nombre'] . ' ' . $res['cliente_apellido']); ?>
                                                    </div>
                                                    <div class="text-brand fs-12">
                                                        <?php echo htmlspecialchars($res['tours_nombres']); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fs-13 fw-500 opacity-75">
                                                <?php echo $res['fecha_inicio_tour'] ? date('d M, Y', strtotime($res['fecha_inicio_tour'])) : 'Pendiente'; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = '';
                                            switch ($res['estado']) {
                                                case 'confirmada':
                                                    $statusClass = 'badge-success-premium';
                                                    break;
                                                case 'pendiente':
                                                    $statusClass = 'badge-warning-premium';
                                                    break;
                                                default:
                                                    $statusClass = 'badge-secondary-premium';
                                            }
                                            ?>
                                            <span
                                                class="badge-premium <?php echo $statusClass; ?>"><?php echo ucfirst($res['estado']); ?></span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <span class="fw-800 fs-15">S/
                                                <?php echo number_format($res['precio_total'], 2); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Control Center -->
        <div class="col-lg-4">
            <div class="glass-container h-100">
                <div class="p-4 border-bottom border-glass">
                    <h5 class="fw-bold mb-0">Centro de Acceso Rápido</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        <a href="<?php echo BASE_URL; ?>agency/reservations/create" class="action-card blue">
                            <div class="icon"><i class="bi bi-plus-circle-fill"></i></div>
                            <div class="text">
                                <span class="d-block fw-bold">Nueva Reserva</span>
                                <span class="small opacity-50">Registrar flujo de ventas</span>
                            </div>
                            <i class="bi bi-chevron-right ms-auto opacity-50 arrow"></i>
                        </a>
                        <a href="<?php echo BASE_URL; ?>agency/tours/create" class="action-card emerald">
                            <div class="icon"><i class="bi bi-map-fill"></i></div>
                            <div class="text">
                                <span class="d-block fw-bold">Crear Tour</span>
                                <span class="small opacity-50">Diseñar nueva experiencia</span>
                            </div>
                            <i class="bi bi-chevron-right ms-auto opacity-50 arrow"></i>
                        </a>
                        <a href="<?php echo BASE_URL; ?>agency/clients" class="action-card indigo">
                            <div class="icon"><i class="bi bi-people-fill"></i></div>
                            <div class="text">
                                <span class="d-block fw-bold">Gestión CRM</span>
                                <span class="small opacity-50">Cartera de clientes</span>
                            </div>
                            <i class="bi bi-chevron-right ms-auto opacity-50 arrow"></i>
                        </a>
                    </div>

                    <!-- OS Status Promo -->
                    <div class="system-status-box mt-5 p-4 rounded-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="live-pulse me-2"></div>
                            <span class="small fw-800 text-uppercase tracking-wider">Estado del Sistema</span>
                        </div>
                        <p class="small opacity-75 mb-0">Tu panel está sincronizado con la nube central. Todas las
                            transacciones son auditadas en tiempo real.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Design System Logic */
    .dashboard-agency {
        --kpi-emerald: linear-gradient(135deg, #064e3b 0%, #10b981 100%);
        --kpi-blue: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        --kpi-indigo: linear-gradient(135deg, #312e81 0%, #6366f1 100%);
        --text-brand: #10b981;
    }

    body.agency-light-theme .dashboard-agency {
        --text-brand: #059669;
        --kpi-emerald: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        --kpi-blue: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        --kpi-indigo: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
    }

    .text-brand {
        color: var(--text-brand);
    }

    .bg-emerald-soft {
        background: rgba(16, 185, 129, 0.1);
        color: var(--text-brand);
    }

    .bg-warning-soft {
        background: rgba(245, 158, 11, 0.1);
        color: #b45309;
    }

    .fw-800 {
        font-weight: 800;
    }

    .tracking-wider {
        letter-spacing: 0.1em;
    }

    .fs-12 {
        font-size: 0.75rem;
    }

    .fs-13 {
        font-size: 0.8125rem;
    }

    .fs-14 {
        font-size: 0.875rem;
    }

    .fs-15 {
        font-size: 0.9375rem;
    }

    .text-gradient-emerald {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .glass-badge {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 50px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Containers */
    .glass-container {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 24px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .border-glass {
        border-color: var(--border-color) !important;
    }

    /* Premium Alert Card */
    .premium-alert-card {
        border-radius: 20px;
        border: 1px solid rgba(239, 68, 68, 0.2);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .card-header-premium {
        background: linear-gradient(90deg, #b91c1c 0%, #ef4444 100%);
    }

    .alert-list-item {
        background: var(--bg-tertiary);
        border: 1px solid var(--border-color);
        transition: transform 0.2s, background 0.2s;
    }

    .alert-list-item:hover {
        transform: scale(1.01);
        background: var(--hover-bg);
    }

    .btn-action-glass {
        padding: 6px 16px;
        border-radius: 50px;
        border: 1px solid var(--brand-primary);
        color: var(--brand-primary);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.75rem;
        transition: all 0.2s;
    }

    .btn-action-glass.warning {
        border-color: #f59e0b;
        color: #f59e0b;
    }

    .btn-action-glass:hover {
        background: var(--brand-primary);
        color: white;
    }

    /* KPI Premium Cards */
    .kpi-premium-card {
        height: 180px;
        border-radius: 24px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .kpi-premium-card:hover {
        transform: translateY(-8px);
    }

    .kpi-premium-card.emerald {
        background: var(--kpi-emerald);
    }

    .kpi-premium-card.blue {
        background: var(--kpi-blue);
    }

    .kpi-premium-card.indigo {
        background: var(--kpi-indigo);
    }

    .kpi-premium-card .kpi-label {
        font-size: 0.875rem;
        font-weight: 600;
        opacity: 0.8;
        color: #fff;
    }

    .kpi-premium-card .kpi-value {
        font-size: 2.25rem;
        font-weight: 800;
        color: #fff;
        margin: 0;
    }

    body.agency-light-theme .kpi-premium-card .kpi-label {
        color: #064e3b;
    }

    body.agency-light-theme .kpi-premium-card .kpi-value {
        color: #064e3b;
    }

    .kpi-icon-wrapper {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(4px);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #fff;
    }

    body.agency-light-theme .kpi-icon-wrapper {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .trend-badge {
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    body.agency-light-theme .trend-badge {
        background: #d1fae5;
        color: #065f46;
    }

    /* Tables */
    .table-custom thead th {
        background: var(--bg-tertiary);
        padding: 16px;
        color: var(--text-secondary);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        border-top: none;
    }

    .avatar-ui {
        width: 40px;
        height: 40px;
        background: var(--brand-primary);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
    }

    /* Action Cards */
    .action-card {
        display: flex;
        align-items: center;
        padding: 16px;
        border-radius: 16px;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-color);
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .action-card:hover {
        transform: translateX(5px);
        border-color: var(--brand-primary);
        background: var(--hover-bg);
    }

    .action-card .icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 16px;
    }

    .action-card.blue .icon {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .action-card.emerald .icon {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .action-card.indigo .icon {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
    }

    .system-status-box {
        background: var(--bg-tertiary);
        border: 1px dashed var(--border-color);
    }

    .live-pulse {
        width: 10px;
        height: 10px;
        background: #10b981;
        border-radius: 50%;
        box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
        animation: pulse 2s infinite;
    }

    /* Animations */
    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }

    .slide-up {
        animation: slideUp 0.6s ease-out;
    }

    .slide-up-delayed {
        animation: slideUp 0.8s ease-out both;
        animation-delay: 0.2s;
    }

    .slide-up-delayed-2 {
        animation: slideUp 0.8s ease-out both;
        animation-delay: 0.4s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }
</style>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="dashboard-agency container-fluid py-4 fade-in">
    <!-- Header/Welcome -->
    <div class="row mb-4 align-items-end">
        <div class="col-md-8">
            <h6 class="text-uppercase tracking-wider text-primary mb-2 fw-bold" style="font-size: 0.75rem;">Command
                Center v3.0</h6>
            <h2 class="fw-800 text-dark mb-1">¡Buenos días, <?php echo explode(' ', $_SESSION['user_name'])[0]; ?>! 👋
            </h2>
            <p class="text-secondary opacity-75 mb-0">Resumen operativo para hoy, <?php echo date('d M'); ?>.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="d-flex justify-content-md-end gap-2">
                <div class="glass-badge py-2 px-3 shadow-sm">
                    <span class="live-pulse me-2"></span>
                    <span class="small fw-bold text-success">Sistema Online</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Widgets Row -->
    <div class="row mb-5 g-4 slide-up">
        <!-- Salidas Hoy/Mañana -->
        <div class="col-lg-4">
            <div class="glass-card h-100 border-0 shadow-sm overflow-hidden">
                <div
                    class="p-3 bg-primary bg-opacity-10 border-bottom border-primary border-opacity-10 d-flex align-items-center">
                    <i class="bi bi-rocket-takeoff-fill text-primary me-2"></i>
                    <h6 class="mb-0 fw-bold">Alarmas de Salida</h6>
                    <span class="badge bg-primary ms-auto rounded-pill"><?php echo count($upcomingDepartures); ?></span>
                </div>
                <div class="card-body p-0 scroll-y" style="max-height: 280px;">
                    <?php if (empty($upcomingDepartures)): ?>
                        <div class="text-center py-5 opacity-50">
                            <i class="bi bi-check2-circle display-6 d-block mb-2"></i>
                            <p class="small">Todo despejado</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($upcomingDepartures as $dep):
                            $isToday = date('Y-m-d', strtotime($dep['fecha_salida'])) === date('Y-m-d');
                            $priorityClass = $isToday ? 'border-start border-4 border-danger' : 'border-start border-4 border-primary';
                            ?>
                            <div
                                class="p-3 border-bottom border-dynamic border-opacity-10 transition-base hover-bg <?php echo $priorityClass; ?>">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold fs-14"><?php echo htmlspecialchars($dep['tour_nombre']); ?></span>
                                    <span class="badge <?php echo $isToday ? 'bg-danger' : 'bg-primary'; ?> small">
                                        <?php echo $isToday ? 'HOY' : 'MAÑANA'; ?>
                                    </span>
                                </div>
                                <div class="d-flex small text-muted align-items-center">
                                    <i class="bi bi-clock me-1"></i> <?php echo date('H:i', strtotime($dep['hora_salida'])); ?>
                                    <span class="mx-2">|</span>
                                    <i class="bi bi-people me-1"></i>
                                    <?php echo ($dep['cupos_totales'] - $dep['cupos_disponibles']); ?>/<?php echo $dep['cupos_totales']; ?>
                                    plazas
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="p-2 border-top border-dynamic border-opacity-10 text-center">
                    <a href="<?php echo BASE_URL; ?>agency/departures"
                        class="small text-primary text-decoration-none fw-bold">Ver calendario de salidas</a>
                </div>
            </div>
        </div>

        <!-- Cobros Pendientes Críticos -->
        <div class="col-lg-4">
            <div class="glass-card h-100 border-0 shadow-sm overflow-hidden">
                <div
                    class="p-3 bg-warning bg-opacity-10 border-bottom border-warning border-opacity-10 d-flex align-items-center">
                    <i class="bi bi-cash-stack text-warning me-2"></i>
                    <h6 class="mb-0 fw-bold">Cobros Urgentes</h6>
                    <span
                        class="badge bg-warning text-dark ms-auto rounded-pill"><?php echo count($pendingPaymentAlerts); ?></span>
                </div>
                <div class="card-body p-0 scroll-y" style="max-height: 280px;">
                    <?php if (empty($pendingPaymentAlerts)): ?>
                        <div class="text-center py-5 opacity-50">
                            <i class="bi bi-emoji-smile display-6 d-block mb-2"></i>
                            <p class="small">Todo pagado</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($pendingPaymentAlerts as $alert): ?>
                            <div
                                class="p-3 border-bottom border-dynamic border-opacity-10 transition-base hover-bg border-start border-4 border-warning">
                                <div class="d-flex justify-content-between mb-1">
                                    <span
                                        class="fw-bold fs-14"><?php echo htmlspecialchars($alert['cliente_nombre'] . ' ' . $alert['cliente_apellido']); ?></span>
                                    <span class="text-danger fw-bold fs-14">S/
                                        <?php echo number_format($alert['saldo_pendiente'], 2); ?></span>
                                </div>
                                <div class="d-flex small text-muted align-items-center">
                                    <i class="bi bi-calendar-event me-1 text-danger"></i> Inicia:
                                    <?php echo date('d/m', strtotime($alert['fecha_inicio_tour'])); ?>
                                    <span class="ms-auto"><a
                                            href="<?php echo BASE_URL; ?>agency/reservations/show?id=<?php echo $alert['id']; ?>"
                                            class="text-warning fw-bold">Gestionar</a></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="p-2 border-top border-dynamic border-opacity-10 text-center">
                    <a href="<?php echo BASE_URL; ?>agency/payments"
                        class="small text-warning text-decoration-none fw-bold">Ver historial de pagos</a>
                </div>
            </div>
        </div>

        <!-- Soporte y Notificaciones -->
        <div class="col-lg-4">
            <div class="glass-card h-100 border-0 shadow-sm overflow-hidden">
                <div
                    class="p-3 bg-info bg-opacity-10 border-bottom border-info border-opacity-10 d-flex align-items-center">
                    <i class="bi bi-megaphone-fill text-info me-2"></i>
                    <h6 class="mb-0 fw-bold">Soporte Activo</h6>
                    <span class="badge bg-info ms-auto rounded-pill"><?php echo $pendingRepliesCount; ?></span>
                </div>
                <div class="card-body p-4 text-center">
                    <?php if ($pendingRepliesCount > 0): ?>
                        <div class="py-2">
                            <div class="btn btn-info btn-sm rounded-pill mb-3 px-3 shadow-sm text-white">
                                <i class="bi bi-chat-dots-fill me-2"></i> <?php echo $pendingRepliesCount; ?> Respuestas
                                Nuevas
                            </div>
                            <p class="small text-muted mb-4 px-2">El equipo de administración ha respondido tus tickets
                                pendientes.</p>
                            <a href="<?php echo BASE_URL; ?>agency/support"
                                class="btn btn-outline-info btn-sm rounded-pill px-4">Ir al Centro de Ayuda</a>
                        </div>
                    <?php else: ?>
                        <div class="py-4 opacity-50">
                            <i class="bi bi-shield-check display-6 d-block mb-2"></i>
                            <p class="small">Sin tickets pendientes</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>agency/support"
                            class="btn btn-light btn-sm rounded-pill px-4 border-0">Solicitar Ayuda</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs Advanced Row -->
    <div class="row mb-5 g-4 slide-up-delayed">
        <!-- Monthly Revenue -->
        <div class="col-md-3">
            <div class="kpi-mini-card">
                <div class="icon bg-emerald-soft"><i class="bi bi-currency-dollar"></i></div>
                <div>
                    <span class="label">Ingresos del Mes</span>
                    <h4 class="value mb-0">S/ <?php echo number_format($monthlyRevenue, 2); ?></h4>
                    <span class="trend positive"><i class="bi bi-arrow-up"></i> Historico: S/
                        <?php echo number_format($totalRevenue, 0); ?></span>
                </div>
            </div>
        </div>
        <!-- Occupancy -->
        <div class="col-md-3">
            <div class="kpi-mini-card">
                <div class="icon bg-primary shadow-sm text-white"><i class="bi bi-percent"></i></div>
                <div>
                    <span class="label">Ocupación Promedio</span>
                    <h4 class="value mb-0"><?php echo $avgOccupancy; ?>%</h4>
                    <span class="trend neutral">Próximas 10 salidas</span>
                </div>
            </div>
        </div>
        <!-- New Clients -->
        <div class="col-md-3">
            <div class="kpi-mini-card">
                <div class="icon bg-blue-soft"><i class="bi bi-person-plus"></i></div>
                <div>
                    <span class="label">Nuevos Clientes</span>
                    <h4 class="value mb-0">+<?php echo $monthlyNewClients; ?></h4>
                    <span class="trend positive">Registrados este mes</span>
                </div>
            </div>
        </div>
        <!-- Support Status -->
        <div class="col-md-3">
            <div class="kpi-mini-card">
                <div class="icon bg-indigo-soft"><i class="bi bi-calendar-check"></i></div>
                <div>
                    <span class="label">Reservas Totales</span>
                    <h4 class="value mb-0"><?php echo $totalReservations; ?></h4>
                    <span class="trend neutral"><?php echo count($recentReservations); ?> esta semana</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity & Quick Actions -->
    <div class="row slide-up-delayed-2">
        <div class="col-lg-8">
            <div class="glass-container h-100">
                <div class="d-flex justify-content-between align-items-center p-4 border-bottom border-glass">
                    <h5 class="fw-bold mb-0">Últimas Operaciones</h5>
                    <a href="<?php echo BASE_URL; ?>agency/reservations" class="btn-text-link">Historial Completo <i
                            class="bi bi-chevron-right ms-1"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Cliente / Experiencia</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th class="text-end pe-4">Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentReservations)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <p class="text-muted mb-0">Sin actividad reciente</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentReservations as $res): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                                    style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                    <?php echo strtoupper(substr($res['cliente_nombre'], 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <div class="fw-bold fs-13">
                                                        <?php echo htmlspecialchars($res['cliente_nombre'] . ' ' . $res['cliente_apellido']); ?>
                                                    </div>
                                                    <div class="text-muted fs-11">
                                                        <?php echo htmlspecialchars(substr($res['tours_nombres'], 0, 30)); ?>...
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span
                                                class="fs-12 opacity-75"><?php echo date('d M', strtotime($res['fecha_hora_reserva'])); ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $badgeClass = 'bg-secondary';
                                            if ($res['estado'] == 'confirmada')
                                                $badgeClass = 'bg-success';
                                            if ($res['estado'] == 'pendiente')
                                                $badgeClass = 'bg-warning text-dark';
                                            ?>
                                            <span class="badge <?php echo $badgeClass; ?> small rounded-pill fw-normal px-3"
                                                style="font-size: 0.65rem;">
                                                <?php echo strtoupper($res['estado']); ?>
                                            </span>
                                        </td>
                                        <td class="text-end pe-4"><span class="fw-bold fs-14">S/
                                                <?php echo number_format($res['precio_total'], 2); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="glass-card h-100 p-4 shadow-sm border-0">
                <h6 class="fw-bold mb-4">Acceso Directo</h6>
                <div class="d-grid gap-2">
                    <a href="<?php echo BASE_URL; ?>agency/reservations/create"
                        class="btn btn-primary rounded-4 p-3 text-start d-flex align-items-center shadow-sm">
                        <div class="bg-white bg-opacity-20 rounded-3 p-2 me-3"><i class="bi bi-plus-lg fs-4"></i></div>
                        <div>
                            <div class="fw-bold">Nueva Venta</div>
                            <div class="small opacity-75">Registro rápido</div>
                        </div>
                    </a>
                    <a href="<?php echo BASE_URL; ?>agency/departures/create"
                        class="btn btn-light rounded-4 p-3 text-start d-flex align-items-center border">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-3"><i
                                class="bi bi-calendar-plus fs-4"></i></div>
                        <div>
                            <div class="fw-bold text-dark">Programar Salida</div>
                            <div class="small text-muted">Añadir calendario</div>
                        </div>
                    </a>
                    <a href="<?php echo BASE_URL; ?>agency/clients"
                        class="btn btn-light rounded-4 p-3 text-start d-flex align-items-center border">
                        <div class="bg-info bg-opacity-10 text-info rounded-3 p-2 me-3"><i
                                class="bi bi-people fs-4"></i></div>
                        <div>
                            <div class="fw-bold text-dark">Cartera de Clientes</div>
                            <div class="small text-muted">Gestión CRM</div>
                        </div>
                    </a>
                </div>

                <div class="mt-4 p-3 bg-light rounded-4 border">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-shield-check-fill text-success me-2"></i>
                        <span class="small fw-bold text-uppercase">Seguridad</span>
                    </div>
                    <p class="small text-muted mb-0" style="font-size: 0.7rem;">Todas tus operaciones están cifradas y
                        respaldadas automáticamente en nuestra nube segura.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-agency {
        background-color: #f8fafc;
        min-height: 100vh;
    }

    .fw-800 {
        font-weight: 800;
    }

    .fs-11 {
        font-size: 0.6875rem;
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

    .glass-card {
        background: white;
        border-radius: 20px;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .hover-bg:hover {
        background: rgba(0, 0, 0, 0.02);
    }

    .transition-base {
        transition: all 0.2s ease;
    }

    .kpi-mini-card {
        background: white;
        padding: 20px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid rgba(0, 0, 0, 0.04);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .kpi-mini-card .icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .kpi-mini-card .label {
        font-size: 0.7rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .kpi-mini-card .value {
        font-size: 1.25rem;
        font-weight: 800;
        color: #1e293b;
    }

    .kpi-mini-card .trend {
        font-size: 0.65rem;
        font-weight: 600;
    }

    .kpi-mini-card .trend.positive {
        color: #10b981;
    }

    .kpi-mini-card .trend.neutral {
        color: #64748b;
    }

    .scroll-y {
        overflow-y: auto;
    }

    .scroll-y::-webkit-scrollbar {
        width: 4px;
    }

    .scroll-y::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    .live-pulse {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
        }

        70% {
            box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    .slide-up {
        animation: slideUp 0.6s ease-out;
    }

    .slide-up-delayed {
        animation: slideUp 0.8s ease-out both;
        animation-delay: 0.1s;
    }

    .slide-up-delayed-2 {
        animation: slideUp 1s ease-out both;
        animation-delay: 0.2s;
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
</style>
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
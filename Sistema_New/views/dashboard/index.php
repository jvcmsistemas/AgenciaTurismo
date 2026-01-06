<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<div class="dashboard-agency container-fluid py-4 fade-in">
    <!-- Header/Welcome -->
    <div class="row mb-4 align-items-end">
        <div class="col-md-8">
            <h6 class="text-uppercase tracking-wider text-primary mb-2 fw-bold" style="font-size: 0.75rem;">Command
                Center v3.0</h6>
            <h2 class="fw-800 text-dark mb-1">¡Buenos días, <?php echo explode(' ', $_SESSION['user_name'])[0]; ?>! 👋
            </h2>
            <p class="text-secondary opacity-75 mb-0">Gestionando: <span
                    class="fw-bold text-primary"><?php echo htmlspecialchars($_SESSION['agencia_nombre'] ?? 'Mi Agencia'); ?></span>
                | Hoy, <?php echo date('d M'); ?>.</p>
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
        <div class="col-lg-12">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="glass-card h-100 border-0 shadow-sm overflow-hidden">
                        <div
                            class="p-3 bg-primary bg-opacity-10 border-bottom border-primary border-opacity-10 d-flex align-items-center">
                            <div class="icon-box-sm bg-primary text-white me-3 rounded-3 shadow-soft">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <h6 class="mb-0 fw-bold">Calendario de Salidas</h6>
                            <span
                                class="badge bg-primary ms-auto rounded-pill"><?php echo count($upcomingDepartures); ?></span>
                        </div>
                        <div class="card-body p-0 scroll-y" style="max-height: 280px;">
                            <?php if (empty($upcomingDepartures)): ?>
                                <div class="text-center py-5 opacity-50">
                                    <i class="bi bi-calendar-check display-6 d-block mb-2"></i>
                                    <p class="small">Todo despejado</p>
                                </div>
                            <?php else: ?>
                                <div class="departure-timeline p-3">
                                    <?php foreach ($upcomingDepartures as $index => $dep):
                                        $isToday = date('Y-m-d', strtotime($dep['fecha_salida'])) === date('Y-m-d');
                                        $isLast = ($index === count($upcomingDepartures) - 1);
                                        ?>
                                        <div class="timeline-item <?php echo $isLast ? 'last' : ''; ?>">
                                            <div class="timeline-date">
                                                <span
                                                    class="day"><?php echo date('d', strtotime($dep['fecha_salida'])); ?></span>
                                                <span
                                                    class="month"><?php echo date('M', strtotime($dep['fecha_salida'])); ?></span>
                                            </div>
                                            <div class="timeline-content <?php echo $isToday ? 'active' : ''; ?>">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="fw-bold fs-13 text-truncate"
                                                        style="max-width: 140px;"><?php echo htmlspecialchars($dep['tour_nombre']); ?></span>
                                                    <span
                                                        class="badge-dot <?php echo $isToday ? 'bg-danger' : 'bg-primary'; ?>"></span>
                                                </div>
                                                <div class="d-flex small text-muted align-items-center fs-11">
                                                    <i class="bi bi-clock me-1"></i>
                                                    <?php echo date('H:i', strtotime($dep['hora_salida'])); ?>
                                                    <span class="mx-1">|</span>
                                                    <i class="bi bi-people me-1"></i>
                                                    <?php echo ($dep['cupos_totales'] - $dep['cupos_disponibles']); ?>/<?php echo $dep['cupos_totales']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-2 border-top border-dynamic border-opacity-10 text-center">
                            <a href="<?php echo BASE_URL; ?>agency/departures"
                                class="small text-primary text-decoration-none fw-bold hover-underline">Abrir calendario
                                completo</a>
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
                                            <span class="text-danger fw-bold fs-14">
                                                <?php echo formatCurrency($alert['saldo_pendiente']); ?></span>
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

                <!-- Se oculta Ranking de Guías temporalmente -->
                <!--
                <div class="col-lg-4">
                    <div class="glass-card h-100 border-0 shadow-sm overflow-hidden">
                        <div
                            class="p-3 bg-warning bg-opacity-10 border-bottom border-warning border-opacity-10 d-flex align-items-center">
                            <div class="icon-box-sm bg-warning text-dark me-3 rounded-3 shadow-soft">
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <h6 class="mb-0 fw-bold">Guías Estrella</h6>
                        </div>
                        <div class="card-body p-4">
                            <?php if (empty($topGuidesRanking)): ?>
                                <div class="text-center py-4 opacity-50">
                                    <i class="bi bi-person-badge display-6 d-block mb-2"></i>
                                    <p class="small">Sin salidas este mes</p>
                                </div>
                            <?php else:
                                $maxSalidas = $topGuidesRanking[0]['total_salidas'] ?: 1;
                                foreach ($topGuidesRanking as $guide):
                                    $percentage = ($guide['total_salidas'] / $maxSalidas) * 100;
                                    ?>
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-bold fs-13 text-truncate"
                                                style="max-width: 180px;"><?php echo htmlspecialchars($guide['nombre'] . ' ' . (isset($guide['apellido']) ? mb_substr($guide['apellido'], 0, 1) . '.' : '')); ?></span>
                                            <span class="badge bg-warning bg-opacity-20 text-dark rounded-pill px-2 py-1 fs-11">
                                                <?php echo $guide['total_salidas']; ?> salidas
                                            </span>
                                        </div>
                                        <div class="progress"
                                            style="height: 6px; border-radius: 10px; background-color: var(--border-color);">
                                            <div class="progress-bar bg-warning rounded-pill transition-base" role="progressbar"
                                                style="width: <?php echo $percentage; ?>%;"
                                                aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="p-2 border-top border-dynamic border-opacity-10 text-center">
                            <a href="<?php echo BASE_URL; ?>agency/guides"
                                class="small text-success text-decoration-none fw-bold hover-underline">Ver staff
                                completo</a>
                        </div>
                    </div>
                </div>
                -->

                <!-- Ranking de Experiencias (Tours Populares) -->
                <div class="col-lg-4">
                    <div class="glass-card h-100 border-0 shadow-sm overflow-hidden">
                        <div
                            class="p-3 bg-info bg-opacity-10 border-bottom border-info border-opacity-10 d-flex align-items-center">
                            <div class="icon-box-sm bg-info text-white me-3 rounded-3 shadow-soft">
                                <i class="bi bi-fire"></i>
                            </div>
                            <h6 class="mb-0 fw-bold">Tours más Vendidos</h6>
                        </div>
                        <div class="card-body p-4">
                            <?php if (empty($popularTours)): ?>
                                <div class="text-center py-4 opacity-50">
                                    <i class="bi bi-bar-chart-line display-6 d-block mb-2"></i>
                                    <p class="small">Lanzando próximas aventuras</p>
                                </div>
                            <?php else:
                                $maxReservas = $popularTours[0]['total_reservas'] ?: 1;
                                foreach ($popularTours as $tour):
                                    $percentage = ($tour['total_reservas'] / $maxReservas) * 100;
                                    ?>
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-bold fs-13 text-truncate"
                                                style="max-width: 180px;"><?php echo htmlspecialchars($tour['nombre']); ?></span>
                                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-2 py-1 fs-11">
                                                <?php echo $tour['total_reservas']; ?> reservas
                                            </span>
                                        </div>
                                        <div class="progress"
                                            style="height: 6px; border-radius: 10px; background-color: var(--border-color);">
                                            <div class="progress-bar bg-info rounded-pill transition-base" role="progressbar"
                                                style="width: <?php echo $percentage; ?>%;"
                                                aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="p-2 border-top border-dynamic border-opacity-10 text-center">
                            <a href="<?php echo BASE_URL; ?>agency/reservations"
                                class="small text-info text-decoration-none fw-bold hover-underline">Ver todas las
                                reservas</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPIs Principales -->
            <div class="col-12">
                <div class="row g-4">
                    <?php if ($_SESSION['user_role'] === 'dueno_agencia'): ?>
                        <!-- KPI: Ingresos del Mes (Solo Dueño) -->
                        <div class="col-md-6 col-lg-3 slide-up">
                            <div class="kpi-mini-card">
                                <div class="icon bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div>
                                    <div class="label">Ingresos del Mes</div>
                                    <div class="value"><?php echo formatCurrency($monthlyRevenue); ?></div>
                                    <div class="trend positive">
                                        <i class="bi bi-arrow-up-short"></i> Acumulado
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- KPI: Guía de Oro (Para Empleados) -->
                        <div class="col-md-6 col-lg-3 slide-up">
                            <div class="kpi-mini-card">
                                <div class="icon bg-warning bg-opacity-10 text-warning">
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <div>
                                    <div class="label">Guía de Oro</div>
                                    <div class="value fs-15">
                                        <?php echo $topGuide ? htmlspecialchars($topGuide['nombre']) : 'Sin datos'; ?>
                                    </div>
                                    <div class="trend positive">
                                        <?php echo $topGuide ? $topGuide['total_salidas'] . ' salidas' : 'Comienza hoy'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- KPI: Ocupación Promedio (Visible para todos) -->
                    <div class="col-md-6 col-lg-3 slide-up-delayed">
                        <div class="kpi-mini-card">
                            <div class="icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-pie-chart-fill"></i>
                            </div>
                            <div>
                                <div class="label">Ocupación Avg.</div>
                                <div class="value"><?php echo round($avgOccupancy, 1); ?>%</div>
                                <div class="trend neutral">Próximas 10 salidas</div>
                            </div>
                        </div>
                    </div>

                    <!-- KPI: Nuevos Clientes (Visible para todos) -->
                    <div class="col-md-6 col-lg-3 slide-up-delayed-2">
                        <div class="kpi-mini-card">
                            <div class="icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-person-plus-fill"></i>
                            </div>
                            <div>
                                <div class="label">Nuevos Clientes</div>
                                <div class="value"><?php echo $monthlyNewClients; ?></div>
                                <div class="trend positive">Este mes</div>
                            </div>
                        </div>
                    </div>

                    <?php if ($_SESSION['user_role'] === 'dueno_agencia'): ?>
                        <!-- KPI: Reservas Totales (Para Dueño) o Ingreso Total -->
                        <div class="col-md-6 col-lg-3 slide-up-delayed-2">
                            <div class="kpi-mini-card">
                                <div class="icon bg-purple bg-opacity-10 text-purple"
                                    style="background-color: rgba(111, 66, 193, 0.1); color: #6f42c1;">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <div>
                                    <div class="label">Ingreso Total</div>
                                    <div class="value"><?php echo formatCurrency($totalRevenue); ?></div>
                                    <div class="trend neutral">Histórico</div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- KPI: Movilidad de Confianza (Para Empleados) -->
                        <div class="col-md-6 col-lg-3 slide-up-delayed-2">
                            <div class="kpi-mini-card">
                                <div class="icon bg-info bg-opacity-10 text-info">
                                    <i class="bi bi-bus-front-fill"></i>
                                </div>
                                <div>
                                    <div class="label">Top Movilidad</div>
                                    <div class="value fs-15">
                                        <?php echo $topTransport ? htmlspecialchars($topTransport['placa']) : 'Sin datos'; ?>
                                    </div>
                                    <div class="trend positive">
                                        <?php echo $topTransport ? $topTransport['total_usos'] . ' usos' : 'Pendiente'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Activity & Quick Actions -->
            <div class="row slide-up-delayed-2">
                <div class="col-lg-8">
                    <div class="glass-container h-100">
                        <div class="d-flex justify-content-between align-items-center p-4 border-bottom border-glass">
                            <h5 class="fw-bold mb-0">Últimas Operaciones</h5>
                            <a href="<?php echo BASE_URL; ?>agency/reservations" class="btn-text-link">Historial
                                Completo <i class="bi bi-chevron-right ms-1"></i></a>
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
                                                            <?php echo mb_strtoupper(mb_substr($res['cliente_nombre'] ?? '', 0, 1)); ?>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold fs-13">
                                                                <?php echo htmlspecialchars(($res['cliente_nombre'] ?? '') . ' ' . ($res['cliente_apellido'] ?? '')); ?>
                                                            </div>
                                                            <div class="text-muted fs-11">
                                                                <?php echo htmlspecialchars(mb_substr($res['tours_nombres'] ?? '', 0, 30)); ?>...
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
                                                    <span
                                                        class="badge <?php echo $badgeClass; ?> small rounded-pill fw-normal px-3"
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
                                class="btn btn-surface-dynamic rounded-4 p-3 text-start d-flex align-items-center border border-dynamic border-opacity-10 hover-up transition-base">
                                <div class="bg-success bg-opacity-10 text-success rounded-3 p-2 me-3"><i
                                        class="bi bi-cart-plus fs-4"></i></div>
                                <div>
                                    <div class="fw-bold text-content-dynamic">Nueva Venta</div>
                                    <div class="small text-muted">Registro rápido</div>
                                </div>
                            </a>
                            <a href="<?php echo BASE_URL; ?>agency/departures/create"
                                class="btn btn-surface-dynamic rounded-4 p-3 text-start d-flex align-items-center border border-dynamic border-opacity-10 hover-up transition-base">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-3"><i
                                        class="bi bi-calendar-plus fs-4"></i></div>
                                <div>
                                    <div class="fw-bold text-content-dynamic">Programar Salida</div>
                                    <div class="small text-muted">Añadir calendario</div>
                                </div>
                            </a>
                            <a href="<?php echo BASE_URL; ?>agency/clients"
                                class="btn btn-surface-dynamic rounded-4 p-3 text-start d-flex align-items-center border border-dynamic border-opacity-10 hover-up transition-base">
                                <div class="bg-info bg-opacity-10 text-info rounded-3 p-2 me-3"><i
                                        class="bi bi-people fs-4"></i></div>
                                <div>
                                    <div class="fw-bold text-content-dynamic">Cartera de Clientes</div>
                                    <div class="small text-muted">Gestión CRM</div>
                                </div>
                            </a>
                        </div>

                        <div class="mt-4 p-3 bg-surface-dynamic border border-dynamic border-opacity-10 rounded-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-shield-check-fill text-success me-2"></i>
                                <span class="small fw-bold text-uppercase">Seguridad</span>
                            </div>
                            <p class="small text-muted mb-0" style="font-size: 0.7rem;">Todas tus operaciones están
                                cifradas y
                                respaldadas automáticamente en nuestra nube segura.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .dashboard-agency {
                background-color: var(--bg-primary);
                min-height: 100vh;
            }

            .text-content-dynamic {
                color: var(--text-primary);
            }

            .bg-surface-dynamic {
                background-color: var(--bg-secondary);
            }

            .btn-surface-dynamic {
                background-color: var(--bg-tertiary);
                color: var(--text-primary);
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
                background: var(--bg-secondary);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border-radius: 24px;
                border: 1px solid var(--border-color);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
            }

            .hover-bg:hover {
                background: var(--hover-bg);
            }

            .transition-base {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .hover-up:hover {
                transform: translateY(-5px);
            }

            .kpi-mini-card {
                background: var(--bg-secondary);
                padding: 24px;
                border-radius: 20px;
                display: flex;
                align-items: center;
                gap: 18px;
                border: 1px solid var(--border-color);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
                transition: all 0.3s ease;
            }

            .kpi-mini-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            }

            .kpi-mini-card .icon {
                width: 52px;
                height: 52px;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.4rem;
            }

            .icon-box-sm {
                width: 38px;
                height: 38px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 10px;
            }

            .kpi-mini-card .label {
                font-size: 0.7rem;
                font-weight: 700;
                color: var(--text-secondary);
                text-transform: uppercase;
                letter-spacing: 0.08em;
            }

            .kpi-mini-card .value {
                font-size: 1.4rem;
                font-weight: 800;
                color: var(--text-primary);
            }

            .kpi-mini-card .value.fs-15 {
                font-size: 1.1rem;
            }

            .kpi-mini-card .trend {
                font-size: 0.65rem;
                font-weight: 600;
            }

            /* Departure Timeline Styling */
            .departure-timeline {
                position: relative;
            }

            .timeline-item {
                display: flex;
                gap: 15px;
                margin-bottom: 20px;
                position: relative;
            }

            .timeline-item:not(.last)::before {
                content: '';
                position: absolute;
                left: 20px;
                top: 40px;
                bottom: -20px;
                width: 2px;
                background: var(--border-color);
                opacity: 0.5;
            }

            .timeline-date {
                width: 42px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                background: var(--bg-tertiary);
                border-radius: 12px;
                padding: 6px;
                border: 1px solid var(--border-color);
                z-index: 1;
            }

            .timeline-date .day {
                font-size: 1rem;
                font-weight: 800;
                color: var(--text-primary);
                line-height: 1;
            }

            .timeline-date .month {
                font-size: 0.6rem;
                font-weight: 700;
                text-transform: uppercase;
                color: var(--text-secondary);
            }

            .timeline-content {
                flex: 1;
                background: var(--bg-tertiary);
                border-radius: 16px;
                padding: 12px 16px;
                border: 1px solid var(--border-color);
                transition: all 0.2s ease;
            }

            .timeline-content.active {
                border-left: 4px solid #ef4444;
                background: rgba(239, 68, 68, 0.03);
            }

            .badge-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
            }

            .scroll-y {
                overflow-y: auto;
            }

            .scroll-y::-webkit-scrollbar {
                width: 5px;
            }

            .scroll-y::-webkit-scrollbar-thumb {
                background: var(--border-color);
                border-radius: 10px;
            }

            .shadow-soft {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }

            .live-pulse {
                display: inline-block;
                width: 10px;
                height: 10px;
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
                    box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
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
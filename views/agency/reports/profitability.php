<?php
// Sistema_New/views/agency/reports/profitability.php
include BASE_PATH . '/views/layouts/header_agency.php';
?>

<div class="container-fluid py-4">
    <!-- Encabezado de la Página -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold mb-0 text-dynamic">
                <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Rentabilidad por Salida
            </h2>
            <p class="text-muted-dynamic mb-0">Análisis detallado de márgenes y utilidades netas.</p>
        </div>
        <div class="col-md-6 text-end">
            <button onclick="window.print()" class="btn btn-outline-primary rounded-pill shadow-sm px-4">
                <i class="bi bi-printer me-2"></i>Imprimir Reporte
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card glass-card border-0 mb-4 shadow-sm">
        <div class="card-body p-3">
            <form method="GET" action="" class="row g-3 align-items-end">
                <input type="hidden" name="path" value="agency/reports/profitability">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted-dynamic">Fecha Inicio</label>
                    <input type="date" name="start_date" class="form-control bg-dynamic border-dynamic text-dynamic"
                        value="<?php echo $startDate; ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted-dynamic">Fecha Fin</label>
                    <input type="date" name="end_date" class="form-control bg-dynamic border-dynamic text-dynamic"
                        value="<?php echo $endDate; ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 shadow-sm">
                        <i class="bi bi-funnel me-2"></i>Filtrar Reporte
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resumen General -->
    <?php
    $totalIngresoBruto = array_sum(array_column($reports, 'ingreso_bruto'));
    $totalUtilidadNeta = array_sum(array_column($reports, 'utilidad_neta'));
    $totalEgresos = $totalIngresoBruto - $totalUtilidadNeta;
    $margenPromedio = ($totalIngresoBruto > 0) ? ($totalUtilidadNeta / $totalIngresoBruto) * 100 : 0;
    ?>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card glass-card border-0 shadow-sm p-3 text-center h-100">
                <div class="text-primary fs-3 mb-2"><i class="bi bi-cash-stack"></i></div>
                <h6 class="text-muted-dynamic small fw-bold uppercase">Ventas Totales</h6>
                <h4 class="fw-bold text-dynamic mb-0">
                    <?php echo formatCurrency($totalIngresoBruto); ?>
                </h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card glass-card border-0 shadow-sm p-3 text-center h-100">
                <div class="text-danger fs-3 mb-2"><i class="bi bi-box-arrow-right"></i></div>
                <h6 class="text-muted-dynamic small fw-bold uppercase">Costos Operativos</h6>
                <h4 class="fw-bold text-dynamic mb-0">
                    <?php echo formatCurrency($totalEgresos); ?>
                </h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card glass-card border-0 shadow-sm p-3 text-center h-100 border-start border-success border-4">
                <div class="text-success fs-3 mb-2"><i class="bi bi-piggy-bank"></i></div>
                <h6 class="text-muted-dynamic small fw-bold uppercase">Utilidad Neta</h6>
                <h4 class="fw-bold text-dynamic mb-0">
                    <?php echo formatCurrency($totalUtilidadNeta); ?>
                </h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card glass-card border-0 shadow-sm p-3 text-center h-100">
                <div class="text-info fs-3 mb-2"><i class="bi bi-pie-chart-fill"></i></div>
                <h6 class="text-muted-dynamic small fw-bold uppercase">Margen de Ganancia (%)</h6>
                <h4 class="fw-bold text-dynamic mb-0">
                    <?php echo number_format($margenPromedio, 1); ?>%
                </h4>
            </div>
        </div>
    </div>

    <!-- Tabla Detallada -->
    <div class="card glass-card border-0 shadow-sm overflow-hidden mb-5">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-soft-dynamic border-dynamic">
                    <tr>
                        <th class="ps-4 border-dynamic py-3">Salida / Tour</th>
                        <th class="border-dynamic py-3">Pax</th>
                        <th class="border-dynamic py-3 text-end">Ingreso Bruto</th>
                        <th class="border-dynamic py-3 text-end">Egresos</th>
                        <th class="border-dynamic py-3 text-end">Utilidad Neta</th>
                        <th class="border-dynamic py-3 text-center">Margen %</th>
                        <th class="border-dynamic py-3 text-center pe-4">Desglose Costos</th>
                    </tr>
                </thead>
                <tbody class="border-dynamic">
                    <?php if (empty($reports)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">No se encontraron salidas para este periodo.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reports as $r): ?>
                            <?php
                            $margen = ($r['ingreso_bruto'] > 0) ? ($r['utilidad_neta'] / $r['ingreso_bruto']) * 100 : 0;
                            $margenClass = ($margen > 30) ? 'text-success' : (($margen > 10) ? 'text-warning' : 'text-danger');
                            $paxColor = ($r['cupos_vendidos'] == $r['cupos_totales']) ? 'badge bg-success' : 'badge bg-soft-dynamic text-dynamic border';
                            ?>
                            <tr class="border-dynamic">
                                <td class="ps-4">
                                    <div class="fw-bold text-dynamic">
                                        <?php echo htmlspecialchars($r['tour_nombre']); ?>
                                    </div>
                                    <small class="text-muted">
                                        <?php echo date('d/m/Y', strtotime($r['fecha_salida'])); ?> -
                                        <?php echo date('H:i', strtotime($r['hora_salida'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="<?php echo $paxColor; ?>">
                                        <?php echo $r['cupos_vendidos']; ?> /
                                        <?php echo $r['cupos_totales']; ?>
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-dynamic">
                                    <?php echo formatCurrency($r['ingreso_bruto']); ?>
                                </td>
                                <td class="text-end text-danger">
                                    -
                                    <?php echo formatCurrency($r['costo_total_operativo']); ?>
                                </td>
                                <td
                                    class="text-end fw-bold <?php echo ($r['utilidad_neta'] >= 0) ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo formatCurrency($r['utilidad_neta']); ?>
                                </td>
                                <td class="text-center fw-bold <?php echo $margenClass; ?>">
                                    <?php echo number_format($margen, 1); ?>%
                                </td>
                                <td class="text-center pe-4">
                                    <span class="badge bg-soft-dynamic text-dynamic border small info-popover"
                                        style="cursor: pointer;" data-bs-toggle="popover" data-bs-trigger="hover focus"
                                        title="Desglose de Costos"
                                        data-bs-content="<div class='small'><strong>Guía:</strong> <?php echo formatCurrency($r['costo_guia']); ?><br><strong>Transporte:</strong> <?php echo formatCurrency($r['costo_transporte']); ?><br><strong>Otros:</strong> <?php echo formatCurrency($r['costo_otros']); ?></div>"
                                        data-bs-html="true">
                                        <i class="bi bi-info-circle me-1 text-primary"></i>Ver Info
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .bg-soft-dynamic {
        background-color: var(--bg-soft);
    }

    .border-dynamic {
        border-color: var(--border-color) !important;
    }

    .uppercase {
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    @media print {

        .btn,
        form,
        footer {
            display: none !important;
        }

        .glass-card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }

        body {
            background: white !important;
            color: black !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })
    });
</script>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
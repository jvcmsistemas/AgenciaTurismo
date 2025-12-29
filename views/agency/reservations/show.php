<?php include BASE_PATH . '/views/layouts/header_agency.php'; ?>

<!-- Incluir html2pdf desde CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary fw-bold mb-0">Detalles de Reserva</h2>
            <p class="text-muted mb-0">Vista previa del comprobante.</p>
        </div>
        <div>
            <a href="<?php echo BASE_URL; ?>agency/reservations"
                class="btn btn-outline-secondary rounded-pill px-4 me-2">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
            <button onclick="downloadPDF()" class="btn btn-danger rounded-pill px-4 shadow-sm">
                <i class="bi bi-file-earmark-pdf me-2"></i>Descargar PDF
            </button>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">

            <!-- Contenedor Específico para el PDF (Diseño Premium) -->
            <div id="invoice-content" class="bg-white shadow-lg mx-auto"
                style="width: 210mm; min-height: 297mm; padding: 15mm; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; position: relative;">

                <!-- Borde Superior de Color -->
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 10px; background: #00695c;"></div>

                <!-- Encabezado con Logo y Título -->
                <div class="d-flex justify-content-between align-items-start mb-5 mt-3">
                    <div>
                        <!-- Logo Placeholder / Nombre Agencia Grande -->
                        <h1 class="fw-bold text-uppercase mb-2"
                            style="color: #00695c; font-size: 24pt; letter-spacing: 1px;">
                            <?php echo htmlspecialchars($agency['nombre']); ?>
                        </h1>
                        <div class="text-secondary small" style="font-size: 10pt; line-height: 1.5;">
                            <?php echo htmlspecialchars($agency['direccion'] ?? 'Dirección de Agencia'); ?><br>
                            <strong>Tel:</strong> <?php echo htmlspecialchars($agency['telefono'] ?? '--'); ?>
                            &nbsp;|&nbsp;
                            <strong>Email:</strong> <?php echo htmlspecialchars($agency['email'] ?? '--'); ?>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="bg-light p-3 rounded border text-start" style="min-width: 200px;">
                            <h5 class="fw-bold mb-1 text-dark text-uppercase" style="font-size: 12pt;">Comprobante de
                                Reserva</h5>
                            <div class="fw-bold text-danger mb-2" style="font-size: 14pt;">
                                <?php echo htmlspecialchars($reservation['codigo_reserva']); ?>
                            </div>
                            <div class="small text-muted">
                                <strong>Fecha:</strong>
                                <?php echo date('d/m/Y', strtotime($reservation['fecha_hora_reserva'])); ?><br>
                                <strong>Hora:</strong>
                                <?php echo date('H:i', strtotime($reservation['fecha_hora_reserva'])); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección: Cliente -->
                <div class="mb-5 pb-3 border-bottom">
                    <h6 class="text-uppercase fw-bold text-secondary mb-3"
                        style="font-size: 9pt; letter-spacing: 1px; color: #00695c !important;">Información del Cliente
                    </h6>
                    <div class="row">
                        <div class="col-7">
                            <h4 class="fw-bold text-dark mb-1">
                                <?php echo htmlspecialchars($reservation['cliente_nombre'] . ' ' . $reservation['cliente_apellido']); ?>
                            </h4>
                            <div class="text-muted small">
                                <?php if ($reservation['cliente_email']): ?>
                                    <div><i
                                            class="bi bi-envelope me-2"></i><?php echo htmlspecialchars($reservation['cliente_email']); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($reservation['cliente_telefono']): ?>
                                    <div><i
                                            class="bi bi-telephone me-2"></i><?php echo htmlspecialchars($reservation['cliente_telefono']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="p-2 small bg-warning bg-opacity-10 rounded border border-warning">
                                <strong>Estado de Reserva:</strong>
                                <span class="fw-bold text-uppercase ms-1" style="color: #d39e00;">
                                    <?php echo htmlspecialchars(ucfirst($reservation['estado'])); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Servicios -->
                <div class="mb-5">
                    <table class="table table-borderless table-striped"
                        style="border-collapse: separate; border-spacing: 0;">
                        <thead style="background-color: #00695c; color: white;">
                            <tr>
                                <th class="py-3 ps-3 rounded-start" style="font-weight: 500; font-size: 10pt;">
                                    DESCRIPCIÓN</th>
                                <th class="py-3 text-center" style="font-weight: 500; font-size: 10pt;">FECHA</th>
                                <th class="py-3 text-center" style="font-weight: 500; font-size: 10pt;">CANT.</th>
                                <th class="py-3 text-end" style="font-weight: 500; font-size: 10pt;">PRECIO UNIT.</th>
                                <th class="py-3 pe-3 text-end rounded-end" style="font-weight: 500; font-size: 10pt;">
                                    IMPORTE</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 10pt;">
                            <?php foreach ($details as $index => $detail): ?>
                                <tr>
                                    <td class="ps-3 py-3 border-bottom">
                                        <div class="fw-bold text-dark">
                                            <?php echo htmlspecialchars($detail['servicio_nombre']); ?>
                                        </div>
                                        <div class="text-muted small">Tour / Servicio Turístico</div>
                                    </td>
                                    <td class="text-center py-3 border-bottom">
                                        <?php if (!empty($detail['fecha_salida'])): ?>
                                            <?php echo date('d/m/Y', strtotime($detail['fecha_salida'])); ?><br>
                                            <span class="text-muted small">
                                                <?php echo date('H:i', strtotime($detail['hora_salida'])); ?> hs
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center py-3 border-bottom text-dark fw-bold">
                                        <?php echo $detail['cantidad']; ?>
                                    </td>
                                    <td class="text-end py-3 border-bottom">
                                        S/ <?php echo number_format($detail['precio_unitario'], 2); ?>
                                    </td>
                                    <td class="text-end pe-3 py-3 border-bottom fw-bold text-dark">
                                        S/ <?php echo number_format($detail['subtotal'], 2); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Totales y Notas -->
                <div class="row mb-5">
                    <div class="col-7">
                        <?php if (!empty($reservation['notas'])): ?>
                            <div class="bg-light p-3 rounded" style="font-size: 9pt; border-left: 4px solid #00695c;">
                                <h6 class="fw-bold mb-1" style="color: #00695c;">Notas Adicionales:</h6>
                                <p class="mb-0 text-muted"><?php echo nl2br(htmlspecialchars($reservation['notas'])); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-5">
                        <div class="bg-light p-3 rounded">
                            <!-- Subtotal -->
                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                <span class="fw-bold text-secondary">Subtotal:</span>
                                <span>S/ <?php echo number_format($reservation['precio_total'], 2); ?></span>
                            </div>

                            <!-- Descuento (Solo si existe) -->
                            <?php if (!empty($reservation['descuento']) && $reservation['descuento'] > 0): ?>
                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom text-danger">
                                    <span class="fw-bold">Descuento:</span>
                                    <span>- S/ <?php echo number_format($reservation['descuento'], 2); ?></span>
                                </div>
                            <?php endif; ?>

                            <!-- Total Final -->
                            <?php
                            $finalTotal = $reservation['precio_total'] - ($reservation['descuento'] ?? 0);
                            $pagado = $finalTotal - $reservation['saldo_pendiente'];
                            ?>
                            <div
                                class="d-flex justify-content-between align-items-center bg-white p-2 rounded shadow-sm border mb-2">
                                <span class="fw-bold text-dark fs-5">TOTAL:</span>
                                <span class="fw-bold text-primary fs-4">S/
                                    <?php echo number_format($finalTotal, 2); ?></span>
                            </div>

                            <!-- Pagado (Calculado visualmente) -->
                            <?php if ($pagado > 0): ?>
                                <div class="d-flex justify-content-between mb-1 small text-success">
                                    <span class="fw-bold">A cuenta / Pagado:</span>
                                    <span>S/ <?php echo number_format($pagado, 2); ?></span>
                                </div>
                            <?php endif; ?>

                            <!-- Saldo -->
                            <?php if ($reservation['saldo_pendiente'] > 0.01): ?>
                                <div class="mt-2 text-end text-danger fw-bold fs-5 border-top pt-2">
                                    <span class="small text-muted me-2">Saldo Pendiente:</span>
                                    S/ <?php echo number_format($reservation['saldo_pendiente'], 2); ?>
                                </div>
                            <?php else: ?>
                                <div class="mt-2 text-end text-success fw-bold small border-top pt-2">
                                    <i class="bi bi-check-circle-fill me-1"></i>TOTALMENTE PAGADO
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Footer Legal -->
                <div style="position: absolute; bottom: 15mm; left: 15mm; right: 15mm;">
                    <div class="row align-items-center border-top pt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted small mb-1" style="font-size: 8pt;">
                                <strong>Términos y Condiciones:</strong> Esta reserva está sujeta a las políticas de
                                cancelación de la agencia.
                                Se requiere llegar con 15 minutos de antelación.
                            </p>
                            <p class="text-muted small mb-0" style="font-size: 8pt;">
                                Gracias por confiar en nosotros para sus viajes.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- SECCIÓN: Historial de Pagos (Fuera del área de impresión principal, pero visible en web) -->
            <div class="card border-0 shadow-sm mt-4 mb-5 no-print">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-cash-coin me-2 text-success"></i>Historial de
                        Pagos</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3">Fecha</th>
                                    <th class="py-3">Método</th>
                                    <th class="py-3">Referencia</th>
                                    <th class="py-3">Nota</th>
                                    <th class="text-end pe-4 py-3">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($payments)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bi bi-wallet2 display-6 d-block mb-3 opacity-25"></i>
                                            Aún no se han registrado pagos para esta reserva.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($payments as $payment): ?>
                                        <tr>
                                            <td class="ps-4 fw-medium text-dark">
                                                <?php echo date('d/m/Y H:i', strtotime($payment['fecha_pago'])); ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">
                                                    <?php echo ucfirst($payment['metodo_pago']); ?>
                                                </span>
                                            </td>
                                            <td class="text-muted small">
                                                <?php echo $payment['referencia'] ? htmlspecialchars($payment['referencia']) : '-'; ?>
                                            </td>
                                            <td class="text-muted small">
                                                <?php echo $payment['notas'] ? htmlspecialchars($payment['notas']) : '-'; ?>
                                            </td>
                                            <td class="text-end pe-4 fw-bold text-success">
                                                S/ <?php echo number_format($payment['monto'], 2); ?>
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
    </div>
</div>

<!-- MODAL: Registrar Pago -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold" id="paymentModalLabel">Registrar Nuevo Abono</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>agency/reservations/payment/add" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4">
                    <input type="hidden" name="reserva_id" value="<?php echo $reservation['id']; ?>">

                    <div class="text-center mb-4">
                        <p class="text-muted small mb-1">Saldo Pendiente Actual</p>
                        <h2 class="fw-bold text-danger">S/
                            <?php echo number_format($reservation['saldo_pendiente'], 2); ?>
                        </h2>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary small">Monto a Pagar (S/)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">S/</span>
                            <input type="number" step="0.01" min="0.1"
                                max="<?php echo $reservation['saldo_pendiente']; ?>"
                                class="form-control border-start-0 ps-0 fw-bold" name="monto" placeholder="0.00"
                                required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-secondary small">Método de Pago</label>
                            <select class="form-select" name="metodo_pago" required>
                                <option value="efectivo">Efectivo</option>
                                <option value="yape">Yape / Plin</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="tarjeta">Tarjeta (Izipay/Niubiz)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-secondary small">Referencia (Opcional)</label>
                            <input type="text" class="form-control" name="referencia"
                                placeholder="# Operación / Voucher">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary small">Notas (Opcional)</label>
                        <textarea class="form-control" name="notas" rows="2"
                            placeholder="Detalles adicionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm fw-bold">
                        <i class="bi bi-check-lg me-2"></i>Confirmar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function downloadPDF() {
        const element = document.getElementById('invoice-content');
        const opt = {
            margin: 0,
            filename: 'Reserva_<?php echo $reservation['codigo_reserva']; ?>.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true, scrollY: 0 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        // Generar y descargar
        html2pdf().set(opt).from(element).save();
    }
</script>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
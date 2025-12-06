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
            <a href="<?php echo BASE_URL; ?>agency/reservations" class="btn btn-outline-secondary rounded-pill px-4 me-2">
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
            <div id="invoice-content" class="bg-white shadow-lg mx-auto" style="width: 210mm; min-height: 297mm; padding: 15mm; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; position: relative;">
                
                <!-- Borde Superior de Color -->
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 10px; background: #00695c;"></div>

                <!-- Encabezado con Logo y Título -->
                <div class="d-flex justify-content-between align-items-start mb-5 mt-3">
                    <div>
                        <!-- Logo Placeholder / Nombre Agencia Grande -->
                        <h1 class="fw-bold text-uppercase mb-2" style="color: #00695c; font-size: 24pt; letter-spacing: 1px;">
                            <?php echo htmlspecialchars($agency['nombre']); ?>
                        </h1>
                        <div class="text-secondary small" style="font-size: 10pt; line-height: 1.5;">
                            <?php echo htmlspecialchars($agency['direccion'] ?? 'Dirección de Agencia'); ?><br>
                            <strong>Tel:</strong> <?php echo htmlspecialchars($agency['telefono'] ?? '--'); ?> &nbsp;|&nbsp; 
                            <strong>Email:</strong> <?php echo htmlspecialchars($agency['email'] ?? '--'); ?>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="bg-light p-3 rounded border text-start" style="min-width: 200px;">
                            <h5 class="fw-bold mb-1 text-dark text-uppercase" style="font-size: 12pt;">Comprobante de Reserva</h5>
                            <div class="fw-bold text-danger mb-2" style="font-size: 14pt;"><?php echo htmlspecialchars($reservation['codigo_reserva']); ?></div>
                            <div class="small text-muted">
                                <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($reservation['fecha_hora_reserva'])); ?><br>
                                <strong>Hora:</strong> <?php echo date('H:i', strtotime($reservation['fecha_hora_reserva'])); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección: Cliente -->
                <div class="mb-5 pb-3 border-bottom">
                    <h6 class="text-uppercase fw-bold text-secondary mb-3" style="font-size: 9pt; letter-spacing: 1px; color: #00695c !important;">Información del Cliente</h6>
                    <div class="row">
                        <div class="col-7">
                            <h4 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($reservation['cliente_nombre'] . ' ' . $reservation['cliente_apellido']); ?></h4>
                            <div class="text-muted small">
                                <?php if($reservation['cliente_email']): ?>
                                    <div><i class="bi bi-envelope me-2"></i><?php echo htmlspecialchars($reservation['cliente_email']); ?></div>
                                <?php endif; ?>
                                <?php if($reservation['cliente_telefono']): ?>
                                    <div><i class="bi bi-telephone me-2"></i><?php echo htmlspecialchars($reservation['cliente_telefono']); ?></div>
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
                    <table class="table table-borderless table-striped" style="border-collapse: separate; border-spacing: 0;">
                        <thead style="background-color: #00695c; color: white;">
                            <tr>
                                <th class="py-3 ps-3 rounded-start" style="font-weight: 500; font-size: 10pt;">DESCRIPCIÓN</th>
                                <th class="py-3 text-center" style="font-weight: 500; font-size: 10pt;">FECHA</th>
                                <th class="py-3 text-center" style="font-weight: 500; font-size: 10pt;">CANT.</th>
                                <th class="py-3 text-end" style="font-weight: 500; font-size: 10pt;">PRECIO UNIT.</th>
                                <th class="py-3 pe-3 text-end rounded-end" style="font-weight: 500; font-size: 10pt;">IMPORTE</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 10pt;">
                            <?php foreach ($details as $index => $detail): ?>
                                <tr>
                                    <td class="ps-3 py-3 border-bottom">
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($detail['servicio_nombre']); ?></div>
                                        <div class="text-muted small">Tour / Servicio Turístico</div>
                                    </td>
                                    <td class="text-center py-3 border-bottom">
                                        <?php echo date('d/m/Y', strtotime($detail['fecha_salida'])); ?><br>
                                        <span class="text-muted small"><?php echo date('H:i', strtotime($detail['hora_salida'])); ?> hs</span>
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
                        <?php if(!empty($reservation['notas'])): ?>
                            <div class="bg-light p-3 rounded" style="font-size: 9pt; border-left: 4px solid #00695c;">
                                <h6 class="fw-bold mb-1" style="color: #00695c;">Notas Adicionales:</h6>
                                <p class="mb-0 text-muted"><?php echo nl2br(htmlspecialchars($reservation['notas'])); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-5">
                        <div class="bg-light p-3 rounded">
                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                <span class="fw-bold text-secondary">Subtotal:</span>
                                <span>S/ <?php echo number_format($reservation['precio_total'], 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold text-secondary">Impuestos (IGV):</span>
                                <span>$ 0.00</span> <!-- Placeholder: Adjust logic if taxes are separate -->
                            </div>
                            <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded shadow-sm border">
                                <span class="fw-bold text-dark fs-5">TOTAL:</span>
                                <span class="fw-bold text-primary fs-4">S/ <?php echo number_format($reservation['precio_total'], 2); ?></span>
                            </div>
                             <?php if ($reservation['saldo_pendiente'] > 0): ?>
                                <div class="mt-2 text-end text-danger fw-bold small">
                                    Pendiente: S/ <?php echo number_format($reservation['saldo_pendiente'], 2); ?>
                                </div>
                            <?php else: ?>
                                <div class="mt-2 text-end text-success fw-bold small">
                                    <i class="bi bi-check-circle-fill me-1"></i>Pagado
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
                                <strong>Términos y Condiciones:</strong> Esta reserva está sujeta a las políticas de cancelación de la agencia. 
                                Se requiere llegar con 15 minutos de antelación.
                            </p>
                            <p class="text-muted small mb-0" style="font-size: 8pt;">
                                Gracias por confiar en nosotros para sus viajes.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function downloadPDF() {
        const element = document.getElementById('invoice-content');
        const opt = {
            margin:       0,
            filename:     'Reserva_<?php echo $reservation['codigo_reserva']; ?>.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, scrollY: 0 },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        // Generar y descargar
        html2pdf().set(opt).from(element).save();
    }
</script>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
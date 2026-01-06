<?php
// seed_faqs.php - Script para poblar FAQs iniciales
require_once __DIR__ . '/config/db.php';

try {
    // El $pdo ya está disponible desde config/db.php

    // Limpiar tabla antes si se desea (opcional)
    // $pdo->exec("DELETE FROM faqs");

    $faqs = [
        [
            'pregunta' => '¿Cómo registro mi primera venta/reserva?',
            'respuesta' => '<div class="rich-content">
                <p>Sigue este flujo estándar para asegurar que los cupos se descuenten correctamente:</p>
                <ol class="ps-3 mb-3">
                    <li>Ve a <strong>Reservas</strong> > <strong>Nueva Venta</strong>.</li>
                    <li><strong>DNI del Cliente:</strong> Ingrésalo y presiona buscar. Si es nuevo, el sistema te permitirá crearlo al instante.</li>
                    <li><strong>Carrito de Tours:</strong> Selecciona el tour y la fecha. <span class="badge bg-soft-success text-success">Tip:</span> Verifica que el contador de cupos esté en verde.</li>
                    <li><strong>Confirmación:</strong> Revisa el total y haz clic en <em>"Confirmar Reserva"</em>.</li>
                </ol>
                <div class="alert alert-info py-2 px-3 small border-0 bg-light-dynamic mb-0">
                    <i class="bi bi-info-circle-fill me-2"></i> Recuerda registrar el abono inicial para que la reserva pase a estado "Confirmada".
                </div>
            </div>',
            'categoria' => 'reservas',
            'orden' => 1
        ],
        [
            'pregunta' => '¿Cómo gestionar mi flota y vehículos?',
            'respuesta' => '<div class="rich-content">
                <p>Tus vehículos son la base de los cupos de tus tours. Para configurarlos:</p>
                <ul class="list-unstyled ps-0 mb-3">
                    <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i> Ve a <strong>Logística</strong> > <strong>Mi Flota</strong>.</li>
                    <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i> Haz clic en <strong>"Añadir Vehículo"</strong>.</li>
                    <li><i class="bi bi-check2-circle text-primary me-2"></i> <strong>Importante:</strong> Ingresa la placa y la "Capacidad Máxima".</li>
                </ul>
                <p class="small text-muted mb-0">Esta capacidad define el límite de ventas por salida cuando el vehículo es asignado.</p>
            </div>',
            'categoria' => 'cuenta',
            'orden' => 2
        ],
        [
            'pregunta' => '¿Cómo corregir un pago registrado por error?',
            'respuesta' => '<div class="rich-content">
                <p>Si te equivocaste en el monto o fecha de un abono:</p>
                <ol class="ps-3 mb-3">
                    <li>Entra al <strong>Detalle de la Reserva</strong> (icono de ojo).</li>
                    <li>Baja hasta la sección <strong>"Historial de Pagos"</strong>.</li>
                    <li>Haz clic en el icono de <strong>basurero rojo</strong> junto al pago erróneo.</li>
                </ol>
                <p class="mb-0">El sistema sumará automáticamente el dinero de vuelta al "Saldo Pendiente" de la reserva.</p>
            </div>',
            'categoria' => 'pagos',
            'orden' => 3
        ],
        [
            'pregunta' => '¿Cómo programar las salidas del mes?',
            'respuesta' => '<div class="rich-content">
                <p>Las salidas vinculan tus tours con fechas y recursos:</p>
                <ol class="ps-3">
                    <li>Selecciona el tour del catálogo.</li>
                    <li>Elige <strong>Fecha y Hora</strong>.</li>
                    <li>Asigna un <strong>Guía</strong> y un <strong>Transporte</strong>.</li>
                </ol>
                <div class="p-2 bg-soft-warning rounded-3 small">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Al elegir transporte, los cupos totales se ajustarán a la capacidad del vehículo.
                </div>
            </div>',
            'categoria' => 'reservas',
            'orden' => 4
        ],
        [
            'pregunta' => '¿Cómo organizar mi catálogo de tours (Zonas, Rutas y Paquetes)?',
            'respuesta' => '<div class="rich-content">
                <p>Para un catálogo ordenado y fácil de vender, utiliza estas estrategias:</p>
                <ul class="ps-3 mb-3">
                    <li><strong>Por Zonas/Rutas:</strong> Agrupa tus tours (Ej: <em>Ruta del Café, Zona Selva Central</em>). Esto ayuda al cliente a decidir según la ubicación.</li>
                    <li><strong>Creación de Paquetes:</strong> Registra un tour que incluya varios itinerarios en la descripción y asígnale un precio especial de paquete.</li>
                    <li><strong>Precios Diferenciados:</strong> Recuerda que puedes establecer precios base que luego puedes ajustar en el carrito al crear la reserva.</li>
                </ul>
                <div class="alert alert-success py-2 px-3 small border-0 bg-light-dynamic">
                    <i class="bi bi-lightbulb-fill me-2 text-warning"></i> <strong>Tip:</strong> Usa nombres claros como "[PAQUETE] Oxapampa 3D/2N" para diferenciarlos de los tours de un solo día.
                </div>
            </div>',
            'categoria' => 'reservas',
            'orden' => 5
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO faqs (pregunta, respuesta, categoria, orden, visible) VALUES (?, ?, ?, ?, 1)");

    foreach ($faqs as $f) {
        $stmt->execute([$f['pregunta'], $f['respuesta'], $f['categoria'], $f['orden']]);
    }

    echo "FAQs predeterminadas insertadas correctamente.";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

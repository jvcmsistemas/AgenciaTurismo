<?php
require_once 'config/db.php';
try {
    // Force set discount for Res #3 to verify UI
    $pdo->exec("UPDATE reservas SET descuento = 50.00, precio_total = 913.00, saldo_pendiente = (913.00 - 50.00) WHERE id = 3");
    echo "Reserva 3 actualizada con Descuento de prueba.";
} catch (Exception $e) {
    echo $e->getMessage();
}

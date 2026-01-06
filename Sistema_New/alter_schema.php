<?php
require_once __DIR__ . '/config/db.php';
try {
    $pdo->exec("ALTER TABLE agencias MODIFY COLUMN tipo_suscripcion ENUM('prueba', 'basico', 'premium', 'semestral', 'anual') DEFAULT 'prueba'");
    echo "Schema Updated Successfully: agencias.tipo_suscripcion now accepts basico, premium.\n";
} catch (PDOException $e) {
    echo "Error updating schema: " . $e->getMessage() . "\n";
}

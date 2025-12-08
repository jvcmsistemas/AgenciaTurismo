<?php
require_once 'config/db.php';

try {
    $stmt = $pdo->query("SHOW COLUMNS FROM reservas LIKE 'descuento'");
    $exists = $stmt->fetch();

    if (!$exists) {
        $pdo->exec("ALTER TABLE reservas ADD COLUMN descuento DECIMAL(10,2) DEFAULT 0.00 AFTER precio_total");
        echo "Columna 'descuento' agregada correctamente.\n";
    } else {
        echo "Columna 'descuento' ya existe.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

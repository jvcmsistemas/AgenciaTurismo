<?php
require_once __DIR__ . '/config/db.php';

try {
    // 1. Agregar columna salida_id (nullable por ahora para compatibilidad, pero idealmente required)
    $sql = "ALTER TABLE reservas ADD COLUMN salida_id INT NULL AFTER tour_id";
    $pdo->exec($sql);
    echo "Columna 'salida_id' agregada correctamente.\n";

    // 2. Agregar Foreign Key
    $sqlFK = "ALTER TABLE reservas ADD CONSTRAINT fk_reserva_salida FOREIGN KEY (salida_id) REFERENCES salidas(id) ON DELETE SET NULL";
    $pdo->exec($sqlFK);
    echo "Foreign Key 'fk_reserva_salida' agregada correctamente.\n";

} catch (PDOException $e) {
    // Si ya existe la columna, dará error, lo capturamos
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "La columna 'salida_id' ya existe.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

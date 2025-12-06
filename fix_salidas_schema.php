<?php
require_once __DIR__ . '/config/db.php';

try {
    // 1. Rename capacidad_total to cupos_totales
    try {
        $pdo->exec("ALTER TABLE salidas CHANGE capacidad_total cupos_totales INT(11)");
        echo "Columna 'capacidad_total' renombrada a 'cupos_totales'.\n";
    } catch (PDOException $e) {
        echo "Info: " . $e->getMessage() . "\n";
    }

    // 2. Add guia_id
    try {
        $pdo->exec("ALTER TABLE salidas ADD COLUMN guia_id INT NULL AFTER tour_id");
        $pdo->exec("ALTER TABLE salidas ADD CONSTRAINT fk_salida_guia FOREIGN KEY (guia_id) REFERENCES guias(id) ON DELETE SET NULL");
        echo "Columna 'guia_id' agregada.\n";
    } catch (PDOException $e) {
        echo "Info: " . $e->getMessage() . "\n";
    }

    // 3. Add transporte_id
    try {
        $pdo->exec("ALTER TABLE salidas ADD COLUMN transporte_id INT NULL AFTER guia_id");
        $pdo->exec("ALTER TABLE salidas ADD CONSTRAINT fk_salida_transporte FOREIGN KEY (transporte_id) REFERENCES transportes(id) ON DELETE SET NULL");
        echo "Columna 'transporte_id' agregada.\n";
    } catch (PDOException $e) {
        echo "Info: " . $e->getMessage() . "\n";
    }

    // 4. Add precio_actual
    try {
        $pdo->exec("ALTER TABLE salidas ADD COLUMN precio_actual DECIMAL(10,2) NULL AFTER cupos_disponibles");
        echo "Columna 'precio_actual' agregada.\n";
    } catch (PDOException $e) {
        echo "Info: " . $e->getMessage() . "\n";
    }

} catch (PDOException $e) {
    echo "Error General: " . $e->getMessage() . "\n";
}

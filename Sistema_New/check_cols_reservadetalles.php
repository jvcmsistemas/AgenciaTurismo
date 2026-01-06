<?php
require_once 'config/db.php';
try {
    $stmt = $pdo->query("DESCRIBE reservadetalles");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $col) {
        echo $col['Field'] . "\n";
    }
} catch (Exception $e) {
    // Try with underscore if failed
    try {
        $stmt = $pdo->query("DESCRIBE reserva_detalles");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "FOUND TABLE: reserva_detalles\n";
        foreach ($columns as $col) {
            echo $col['Field'] . "\n";
        }
    } catch (Exception $ex) {
        echo "Error: " . $e->getMessage() . " | " . $ex->getMessage();
    }
}

<?php
require_once __DIR__ . '/config/db.php';

try {
    echo "--- RESERVAS ---\n";
    $stmt = $pdo->query("DESCRIBE reservas");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
        echo $col['Field'] . " (" . $col['Type'] . ")\n";
    }

    echo "\n--- RESERVA_DETALLES ---\n";
    $stmt = $pdo->query("DESCRIBE reserva_detalles");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
        echo $col['Field'] . " (" . $col['Type'] . ")\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

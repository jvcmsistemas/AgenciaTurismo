<?php
require_once 'config/db.php';
try {
    $stmt = $pdo->query("DESCRIBE reservas");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $col) {
        echo $col['Field'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

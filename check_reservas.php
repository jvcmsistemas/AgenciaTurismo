<?php
require_once 'config/db.php';
try {
    $stmt = $pdo->query("DESCRIBE reservas");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $col) {
        echo " - {$col['Field']} ({$col['Type']})\n";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

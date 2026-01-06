<?php
require_once 'config/db.php';

try {
    $stmt = $pdo->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        if (strpos($row[0], 'reserva') !== false || strpos($row[0], 'detalle') !== false) {
            echo "Found table: " . $row[0] . "\n";
            // Show columns
            $cols = $pdo->query("SHOW COLUMNS FROM {$row[0]}")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($cols as $col) {
                echo "   - {$col['Field']} ({$col['Type']})\n";
            }
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

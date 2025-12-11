<?php
require_once 'config/db.php';

$tables = ['pagos', 'reservas', 'reservadetalles'];

foreach ($tables as $table) {
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Table '$table' exists.\n";
            // Show columns
            $cols = $pdo->query("SHOW COLUMNS FROM $table")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($cols as $col) {
                echo "   - {$col['Field']} ({$col['Type']})\n";
            }
        } else {
            echo "❌ Table '$table' DOES NOT exist.\n";
        }
    } catch (PDOException $e) {
        echo "Error checking $table: " . $e->getMessage() . "\n";
    }
}

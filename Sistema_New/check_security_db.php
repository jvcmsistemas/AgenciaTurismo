<?php
require_once 'config/db.php';

$tables = ['logs_acceso', 'auditorias', 'permisos', 'sesiones', 'intentos_fallidos'];

foreach ($tables as $table) {
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Table '$table' exists.\n";
        } else {
            echo "❌ Table '$table' DOES NOT exist.\n";
        }
    } catch (PDOException $e) {
        echo "Error checking $table: " . $e->getMessage() . "\n";
    }
}

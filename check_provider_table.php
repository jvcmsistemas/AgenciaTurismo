<?php
require_once 'config/db.php';

try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'proveedores'");
    if ($stmt->rowCount() > 0) {
        echo "Tabla 'proveedores' EXISTE.\n";
        $stmtDesc = $pdo->query("DESCRIBE proveedores");
        $columns = $stmtDesc->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $col) {
            echo " - {$col['Field']} ({$col['Type']})\n";
        }
    } else {
        echo "Tabla 'proveedores' NO EXISTE.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

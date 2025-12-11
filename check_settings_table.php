<?php
require_once 'config/db.php';
$stmt = $pdo->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
$found = [];
foreach ($tables as $table) {
    if (strpos($table, 'setting') !== false || strpos($table, 'conf') !== false) {
        $found[] = $table;
    }
}
if (empty($found)) {
    echo "NO_SETTINGS_TABLE";
} else {
    echo "FOUND: " . implode(", ", $found);
}

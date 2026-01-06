<?php
require_once 'config/db.php';
$stmt = $pdo->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
$found = [];
foreach ($tables as $table) {
    if (strpos($table, 'califica') !== false || strpos($table, 'review') !== false || strpos($table, 'comentario') !== false) {
        $found[] = $table;
    }
}
if (empty($found)) {
    echo "NO_RATINGS_TABLE";
} else {
    echo "FOUND: " . implode(", ", $found);
}

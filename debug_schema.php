<?php
require_once __DIR__ . '/config/db.php';
$stmt = $pdo->query("DESCRIBE agencias");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($columns as $c) {
    echo $c['Field'] . " | " . $c['Type'] . "\n";
}

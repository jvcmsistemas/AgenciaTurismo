<?php
require_once __DIR__ . '/config/db.php';
$stmt = $pdo->query("SELECT id, nombre, codigo FROM planes");
$plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "PLANS_LIST_START\n";
print_r($plans);
echo "PLANS_LIST_END\n";

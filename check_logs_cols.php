<?php
require_once 'config/db.php';
$cols = $pdo->query("SHOW COLUMNS FROM logs_acceso")->fetchAll(PDO::FETCH_ASSOC);
foreach ($cols as $col) {
    echo $col['Field'] . "\n";
}

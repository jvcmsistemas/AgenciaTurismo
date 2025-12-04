<?php
require_once __DIR__ . '/config/db.php';

try {
    $sql = file_get_contents(__DIR__ . '/database/password_resets.sql');
    $pdo->exec($sql);
    echo "Tabla password_resets creada exitosamente.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

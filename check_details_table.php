<?php
require_once 'config/db.php';

try {
    $stmt = $pdo->query("SHOW CREATE TABLE reserva_detalles");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $row['Create Table'];
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

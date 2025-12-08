<?php
require_once 'config/db.php';
try {
    $stmt = $pdo->query("SELECT * FROM reservas ORDER BY id DESC LIMIT 1");
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    print_r($res);
} catch (Exception $e) {
    echo $e->getMessage();
}

<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=agencia_turismo_db', 'root', '');
    $stmt = $pdo->query("SELECT id, email, rol, agencia_id FROM usuarios");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo $e->getMessage();
}

<?php
require_once __DIR__ . '/config/db.php';

try {
    $stmt = $pdo->prepare("SELECT id, nombre, email, agencia_id FROM usuarios WHERE email = '002@agencia.com'");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "Usuario encontrado: " . $user['nombre'] . " (ID: " . $user['id'] . ")<br>";
        echo "Agencia ID: " . $user['agencia_id'];
    } else {
        echo "Usuario no encontrado.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

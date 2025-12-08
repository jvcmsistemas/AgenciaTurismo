<?php
require_once 'config/db.php';

$email = '002@agencia.com';

echo "Buscando '$email'...\n";

// 1. Buscar en Usuarios
$stmtUser = $pdo->prepare("SELECT id, agencia_id, rol FROM usuarios WHERE email = :email");
$stmtUser->execute(['email' => $email]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "Encontrado en USUARIOS. ID: {$user['id']}, Rol: {$user['rol']}, Agencia ID: {$user['agencia_id']}\n";
} else {
    echo "No encontrado en USUARIOS.\n";
}

// 2. Buscar en Agencias
$stmtAgency = $pdo->prepare("SELECT id, nombre FROM agencias WHERE email = :email");
$stmtAgency->execute(['email' => $email]);
$agency = $stmtAgency->fetch(PDO::FETCH_ASSOC);

if ($agency) {
    echo "Encontrado en AGENCIAS. ID: {$agency['id']}, Nombre: {$agency['nombre']}\n";
} else {
    echo "No encontrado en AGENCIAS.\n";
}

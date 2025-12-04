<?php
// Sistema_New/database/seed_users.php

require_once __DIR__ . '/../config/db.php';

echo "Conectando a la base de datos...\n";

try {
    // 1. Crear Super Admin
    $email = 'admin@agencia.com';
    $password = 'password123';
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Verificar si existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $exists = $stmt->fetch();

    if ($exists) {
        $stmt = $pdo->prepare("UPDATE usuarios SET contrasena = ? WHERE email = ?");
        $stmt->execute([$hash, $email]);
        echo "Usuario Admin actualizado (Password: $password)\n";
    } else {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, email, contrasena, rol) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Admin', 'General', $email, $hash, 'administrador_general']);
        echo "Usuario Admin creado (Password: $password)\n";
    }

    // 2. Crear Dueño de Agencia
    $emailAgency = 'dueno@agencia.com';
    $passwordAgency = 'agencia123';
    $hashAgency = password_hash($passwordAgency, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$emailAgency]);
    $existsAgency = $stmt->fetch();

    if ($existsAgency) {
        $stmt = $pdo->prepare("UPDATE usuarios SET contrasena = ? WHERE email = ?");
        $stmt->execute([$hashAgency, $emailAgency]);
        echo "Usuario Dueño actualizado (Password: $passwordAgency)\n";
        $duenoId = $existsAgency['id'];
    } else {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, email, contrasena, rol) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Juan', 'Dueño', $emailAgency, $hashAgency, 'dueno_agencia']);
        $duenoId = $pdo->lastInsertId();
        echo "Usuario Dueño creado (Password: $passwordAgency)\n";
    }

    // 3. Crear Agencia vinculada
    $stmt = $pdo->prepare("SELECT id FROM agencias WHERE dueno_id = ?");
    $stmt->execute([$duenoId]);
    $agencyExists = $stmt->fetch();

    if (!$agencyExists) {
        $stmt = $pdo->prepare("INSERT INTO agencias (nombre, email, tipo_suscripcion, dueno_id, estado) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Agencia Demo', 'contacto@agenciademo.com', 'prueba', $duenoId, 'activa']);
        $agencyId = $pdo->lastInsertId();
        echo "Agencia Demo creada vinculada al Dueño.\n";

        // Actualizar usuario con agencia_id
        $stmt = $pdo->prepare("UPDATE usuarios SET agencia_id = ? WHERE id = ?");
        $stmt->execute([$agencyId, $duenoId]);
    } else {
        echo "El dueño ya tiene una agencia asignada.\n";
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

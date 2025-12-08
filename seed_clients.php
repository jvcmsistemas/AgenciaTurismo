<?php
require_once 'config/db.php';
require_once 'models/Client.php';

try {
    // 1. Obtener la agencia por email de usuario (solicitado: 002@agencia.com)
    $targetEmail = '002@agencia.com';
    $stmt = $pdo->prepare("SELECT agencia_id FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $targetEmail]);
    $agencyId = $stmt->fetchColumn();

    if (!$agencyId) {
        // Fallback: buscar si el email es directo de la agencia
        $stmt2 = $pdo->prepare("SELECT id FROM agencias WHERE email = :email LIMIT 1");
        $stmt2->execute(['email' => $targetEmail]);
        $agencyId = $stmt2->fetchColumn();
    }

    if (!$agencyId) {
        die("Error: No se encontró ninguna agencia asociada al email '$targetEmail'.");
    }

    echo "Asociando clientes a Agencia ID: $agencyId (Email: $targetEmail)\n";

    $clientModel = new Client($pdo);

    // 2. Datos Dummy
    $nombres = ['Juan', 'Maria', 'Carlos', 'Ana', 'Luis', 'Sofia', 'Pedro', 'Lucia', 'Miguel', 'Elena'];
    $apellidos = ['Perez', 'Garcia', 'Lopez', 'Martinez', 'Rodriguez', 'Fernandez', 'Gonzalez', 'Diaz', 'Vasquez', 'Castro'];

    $count = 0;
    for ($i = 0; $i < 10; $i++) {
        $nombre = $nombres[$i];
        $apellido = $apellidos[$i];
        $dni = '1000000' . $i; // DNIs únicos simulados

        // Verificar si ya existe para no duplicar en runs sucesivos
        $exists = $clientModel->findByDniOrEmail($dni, $agencyId);

        if (!$exists) {
            $data = [
                'nombre' => $nombre,
                'apellido' => $apellido,
                'email' => strtolower($nombre . '.' . $apellido . '@ejemplo.com'),
                'dni' => $dni,
                'ruc' => null, // Opcional
                'telefono' => '9' . rand(10000000, 99999999),
                'nacionalidad' => 'Peruana',
                'agencia_id' => $agencyId
            ];

            $clientModel->create($data);
            $count++;
            echo "Cliente creado: $nombre $apellido (DNI: $dni)\n";
        } else {
            echo "Cliente saltado (ya existe): $nombre $apellido\n";
        }
    }

    echo "\nTotal clientes insertados: $count\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

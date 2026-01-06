<?php
require __DIR__ . '/config/db.php';

$conn = $pdo;

$agencies = [
    [
        'nombre' => 'Andes Trekking',
        'telefono' => '+51 900100100',
        'email_agencia' => 'contact@andestrek.com',
        'direccion' => 'Av Sol 123',
        'tipo_suscripcion' => 'premium',
        'dueno_nombre' => 'Juan',
        'dueno_apellido' => 'Perez',
        'dueno_email' => 'juan@andestrek.com',
        'password' => '123456',
        'fecha_vencimiento' => date('Y-m-d', strtotime('+1 year'))
    ],
    [
        'nombre' => 'Selva Tours',
        'telefono' => '+51 900200200',
        'email_agencia' => 'info@selvatours.com',
        'direccion' => 'Jr Amazonas 456',
        'tipo_suscripcion' => 'basico',
        'dueno_nombre' => 'Maria',
        'dueno_apellido' => 'Gomez',
        'dueno_email' => 'maria@selvatours.com',
        'password' => '123456',
        'fecha_vencimiento' => date('Y-m-d', strtotime('+6 months'))
    ],
    [
        'nombre' => 'Costa Travel',
        'telefono' => '+51 900300300',
        'email_agencia' => 'ventas@costa.com',
        'direccion' => 'Calle Lima 789',
        'tipo_suscripcion' => 'prueba',
        'dueno_nombre' => 'Pedro',
        'dueno_apellido' => 'Ruiz',
        'dueno_email' => 'pedro@costa.com',
        'password' => '123456',
        'fecha_vencimiento' => date('Y-m-d', strtotime('+1 month'))
    ],
    [
        'nombre' => 'Inka Path',
        'telefono' => '+51 900400400',
        'email_agencia' => 'reserve@inka.com',
        'direccion' => 'Av Cultura 101',
        'tipo_suscripcion' => 'premium',
        'dueno_nombre' => 'Ana',
        'dueno_apellido' => 'Solis',
        'dueno_email' => 'ana@inka.com',
        'password' => '123456',
        'fecha_vencimiento' => date('Y-m-d', strtotime('+1 year'))
    ],
    [
        'nombre' => 'Amazon Explorers',
        'telefono' => '+51 900500500',
        'email_agencia' => 'book@amazon.com',
        'direccion' => 'Jr Rio 202',
        'tipo_suscripcion' => 'basico',
        'dueno_nombre' => 'Luis',
        'dueno_apellido' => 'Diaz',
        'dueno_email' => 'luis@amazon.com',
        'password' => '123456',
        'fecha_vencimiento' => date('Y-m-d', strtotime('+6 months'))
    ]
];

foreach ($agencies as $agency) {
    try {
        // 1. Check if USER exists
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$agency['dueno_email']]);
        if ($user = $stmt->fetch()) {
            echo "Skipping User " . $agency['dueno_email'] . " (Already exists)\n";
            $userId = $user['id'];
        } else {
            // Create User
            $sqlUser = "INSERT INTO usuarios (nombre, apellido, email, contrasena, rol, created_at) 
                        VALUES (:nombre, :apellido, :email, :password, 'agencia', NOW())";
            $stmtUser = $conn->prepare($sqlUser);
            $stmtUser->execute([
                ':nombre' => $agency['dueno_nombre'],
                ':apellido' => $agency['dueno_apellido'],
                ':email' => $agency['dueno_email'],
                ':password' => password_hash($agency['password'], PASSWORD_DEFAULT)
            ]);
            $userId = $conn->lastInsertId();
            echo "Created User: " . $agency['dueno_email'] . " (ID: $userId)\n";
        }

        // 2. Check if AGENCY exists (by email)
        // Adjust field name: model uses 'email', not 'email_agencia'
        $stmt = $conn->prepare("SELECT id FROM agencias WHERE email = ?");
        $stmt->execute([$agency['email_agencia']]);

        if ($stmt->fetch()) {
            echo "Agency " . $agency['nombre'] . " exists. Updating data...\n";
            // Update logic to ensure plan/date are set correctly
            $sqlUpdate = "UPDATE agencias SET 
                          tipo_suscripcion = :tipo_suscripcion, 
                          fecha_vencimiento = :fecha_vencimiento 
                          WHERE email = :email";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->execute([
                ':tipo_suscripcion' => $agency['tipo_suscripcion'],
                ':fecha_vencimiento' => $agency['fecha_vencimiento'],
                ':email' => $agency['email_agencia']
            ]);
            echo "Updated " . $agency['nombre'] . "\n";
        } else {
            // Create Agency
            $sqlAgency = "INSERT INTO agencias 
                    (nombre, telefono, email, direccion, tipo_suscripcion, 
                     fecha_vencimiento, dueno_id, estado, created_at) 
                    VALUES 
                    (:nombre, :telefono, :email, :direccion, :tipo_suscripcion,
                     :fecha_vencimiento, :dueno_id, 'activa', NOW())";

            $stmtAgency = $conn->prepare($sqlAgency);
            $stmtAgency->execute([
                ':nombre' => $agency['nombre'],
                ':telefono' => $agency['telefono'],
                ':email' => $agency['email_agencia'], // Map email_agencia to email
                ':direccion' => $agency['direccion'],
                ':tipo_suscripcion' => $agency['tipo_suscripcion'],
                ':fecha_vencimiento' => $agency['fecha_vencimiento'],
                ':dueno_id' => $userId
            ]);
            echo "Created Agency: " . $agency['nombre'] . "\n";
        }

    } catch (PDOException $e) {
        echo "Error creating " . $agency['nombre'] . ": " . $e->getMessage() . "\n";
    }
}
?>
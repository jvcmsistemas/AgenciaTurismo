<?php
require_once __DIR__ . '/config/db.php';

$agencyId = 1;
$demoEmail = 'demo@agencia.com';
$demoPass = '123456';

try {
    // 1. Check if Agency 1 exists
    $stmt = $pdo->prepare("SELECT * FROM agencias WHERE id = ?");
    $stmt->execute([$agencyId]);
    $agency = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$agency) {
        echo "Agency ID 1 not found. Creating it...\n";
        // Logic to create if missing (though user said it exists)
        // For now focusing on UPDATE as requested
        exit("Error: Agency #1 does not exist to update.\n");
    }

    echo "Found Agency #1: " . $agency['nombre'] . "\n";

    // 2. Create/Update Owner User
    $stmtUser = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmtUser->execute([$demoEmail]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $userId = $user['id'];
        echo "User $demoEmail exists (ID: $userId).\n";
    } else {
        $sqlUser = "INSERT INTO usuarios (nombre, apellido, email, contrasena, rol, created_at) 
                    VALUES ('Demo', 'User', :email, :pass, 'agencia', NOW())";
        $stmtCreateUser = $pdo->prepare($sqlUser);
        $stmtCreateUser->execute([':email' => $demoEmail, ':pass' => password_hash($demoPass, PASSWORD_DEFAULT)]);
        $userId = $pdo->lastInsertId();
        echo "Created User $demoEmail (ID: $userId).\n";
    }

    // 3. Update Agency Data
    $sqlUpdate = "UPDATE agencias SET 
                  nombre = 'Agencia de Turismo Demo',
                  email = :email,
                  telefono = '+51 900 800 700',
                  direccion = 'Av. El Sol 101, Cusco',
                  tipo_suscripcion = 'prueba',
                  fecha_vencimiento = :vence,
                  dueno_id = :uid,
                  estado = 'activa'
                  WHERE id = :id";

    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->execute([
        ':email' => $demoEmail,
        ':vence' => date('Y-m-d', strtotime('+1 month')),
        ':uid' => $userId,
        ':id' => $agencyId
    ]);

    echo "Agency #1 Updated Successfully.\n";
    echo "Email: $demoEmail\n";
    echo "Plan: prueba\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

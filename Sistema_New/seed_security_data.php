<?php
require_once 'config/db.php';

try {
    $stmt = $pdo->query("SELECT id, email FROM usuarios WHERE rol = 'administrador_general' LIMIT 1");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("No superadmin found.\n");
    }

    $uid = $user['id'];
    $email = $user['email'];

    // Insert Logs individually
    $pdo->exec("INSERT INTO logs_acceso (usuarioid, email, tipo_evento, direccion_ip, user_agent, endpoint, metodo_http, codigo_respuesta, resultado, fecha_hora) VALUES 
    ($uid, '$email', 'login', '192.168.1.10', 'Mozilla/5.0', '/admin/login', 'POST', 200, 'exitoso', NOW())");

    $pdo->exec("INSERT INTO logs_acceso (usuarioid, email, tipo_evento, direccion_ip, user_agent, endpoint, metodo_http, codigo_respuesta, resultado, fecha_hora) VALUES 
    ($uid, '$email', 'acceso_recurso', '192.168.1.10', 'Chrome', '/admin/dashboard', 'GET', 200, 'exitoso', DATE_SUB(NOW(), INTERVAL 5 MINUTE))");

    $pdo->exec("INSERT INTO logs_acceso (usuarioid, email, tipo_evento, direccion_ip, user_agent, endpoint, metodo_http, codigo_respuesta, resultado, fecha_hora) VALUES 
    ($uid, '$email', 'intento_fallido', '10.0.0.5', 'Bot', '/admin/login', 'POST', 401, 'fallido', DATE_SUB(NOW(), INTERVAL 1 HOUR))");

    echo "✅ Access Logs seeded.\n";

    // Insert Audits
    $pdo->exec("INSERT INTO auditorias (usuarioid, tabla, id_recurso, tipo_operacion, razon_cambio, fecha_hora) VALUES 
    ($uid, 'planes', 1, 'actualizar', 'Actualización de precios anual', NOW())");

    $pdo->exec("INSERT INTO auditorias (usuarioid, tabla, id_recurso, tipo_operacion, razon_cambio, fecha_hora) VALUES 
    ($uid, 'usuarios', 5, 'eliminar', 'Usuario inactivo', DATE_SUB(NOW(), INTERVAL 2 HOUR))");

    echo "✅ Audit Logs seeded.\n";

    // Insert Failed Attempts
    $pdo->exec("INSERT INTO intentos_fallidos (email, direccion_ip, razon, fecha_intento) VALUES 
    ('hacker@evil.com', '45.45.45.45', 'password_incorrecto', NOW())");

    echo "✅ Failed Attempts seeded.\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}

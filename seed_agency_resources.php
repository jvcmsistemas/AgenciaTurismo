<?php
require_once __DIR__ . '/config/db.php';

$agencyId = 5;

try {
    echo "<h2>Insertando datos de prueba para Agencia ID: $agencyId</h2>";

    // --- 1. GUÍAS (5) ---
    $guides = [
        ['nombre' => 'Carlos Ruiz', 'dni' => '45879632', 'telefono' => '987654321', 'email' => 'carlos.ruiz@email.com', 'idiomas' => 'Español, Inglés'],
        ['nombre' => 'Ana Morales', 'dni' => '74125896', 'telefono' => '912345678', 'email' => 'ana.morales@email.com', 'idiomas' => 'Español, Francés'],
        ['nombre' => 'Luis Torres', 'dni' => '36985214', 'telefono' => '998877665', 'email' => 'luis.torres@email.com', 'idiomas' => 'Español, Inglés, Alemán'],
        ['nombre' => 'Maria Lopez', 'dni' => '15975346', 'telefono' => '951753852', 'email' => 'maria.lopez@email.com', 'idiomas' => 'Español, Portugués'],
        ['nombre' => 'Jorge Diaz', 'dni' => '25845691', 'telefono' => '963852741', 'email' => 'jorge.diaz@email.com', 'idiomas' => 'Español']
    ];

    $stmtGuide = $pdo->prepare("INSERT INTO guias (agencia_id, nombre, dni, telefono, email, idiomas, estado) VALUES (:agencia_id, :nombre, :dni, :telefono, :email, :idiomas, 'activo')");

    foreach ($guides as $g) {
        $g['agencia_id'] = $agencyId;
        $stmtGuide->execute($g);
        echo "Guía insertado: {$g['nombre']}<br>";
    }

    // --- 2. TRANSPORTE (5) ---
    $transports = [
        ['placa' => 'ABC-123', 'modelo' => 'Toyota Coaster', 'capacidad' => 20, 'chofer_nombre' => 'Pedro Castillo', 'chofer_telefono' => '999111222'],
        ['placa' => 'XYZ-789', 'modelo' => 'Mercedes Sprinter', 'capacidad' => 15, 'chofer_nombre' => 'Juan Perez', 'chofer_telefono' => '999333444'],
        ['placa' => 'DEF-456', 'modelo' => 'Hyundai H1', 'capacidad' => 10, 'chofer_nombre' => 'Roberto Gomez', 'chofer_telefono' => '999555666'],
        ['placa' => 'GHI-101', 'modelo' => 'Bus Volvo', 'capacidad' => 45, 'chofer_nombre' => 'Miguel Angel', 'chofer_telefono' => '999777888'],
        ['placa' => 'JKL-202', 'modelo' => 'Toyota Hiace', 'capacidad' => 12, 'chofer_nombre' => 'Jose Luis', 'chofer_telefono' => '999999000']
    ];

    $stmtTransport = $pdo->prepare("INSERT INTO transportes (agencia_id, placa, modelo, capacidad, chofer_nombre, chofer_telefono, estado) VALUES (:agencia_id, :placa, :modelo, :capacidad, :chofer_nombre, :chofer_telefono, 'activo')");

    foreach ($transports as $t) {
        $t['agencia_id'] = $agencyId;
        $stmtTransport->execute($t);
        echo "Transporte insertado: {$t['placa']}<br>";
    }

    // --- 3. PROVEEDORES (5) ---
    $providers = [
        ['nombre' => 'Restaurante El Buen Sabor', 'tipo' => 'restaurante', 'contacto_nombre' => 'Maria Chef', 'telefono' => '01-222-3333', 'ubicacion' => 'Plaza de Armas'],
        ['nombre' => 'Hotel Los Andes', 'tipo' => 'hotel', 'contacto_nombre' => 'Gerente Juan', 'telefono' => '01-444-5555', 'ubicacion' => 'Av. Sol 123'],
        ['nombre' => 'Restaurante Campestre', 'tipo' => 'restaurante', 'contacto_nombre' => 'Dona Julia', 'telefono' => '987654321', 'ubicacion' => 'Valle Sagrado'],
        ['nombre' => 'Hostal Mochilero', 'tipo' => 'hotel', 'contacto_nombre' => 'Alex Host', 'telefono' => '912345678', 'ubicacion' => 'Callejon 456'],
        ['nombre' => 'Tickets Machu Picchu', 'tipo' => 'ticket', 'contacto_nombre' => 'Ministerio Cultura', 'telefono' => '01-000-0000', 'ubicacion' => 'Aguas Calientes']
    ];

    $stmtProvider = $pdo->prepare("INSERT INTO proveedores (agencia_id, nombre, tipo, contacto_nombre, telefono, ubicacion, estado) VALUES (:agencia_id, :nombre, :tipo, :contacto_nombre, :telefono, :ubicacion, 'activo')");

    foreach ($providers as $p) {
        $p['agencia_id'] = $agencyId;
        $stmtProvider->execute($p);
        echo "Proveedor insertado: {$p['nombre']}<br>";
    }

    echo "<h3>¡Carga de datos completada exitosamente!</h3>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

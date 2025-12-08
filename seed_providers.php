<?php
require_once 'config/db.php';

$agencyId = 5; // Oxa

// Datos Dummy
$providers = [
    ['nombre' => 'Hotel Cielo Azul', 'tipo' => 'hotel', 'contacto' => 'Juan Admin', 'telefono' => '999111222', 'ubicacion' => 'Plaza de Armas'],
    ['nombre' => 'Posada del Sol', 'tipo' => 'hotel', 'contacto' => 'Maria Recepcionista', 'telefono' => '999333444', 'ubicacion' => 'Calle Real 123'],
    ['nombre' => 'Restaurante El Valle', 'tipo' => 'restaurante', 'contacto' => 'Chef Carlos', 'telefono' => '988555666', 'ubicacion' => 'Av. Principal'],
    ['nombre' => 'Comedor Típico Oxapampa', 'tipo' => 'restaurante', 'contacto' => 'Sra. Juana', 'telefono' => '988777888', 'ubicacion' => 'Jr. Grau 456'],
    ['nombre' => 'Transportes Rápidos', 'tipo' => 'transporte', 'contacto' => 'Luis Chofer', 'telefono' => '977000111', 'ubicacion' => 'Terminal Terrestre']
];

try {
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM proveedores WHERE nombre = :nombre AND agencia_id = :id");
    $stmtInsert = $pdo->prepare("INSERT INTO proveedores (agencia_id, nombre, tipo, contacto_nombre, telefono, ubicacion, created_at) VALUES (:ag_id, :nom, :tipo, :cont, :tel, :ubi, NOW())");

    echo "Insertando proveedores para Agencia $agencyId...\n";

    foreach ($providers as $p) {
        // Check duplicados
        $stmtCheck->execute(['nombre' => $p['nombre'], 'id' => $agencyId]);
        if ($stmtCheck->fetchColumn() == 0) {
            $stmtInsert->execute([
                'ag_id' => $agencyId,
                'nom' => $p['nombre'],
                'tipo' => $p['tipo'],
                'cont' => $p['contacto'],
                'tel' => $p['telefono'],
                'ubi' => $p['ubicacion']
            ]);
            echo " [OK] {$p['nombre']} ({$p['tipo']})\n";
        } else {
            echo " [SKIP] {$p['nombre']} already exists.\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

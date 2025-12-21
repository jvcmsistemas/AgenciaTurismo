<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/models/Agency.php';

$pdo = isset($pdo) ? $pdo : null;
if (!$pdo) {
    if (class_exists('Database')) {
        $db = new Database();
        $pdo = $db->connect();
    } else {
        // Fallback if $pdo is variable in db.php
        include __DIR__ . '/config/db.php';
        // check $pdo again
    }
}

try {
    $agencyModel = new Agency($pdo);
    $agencies = $agencyModel->getAll();

    echo "Total Agencies Found: " . count($agencies) . "\n\n";

    foreach ($agencies as $a) {
        echo "ID: " . $a['id'] . "\n";
        echo "Name: " . $a['nombre'] . "\n";
        echo "Plan (DB): '" . $a['tipo_suscripcion'] . "'\n";
        echo "Expiry: " . $a['fecha_vencimiento'] . "\n";
        echo "-------------------\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

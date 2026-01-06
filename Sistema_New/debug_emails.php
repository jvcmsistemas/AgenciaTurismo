<?php
require_once __DIR__ . '/config/db.php';
$conn = $pdo;
$stmt = $conn->query("SELECT id, nombre, email, tipo_suscripcion FROM agencias");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($results as $r) {
    echo "ID: " . $r['id'] . " | Name: " . $r['nombre'] . " | Email: " . $r['email'] . " | Plan: " . $r['tipo_suscripcion'] . "\n";
}

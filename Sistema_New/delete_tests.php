<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/models/Plan.php';
$planModel = new Plan($pdo);
$stmt = $pdo->query("SELECT id, nombre FROM planes WHERE nombre LIKE '%test%' OR nombre LIKE '%prueba%' AND id != 1");
$toDelete = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($toDelete as $p) {
    try {
        $planModel->delete($p['id']);
        echo "Deleted plan: {$p['nombre']} (ID: {$p['id']})\n";
    } catch (Exception $e) {
        echo "Error deleting {$p['nombre']}: {$e->getMessage()}\n";
    }
}
echo "SERVER_PATH: " . __FILE__ . "\n";
echo "BASE_PATH: " . (defined('BASE_PATH') ? BASE_PATH : 'Not Defined') . "\n";

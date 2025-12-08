<?php
// Simular entorno
define('BASE_PATH', __DIR__);
$_SESSION['user_role'] = 'dueno_agencia';
$_SESSION['agencia_id'] = 5; // ID 'Oxa'
$_GET['q'] = 'Juan';

require_once 'config/db.php';
require_once 'controllers/ClientController.php';

// Capturar output buffer para ver JSON
ob_start();
$controller = new ClientController($pdo);
$controller->searchApi();
$output = ob_get_clean();

echo "API Response:\n" . $output;

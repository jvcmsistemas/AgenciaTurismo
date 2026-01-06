<?php
// run_seeders.php
define('BASE_PATH', __DIR__);
require_once 'config/db.php';
require_once 'seeds/TourSeeder.php';
require_once 'seeds/ReservationSeeder.php';

try {
    $agencyId = 1; // "Audit Test Agency" - demo@agencia.com / marco@agencia.com
    echo "Poblando datos para la Agencia ID: $agencyId...\n";

    $tourSeeder = new TourSeeder($pdo, $agencyId);
    $tourSeeder->run();

    $reservaSeeder = new ReservationSeeder($pdo, $agencyId);
    $reservaSeeder->run();

    echo "✅ Semillas ejecutadas con éxito.\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

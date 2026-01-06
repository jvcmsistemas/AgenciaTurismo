<?php
require_once 'config/db.php';

try {
    $stmt = $pdo->query("SELECT id, nombre, email, rol FROM usuarios");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Found " . count($users) . " users:\n";
    foreach ($users as $u) {
        echo "ID: {$u['id']} | Name: {$u['nombre']} | Email: {$u['email']} | Role: {$u['rol']}\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

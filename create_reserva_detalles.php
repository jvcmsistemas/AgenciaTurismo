<?php
require_once __DIR__ . '/config/db.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS reserva_detalles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        reserva_id INT NOT NULL,
        tipo_servicio ENUM('tour', 'transporte', 'guia', 'otro') DEFAULT 'tour',
        servicio_id INT NOT NULL COMMENT 'ID de la salida, transporte, etc.',
        cantidad INT NOT NULL,
        precio_unitario DECIMAL(10,2) NOT NULL,
        subtotal DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (reserva_id) REFERENCES reservas(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $pdo->exec($sql);
    echo "Table 'reserva_detalles' created successfully.\n";

    // Remove foreign keys from reservas if they exist (to allow multiple items not tied to one column)
    // But for backward compatibility/simplicity, maybe we leave them as "main service" info or nullable?
    // Let's make salida_id nullable in reservas if it isn't already.

    // Check constraints first
    // For now, I will just ensure the table exists. I won't drop columns from 'reservas' yet to avoid breaking other things immediately.

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

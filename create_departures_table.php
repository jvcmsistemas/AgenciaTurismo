<?php
require_once __DIR__ . '/config/db.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS salidas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        agencia_id INT NOT NULL,
        tour_id INT NOT NULL,
        fecha_salida DATETIME NOT NULL,
        guia_id INT NULL,
        transporte_id INT NULL,
        cupos_totales INT NOT NULL DEFAULT 0,
        cupos_disponibles INT NOT NULL DEFAULT 0,
        precio_actual DECIMAL(10, 2) NULL,
        estado ENUM('programada', 'confirmada', 'cerrada', 'cancelada') DEFAULT 'programada',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (agencia_id) REFERENCES agencias(id) ON DELETE CASCADE,
        FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
        FOREIGN KEY (guia_id) REFERENCES guias(id) ON DELETE SET NULL,
        FOREIGN KEY (transporte_id) REFERENCES transportes(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $pdo->exec($sql);
    echo "Tabla 'salidas' creada exitosamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}

<?php
require_once 'config/db.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS pagos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        reserva_id INT NOT NULL,
        monto DECIMAL(10,2) NOT NULL,
        metodo_pago VARCHAR(50) NOT NULL, -- efectivo, yape, tarjeta, transferencia
        fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,
        referencia VARCHAR(255) NULL,
        notas TEXT NULL,
        agencia_id INT NOT NULL,
        FOREIGN KEY (reserva_id) REFERENCES reservas(id) ON DELETE CASCADE,
        FOREIGN KEY (agencia_id) REFERENCES agencias(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $pdo->exec($sql);
    echo "Tabla 'pagos' creada correctamente.";

} catch (PDOException $e) {
    die("Error al crear tabla: " . $e->getMessage());
}

<?php
require_once 'config/db.php';

try {
    // 1. Crear tabla reviews (Para Satisfacción)
    $sql = "CREATE TABLE IF NOT EXISTS reviews (
        id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        reserva_id int(11) NOT NULL,
        cliente_id int(11) NOT NULL,
        agencia_id int(11) NOT NULL,
        calificacion int(1) NOT NULL COMMENT '1 to 5 stars',
        comentario text DEFAULT NULL,
        visible tinyint(1) DEFAULT 1 COMMENT '1=Published, 0=Hidden',
        created_at timestamp DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (reserva_id) REFERENCES reservas(id) ON DELETE CASCADE,
        -- Assuming clientes and agencias tables exist and use consistent IDs
        INDEX idx_agencia_rating (agencia_id, calificacion),
        INDEX idx_reserva (reserva_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $pdo->exec($sql);
    echo "✅ Tabla 'reviews' creada o verificada.\n";

    // 2. Sembrar datos de prueba (Reviews)
    // Primero obtenemos algunos IDs válidos para no fallar foreign keys
    $reserva = $pdo->query("SELECT id, cliente_id, agencia_id FROM reservas LIMIT 1")->fetch();

    if ($reserva) {
        $stmt = $pdo->prepare("INSERT INTO reviews (reserva_id, cliente_id, agencia_id, calificacion, comentario, created_at) VALUES 
            (:rid, :cid, :aid, 5, 'Excelente servicio, muy recomendado!', NOW()),
            (:rid, :cid, :aid, 4, 'Buen tour, pero la comida pudo ser mejor.', DATE_SUB(NOW(), INTERVAL 1 DAY)),
            (:rid, :cid, :aid, 5, 'Increíble experiencia.', DATE_SUB(NOW(), INTERVAL 2 DAY))
        ");

        // Insertamos 3 reviews para la misma reserva/agencia para pruebas
        for ($i = 0; $i < 3; $i++) {
            $stmt->execute([
                'rid' => $reserva['id'],
                'cid' => $reserva['cliente_id'],
                'aid' => $reserva['agencia_id']
            ]);
        }
        echo "✅ Datos de prueba (Reviews) insertados.\n";
    } else {
        echo "ℹ️ No se insertaron reviews de prueba porque no hay reservas activas.\n";
    }

} catch (PDOException $e) {
    die("❌ Error en migración: " . $e->getMessage() . "\n");
}

<?php
require_once 'config/db.php';

try {
    // 1. Tabla Tickets
    $sqlTickets = "CREATE TABLE IF NOT EXISTS tickets (
        id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        agencia_id int(11) NOT NULL,
        usuario_id int(11) NOT NULL COMMENT 'Usuario que abrió el ticket',
        asunto varchar(255) NOT NULL,
        prioridad enum('baja','media','alta','critica') DEFAULT 'media',
        estado enum('abierto','esperando_cliente','resuelto','cerrado') DEFAULT 'abierto',
        categoria enum('tecnico','facturacion','feature_request','otro') DEFAULT 'tecnico',
        created_at timestamp DEFAULT CURRENT_TIMESTAMP,
        updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (agencia_id) REFERENCES agencias(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sqlTickets);
    echo "✅ Tabla 'tickets' creada.\n";

    // 2. Tabla Mensajes
    $sqlMensajes = "CREATE TABLE IF NOT EXISTS ticket_mensajes (
        id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        ticket_id int(11) NOT NULL,
        usuario_id int(11) NOT NULL COMMENT 'Remitente',
        mensaje text NOT NULL,
        adjunto_url varchar(255) DEFAULT NULL,
        created_at timestamp DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sqlMensajes);
    echo "✅ Tabla 'ticket_mensajes' creada.\n";

    // 3. Tabla FAQs
    $sqlFaqs = "CREATE TABLE IF NOT EXISTS faqs (
        id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        pregunta varchar(255) NOT NULL,
        respuesta text NOT NULL,
        categoria varchar(50) DEFAULT 'General',
        orden int(11) DEFAULT 0,
        visible tinyint(1) DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sqlFaqs);
    echo "✅ Tabla 'faqs' creada.\n";

    // 4. Seed FAQs
    $stmt = $pdo->prepare("INSERT INTO faqs (pregunta, respuesta, categoria, orden) VALUES 
        (:p, :r, :c, :o)");

    // Check if empty first
    $count = $pdo->query("SELECT COUNT(*) FROM faqs")->fetchColumn();
    if ($count == 0) {
        $faqs = [
            ['p' => '¿Cómo cambio mi plan de suscripción?', 'r' => 'Puede cambiar su plan desde el panel en Configuración > Suscripción.', 'c' => 'Facturación', 'o' => 1],
            ['p' => '¿Cómo registro un nuevo pago?', 'r' => 'Vaya al menú Pagos y haga clic en Registrar Pago.', 'c' => 'Operativo', 'o' => 2],
            ['p' => '¿Puedo cancelar una reserva confirmada?', 'r' => 'Sí, pero dependerá de las políticas de cancelación de su agencia.', 'c' => 'Operativo', 'o' => 3]
        ];
        foreach ($faqs as $faq) {
            $stmt->execute($faq);
        }
        echo "✅ FAQs iniciales insertadas.\n";
    }

} catch (PDOException $e) {
    die("❌ Error en migración: " . $e->getMessage() . "\n");
}

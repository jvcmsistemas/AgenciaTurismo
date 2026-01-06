<?php
require_once 'config/db.php';

try {
    // 1. Drop existing mismatched table
    $pdo->exec("DROP TABLE IF EXISTS logs_acceso");
    echo "✅ Old logs_acceso dropped.\n";

    // 2. Tabla logs_acceso (Nuevo Schema)
    $pdo->exec("CREATE TABLE logs_acceso (
      id                int(11)       NOT NULL AUTO_INCREMENT PRIMARY KEY,
      usuarioid         int(11)       NOT NULL,
      email             varchar(100)  NOT NULL,
      tipo_evento       enum('login','logout','acceso_recurso','intento_fallido','cambio_contrasena') NOT NULL,
      direccion_ip      varchar(45)   DEFAULT NULL,
      user_agent        text          DEFAULT NULL,
      endpoint          varchar(255)  DEFAULT NULL,
      metodo_http       enum('GET','POST','PUT','DELETE','PATCH') DEFAULT NULL,
      codigo_respuesta  int(3)        DEFAULT NULL,
      descripcion       text          DEFAULT NULL,
      resultado         enum('exitoso','fallido') DEFAULT 'exitoso',
      fecha_hora        datetime      NOT NULL DEFAULT CURRENT_TIMESTAMP,
      INDEX idx_usuarioid (usuarioid),
      INDEX idx_fecha_hora (fecha_hora)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "✅ Table logs_acceso created with correct schema.\n";

    // ... (Rest of tables are already trusted or created previously, but good to ensure)
    // For brevity, assuming other tables are fine since they were created in step 505 successfully.

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

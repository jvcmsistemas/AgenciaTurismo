<?php
require_once 'config/db.php';

try {
    // 2. Tabla logs_acceso (Verificamos si existe, si no la creamos o alteramos)
    // Ya existe segun el check, pero aseguramos la estructura
    $pdo->exec("CREATE TABLE IF NOT EXISTS logs_acceso (
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
    echo "✅ Table logs_acceso checked/created.\n";


    // 3. Tabla auditorias
    $pdo->exec("CREATE TABLE IF NOT EXISTS auditorias (
      id              int(11)       NOT NULL AUTO_INCREMENT PRIMARY KEY,
      usuarioid       int(11)       NOT NULL,
      agenciaid       int(11)       DEFAULT NULL,
      tabla           varchar(50)   NOT NULL,
      id_recurso      int(11)       NOT NULL,
      tipo_operacion  enum('crear','actualizar','eliminar') NOT NULL,
      valores_antes   json          DEFAULT NULL,
      valores_despues json          DEFAULT NULL,
      razon_cambio    text          DEFAULT NULL,
      fecha_hora      datetime      NOT NULL DEFAULT CURRENT_TIMESTAMP,
      INDEX idx_usuarioid (usuarioid),
      INDEX idx_tabla (tabla),
      INDEX idx_fecha_hora (fecha_hora)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "✅ Table auditorias created.\n";

    // 4. Tabla permisos
    $pdo->exec("CREATE TABLE IF NOT EXISTS permisos (
      id                int(11)       NOT NULL AUTO_INCREMENT PRIMARY KEY,
      rol               varchar(50)   NOT NULL,
      recurso           varchar(100)  NOT NULL,
      accion            enum('crear','leer','actualizar','eliminar') NOT NULL,
      descripcion       text          DEFAULT NULL,
      UNIQUE KEY uq_rol_recurso_accion (rol, recurso, accion),
      INDEX idx_rol (rol)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "✅ Table permisos created.\n";

    // 5. Tabla sesiones
    $pdo->exec("CREATE TABLE IF NOT EXISTS sesiones (
      id                int(11)       NOT NULL AUTO_INCREMENT PRIMARY KEY,
      usuarioid         int(11)       NOT NULL,
      token_jwt         varchar(500)  NOT NULL UNIQUE,
      direccion_ip      varchar(45)   DEFAULT NULL,
      user_agent        text          DEFAULT NULL,
      fecha_inicio      datetime      NOT NULL DEFAULT CURRENT_TIMESTAMP,
      fecha_expiracion  datetime      NOT NULL,
      activa            tinyint(1)    DEFAULT 1,
      fecha_cierre      datetime      DEFAULT NULL,
      razon_cierre      varchar(100)  DEFAULT NULL,
      INDEX idx_usuarioid (usuarioid),
      INDEX idx_token_jwt (token_jwt)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "✅ Table sesiones created.\n";

    // 6. Tabla intentos_fallidos
    $pdo->exec("CREATE TABLE IF NOT EXISTS intentos_fallidos (
      id              int(11)       NOT NULL AUTO_INCREMENT PRIMARY KEY,
      email           varchar(100)  NOT NULL,
      direccion_ip    varchar(45)   DEFAULT NULL,
      fecha_intento   datetime      NOT NULL DEFAULT CURRENT_TIMESTAMP,
      razon           varchar(100)  DEFAULT NULL,
      INDEX idx_email (email),
      INDEX idx_direccion_ip (direccion_ip)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "✅ Table intentos_fallidos created.\n";

    // Initial Permissions Seeding
    $pdo->exec("INSERT IGNORE INTO permisos (rol, recurso, accion, descripcion) VALUES
    ('administrador_general', 'agencias', 'crear', 'Crear nuevas agencias'),
    ('administrador_general', 'agencias', 'leer', 'Ver todas las agencias'),
    ('administrador_general', 'agencias', 'actualizar', 'Editar agencias'),
    ('administrador_general', 'agencias', 'eliminar', 'Eliminar agencias'),
    ('administrador_general', 'usuarios', 'crear', 'Crear usuarios en cualquier agencia'),
    ('administrador_general', 'usuarios', 'leer', 'Ver todos los usuarios'),
    ('administrador_general', 'usuarios', 'actualizar', 'Editar usuarios'),
    ('administrador_general', 'usuarios', 'eliminar', 'Eliminar usuarios'),
    ('administrador_general', 'reportes', 'leer', 'Ver reportes de todas las agencias'),
    ('administrador_general', 'auditorias', 'leer', 'Ver logs de auditoría globales'),
    ('administrador_general', 'pagos', 'leer', 'Ver todos los pagos');");
    echo "✅ Default permissions seeded.\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

<?php
require_once 'config/db.php';

try {
    // 1. Crear tabla configuracion (Key-Value Store)
    $sql = "CREATE TABLE IF NOT EXISTS configuracion (
        id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        clave varchar(50) NOT NULL UNIQUE COMMENT 'Identificador único de la configuración',
        valor text COMMENT 'Valor de la configuración',
        descripcion varchar(255) DEFAULT NULL COMMENT 'Descripción para el usuario',
        grupo enum('general','negocio','sistema') DEFAULT 'general',
        created_at timestamp DEFAULT CURRENT_TIMESTAMP,
        updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $pdo->exec($sql);
    echo "✅ Tabla 'configuracion' creada o verificada.\n";

    // 2. Insertar valores por defecto (Upsert)
    $stmt = $pdo->prepare("INSERT INTO configuracion (clave, valor, descripcion, grupo) VALUES 
        (:clave, :valor, :descripcion, :grupo) 
        ON DUPLICATE KEY UPDATE valor=VALUES(valor), descripcion=VALUES(descripcion), grupo=VALUES(grupo)");

    $settings = [
        // Identidad
        ['clave' => 'site_name', 'valor' => 'Turismo Oxapampa', 'descripcion' => 'Nombre del Sitio', 'grupo' => 'general'],
        ['clave' => 'support_email', 'valor' => 'soporte@turismooxapampa.com', 'descripcion' => 'Email de Soporte', 'grupo' => 'general'],
        ['clave' => 'currency', 'valor' => 'PEN', 'descripcion' => 'Moneda Base del Sistema', 'grupo' => 'general'],

        // Negocio (Reglas)
        ['clave' => 'grace_period_days', 'valor' => '15', 'descripcion' => 'Días de Gracia (Post-Vencimiento)', 'grupo' => 'negocio'],
        ['clave' => 'default_plan_id', 'valor' => '1', 'descripcion' => 'ID Plan por Defecto (Prueba)', 'grupo' => 'negocio'],

        // Sistema
        ['clave' => 'maintenance_mode', 'valor' => '0', 'descripcion' => 'Modo Mantenimiento (1=Activo)', 'grupo' => 'sistema'],
        ['clave' => 'allow_registrations', 'valor' => '1', 'descripcion' => 'Permitir Nuevos Registros', 'grupo' => 'sistema']
    ];

    foreach ($settings as $setting) {
        $stmt->execute($setting);
    }
    echo "✅ Configuraciones por defecto insertadas.\n";

} catch (PDOException $e) {
    die("❌ Error en migración: " . $e->getMessage() . "\n");
}

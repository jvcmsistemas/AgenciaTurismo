<?php
require_once __DIR__ . '/config/db.php';

try {
    // 1. Tabla de Guías
    $pdo->exec("CREATE TABLE IF NOT EXISTS guias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        agencia_id INT NOT NULL,
        nombre VARCHAR(100) NOT NULL,
        dni VARCHAR(20),
        telefono VARCHAR(20),
        email VARCHAR(100),
        idiomas VARCHAR(255) DEFAULT 'Español',
        estado ENUM('activo', 'inactivo') DEFAULT 'activo',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (agencia_id) REFERENCES agencias(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "Tabla 'guias' verificada.<br>";

    // 2. Tabla de Transportes
    $pdo->exec("CREATE TABLE IF NOT EXISTS transportes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        agencia_id INT NOT NULL,
        placa VARCHAR(20) NOT NULL,
        modelo VARCHAR(100),
        capacidad INT NOT NULL DEFAULT 0,
        chofer_nombre VARCHAR(100),
        chofer_telefono VARCHAR(20),
        estado ENUM('activo', 'inactivo', 'mantenimiento') DEFAULT 'activo',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (agencia_id) REFERENCES agencias(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "Tabla 'transportes' verificada.<br>";

    // 3. Tabla de Proveedores
    $pdo->exec("CREATE TABLE IF NOT EXISTS proveedores (
        id INT AUTO_INCREMENT PRIMARY KEY,
        agencia_id INT NOT NULL,
        nombre VARCHAR(100) NOT NULL,
        tipo ENUM('restaurante', 'hotel', 'ticket', 'otro') NOT NULL,
        contacto_nombre VARCHAR(100),
        telefono VARCHAR(20),
        ubicacion VARCHAR(255),
        estado ENUM('activo', 'inactivo') DEFAULT 'activo',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (agencia_id) REFERENCES agencias(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "Tabla 'proveedores' verificada.<br>";

    // 4. Tabla de Salidas
    $pdo->exec("CREATE TABLE IF NOT EXISTS salidas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        agencia_id INT NOT NULL,
        tour_id INT,
        fecha_salida DATE NOT NULL,
        hora_salida TIME NOT NULL,
        tipo ENUM('compartida', 'privada') DEFAULT 'compartida',
        estado ENUM('programada', 'confirmada', 'cerrada', 'cancelada') DEFAULT 'programada',
        capacidad_total INT DEFAULT 0,
        cupos_disponibles INT DEFAULT 0,
        precio_privado DECIMAL(10,2) DEFAULT NULL,
        notas TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (agencia_id) REFERENCES agencias(id) ON DELETE CASCADE,
        FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "Tabla 'salidas' verificada.<br>";

    // 5. Pivotes
    $pdo->exec("CREATE TABLE IF NOT EXISTS salida_guias (
        salida_id INT NOT NULL,
        guia_id INT NOT NULL,
        PRIMARY KEY (salida_id, guia_id),
        FOREIGN KEY (salida_id) REFERENCES salidas(id) ON DELETE CASCADE,
        FOREIGN KEY (guia_id) REFERENCES guias(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS salida_transportes (
        salida_id INT NOT NULL,
        transporte_id INT NOT NULL,
        PRIMARY KEY (salida_id, transporte_id),
        FOREIGN KEY (salida_id) REFERENCES salidas(id) ON DELETE CASCADE,
        FOREIGN KEY (transporte_id) REFERENCES transportes(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "Tablas pivote verificadas.<br>";

    // 6. Modificar Reservas (Agregar salida_id)
    try {
        $pdo->exec("ALTER TABLE reservas ADD COLUMN salida_id INT DEFAULT NULL");
        $pdo->exec("ALTER TABLE reservas ADD CONSTRAINT fk_reservas_salida FOREIGN KEY (salida_id) REFERENCES salidas(id) ON DELETE SET NULL");
        echo "Columna 'salida_id' agregada a 'reservas'.<br>";
    } catch (PDOException $e) {
        // Ignorar si ya existe (código de error específico o mensaje genérico)
        echo "Nota: Columna 'salida_id' ya existía o error menor: " . $e->getMessage() . "<br>";
    }

    echo "<h3>Migración de Módulo de Agencia Completada</h3>";

} catch (PDOException $e) {
    echo "Error Crítico: " . $e->getMessage();
}

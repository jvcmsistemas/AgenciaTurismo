<?php
require_once __DIR__ . '/config/db.php';

function addColumnIfNotExists($pdo, $table, $column, $definition)
{
    try {
        $stmt = $pdo->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (!in_array($column, $columns)) {
            $pdo->exec("ALTER TABLE $table ADD COLUMN $column $definition");
            echo "Columna '$column' agregada a la tabla '$table'.<br>";
        } else {
            echo "Columna '$column' ya existe en la tabla '$table'.<br>";
        }
    } catch (PDOException $e) {
        echo "Error verificando tabla $table: " . $e->getMessage() . "<br>";
    }
}

try {
    echo "<h2>Actualizando Esquema de Base de Datos (Recursos)</h2>";

    // Agregar columna 'estado' a las tablas de recursos
    addColumnIfNotExists($pdo, 'guias', 'estado', "ENUM('activo', 'inactivo') DEFAULT 'activo'");
    addColumnIfNotExists($pdo, 'transportes', 'estado', "ENUM('activo', 'inactivo') DEFAULT 'activo'");
    addColumnIfNotExists($pdo, 'proveedores', 'estado', "ENUM('activo', 'inactivo') DEFAULT 'activo'");

    echo "<h3>Actualización finalizada.</h3>";

} catch (PDOException $e) {
    echo "Error general: " . $e->getMessage();
}

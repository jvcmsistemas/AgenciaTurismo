<?php
require_once __DIR__ . '/config/db.php';

try {
    echo "<h2>Verificando tabla 'transportes'</h2>";

    // 1. Obtener columnas actuales
    $stmt = $pdo->query("DESCRIBE transportes");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "Columnas actuales: " . implode(", ", $columns) . "<br>";

    // 2. Verificar y agregar 'chofer_nombre'
    if (!in_array('chofer_nombre', $columns)) {
        $pdo->exec("ALTER TABLE transportes ADD COLUMN chofer_nombre VARCHAR(100) AFTER capacidad");
        echo "Columna 'chofer_nombre' agregada.<br>";
    } else {
        echo "Columna 'chofer_nombre' ya existe.<br>";
    }

    // 3. Verificar y agregar 'chofer_telefono'
    if (!in_array('chofer_telefono', $columns)) {
        $pdo->exec("ALTER TABLE transportes ADD COLUMN chofer_telefono VARCHAR(20) AFTER chofer_nombre");
        echo "Columna 'chofer_telefono' agregada.<br>";
    } else {
        echo "Columna 'chofer_telefono' ya existe.<br>";
    }

    echo "<h3>Corrección finalizada.</h3>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

<?php
// Sistema_New/models/Setting.php

class Setting
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Obtener todas las configuraciones agrupadas
    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM configuracion ORDER BY grupo, clave");
        return $stmt->fetchAll();
    }

    // Obtener valor simple por clave
    public function get($key)
    {
        $stmt = $this->pdo->prepare("SELECT valor FROM configuracion WHERE clave = :clave LIMIT 1");
        $stmt->execute(['clave' => $key]);
        return $stmt->fetchColumn();
    }

    // Actualizar configuración
    public function update($key, $value)
    {
        $stmt = $this->pdo->prepare("UPDATE configuracion SET valor = :valor WHERE clave = :clave");
        return $stmt->execute(['valor' => $value, 'clave' => $key]);
    }

    // Obtener configuraciones por grupo para la vista
    public function getGrouped()
    {
        $all = $this->getAll();
        $grouped = [];
        foreach ($all as $item) {
            $grouped[$item['grupo']][] = $item;
        }
        return $grouped;
    }
}

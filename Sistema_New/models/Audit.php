<?php
// Sistema_New/models/Audit.php

class Audit
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Obtiene los logs de acceso para una agencia específica.
     */
    public function getAccessLogs($agencyId, $limit = 100)
    {
        $sql = "SELECT la.*, u.nombre, u.apellido, u.rol 
                FROM logs_acceso la 
                JOIN usuarios u ON la.usuario_id = u.id 
                WHERE u.agencia_id = :agencia_id 
                ORDER BY la.fecha_hora DESC 
                LIMIT :limit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':agencia_id', $agencyId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtiene los logs de cambios (auditorías) para una agencia específica.
     */
    public function getActivityLogs($agencyId, $limit = 100)
    {
        $sql = "SELECT a.*, u.nombre as usuario_nombre, u.apellido as usuario_apellido 
                FROM auditoria a 
                LEFT JOIN usuarios u ON a.usuario_id = u.id 
                WHERE u.agencia_id = :agencia_id 
                ORDER BY a.fecha_hora DESC 
                LIMIT :limit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':agencia_id', $agencyId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

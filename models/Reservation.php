<?php
// Sistema_New/models/Reservation.php

class Reservation
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllByAgency($agencyId)
    {
        $sql = "SELECT r.*, 
                       c.nombre as cliente_nombre, c.apellido as cliente_apellido, c.email as cliente_email,
                       (SELECT GROUP_CONCAT(t.nombre SEPARATOR ', ') 
                        FROM reserva_detalles rd 
                        JOIN tours t ON rd.servicio_id = t.id 
                        WHERE rd.reserva_id = r.id AND rd.tipo_servicio = 'tour') as tours_nombres
                FROM reservas r
                LEFT JOIN clientes c ON r.cliente_id = c.id
                WHERE r.agencia_id = :agencia_id
                ORDER BY r.fecha_hora_reserva DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['agencia_id' => $agencyId]);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT r.*, 
                       c.nombre as cliente_nombre, c.apellido as cliente_apellido, c.email as cliente_email, c.telefono as cliente_telefono
                FROM reservas r
                LEFT JOIN clientes c ON r.cliente_id = c.id
                WHERE r.id = :id LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function updateStatus($id, $status, $agencyId)
    {
        $sql = "UPDATE reservas SET estado = :estado WHERE id = :id AND agencia_id = :agencia_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['estado' => $status, 'id' => $id, 'agencia_id' => $agencyId]);
    }
}

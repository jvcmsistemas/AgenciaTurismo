<?php
// Sistema_New/models/Payment.php

class Payment
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Listar todos los pagos (filtros opcionales)
    public function getAll($filters = [])
    {
        $sql = "SELECT p.*, r.codigo_reserva, a.nombre as agencia_nombre 
                FROM pagos p
                JOIN reservas r ON p.reserva_id = r.id
                JOIN agencias a ON r.agencia_id = a.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['start_date'])) {
            $sql .= " AND p.fecha_pago >= :start_date";
            $params['start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $sql .= " AND p.fecha_pago <= :end_date";
            $params['end_date'] = $filters['end_date'];
        }

        $sql .= " ORDER BY p.fecha_pago DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Obtener estadísticas por Métodos de Pago
    public function getStatsByMethod()
    {
        $sql = "SELECT metodo_pago, SUM(monto) as total_monto, COUNT(*) as cantidad
                FROM pagos
                WHERE estado = 'aprobado'
                GROUP BY metodo_pago
                ORDER BY total_monto DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Obtener estadísticas por Agencia
    public function getStatsByAgency()
    {
        $sql = "SELECT a.nombre as agencia, SUM(p.monto) as total_ingresos, COUNT(p.id) as cantidad_transacciones
                FROM pagos p
                JOIN reservas r ON p.reserva_id = r.id
                JOIN agencias a ON r.agencia_id = a.id
                WHERE p.estado = 'aprobado'
                GROUP BY a.id, a.nombre
                ORDER BY total_ingresos DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Registrar un nuevo pago
    public function create($data)
    {
        $sql = "INSERT INTO pagos (reserva_id, monto, metodo_pago, referencia, fecha_pago, estado)
                VALUES (:reserva_id, :monto, :metodo_pago, :referencia, NOW(), :estado)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'reserva_id' => $data['reserva_id'],
            'monto' => $data['monto'],
            'metodo_pago' => $data['metodo_pago'],
            'referencia' => $data['referencia'] ?? null,
            'estado' => $data['estado'] ?? 'aprobado'
        ]);

        return $this->pdo->lastInsertId();
    }
}

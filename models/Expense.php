<?php
// Sistema_New/models/Expense.php

class Expense
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Obtiene todos los gastos de una agencia con filtros.
     */
    public function getAllByAgency($agencyId, $filters = [])
    {
        $sql = "SELECT g.*, s.fecha_salida, t.nombre as tour_nombre 
                FROM gastos g
                LEFT JOIN salidas s ON g.salida_id = s.id
                LEFT JOIN tours t ON s.tour_id = t.id
                WHERE g.agencia_id = :agencia_id";

        $params = ['agencia_id' => $agencyId];

        if (!empty($filters['categoria'])) {
            $sql .= " AND g.categoria = :categoria";
            $params['categoria'] = $filters['categoria'];
        }

        if (!empty($filters['estado'])) {
            $sql .= " AND g.estado = :estado";
            $params['estado'] = $filters['estado'];
        }

        if (!empty($filters['start_date'])) {
            $sql .= " AND g.fecha_gasto >= :start_date";
            $params['start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $sql .= " AND g.fecha_gasto <= :end_date";
            $params['end_date'] = $filters['end_date'];
        }

        $sql .= " ORDER BY g.fecha_gasto DESC, g.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Crea un nuevo registro de gasto.
     */
    public function create($data)
    {
        $sql = "INSERT INTO gastos (agencia_id, salida_id, categoria, beneficiario, monto, fecha_gasto, estado, notas) 
                VALUES (:agencia_id, :salida_id, :categoria, :beneficiario, :monto, :fecha_gasto, :estado, :notas)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'agencia_id' => $data['agencia_id'],
            'salida_id' => $data['salida_id'] ?? null,
            'categoria' => $data['categoria'],
            'beneficiario' => $data['beneficiario'],
            'monto' => $data['monto'],
            'fecha_gasto' => $data['fecha_gasto'],
            'estado' => $data['estado'] ?? 'pendiente',
            'notas' => $data['notas'] ?? null
        ]);
    }

    /**
     * Actualiza el estado de un gasto (Ej: de pendiente a pagado).
     */
    public function updateStatus($id, $agencyId, $status)
    {
        $sql = "UPDATE gastos SET estado = :estado WHERE id = :id AND agencia_id = :agencia_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'estado' => $status,
            'id' => $id,
            'agencia_id' => $agencyId
        ]);
    }

    /**
     * Elimina un gasto.
     */
    public function delete($id, $agencyId)
    {
        $sql = "DELETE FROM gastos WHERE id = :id AND agencia_id = :agencia_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'agencia_id' => $agencyId
        ]);
    }

    /**
     * Obtiene totales por categoría para un periodo dado.
     */
    public function getTotalsByCategory($agencyId, $startDate, $endDate)
    {
        $sql = "SELECT categoria, SUM(monto) as total 
                FROM gastos 
                WHERE agencia_id = :agencia_id 
                AND fecha_gasto BETWEEN :start AND :end
                GROUP BY categoria";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'agencia_id' => $agencyId,
            'start' => $startDate,
            'end' => $endDate
        ]);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    /**
     * Obtiene un gasto por su ID.
     */
    public function find($id, $agencyId)
    {
        $sql = "SELECT * FROM gastos WHERE id = :id AND agencia_id = :agencia_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id, 'agencia_id' => $agencyId]);
        return $stmt->fetch();
    }

    /**
     * Actualiza un gasto existente.
     */
    public function update($id, $agencyId, $data)
    {
        $sql = "UPDATE gastos SET 
                salida_id = :salida_id,
                categoria = :categoria,
                beneficiario = :beneficiario,
                monto = :monto,
                fecha_gasto = :fecha_gasto,
                estado = :estado,
                notas = :notas,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = :id AND agencia_id = :agencia_id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'salida_id' => $data['salida_id'] ?? null,
            'categoria' => $data['categoria'],
            'beneficiario' => $data['beneficiario'],
            'monto' => $data['monto'],
            'fecha_gasto' => $data['fecha_gasto'],
            'estado' => $data['estado'] ?? 'pendiente',
            'notas' => $data['notas'] ?? null,
            'id' => $id,
            'agencia_id' => $agencyId
        ]);
    }
}

<?php
// Sistema_New/models/Payment.php

class Payment
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Listar todos los pagos (filtros opcionales) con paginación y orden
    public function getAll($filters = [], $limit = null, $offset = 0, $orderBy = 'p.fecha_pago', $orderDir = 'DESC')
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

        if (!empty($filters['agencia_id'])) {
            $sql .= " AND r.agencia_id = :agencia_id";
            $params['agencia_id'] = $filters['agencia_id'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (r.codigo_reserva LIKE :search OR p.referencia LIKE :search)";
            $params['search'] = "%" . $filters['search'] . "%";
        }

        // Validar campos de ordenamiento
        $allowedSort = ['p.fecha_pago', 'p.monto', 'r.codigo_reserva', 'p.metodo_pago', 'p.estado'];
        if (!in_array($orderBy, $allowedSort))
            $orderBy = 'p.fecha_pago';
        $orderDir = (strtoupper($orderDir) === 'ASC') ? 'ASC' : 'DESC';

        $sql .= " ORDER BY $orderBy $orderDir";

        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $params['limit'] = (int) $limit;
            $params['offset'] = (int) $offset;
        }

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => &$val) {
            $type = is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindParam($key, $val, $type);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countAll($filters = [])
    {
        $sql = "SELECT COUNT(*) FROM pagos p
                JOIN reservas r ON p.reserva_id = r.id
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

        if (!empty($filters['agencia_id'])) {
            $sql .= " AND r.agencia_id = :agencia_id";
            $params['agencia_id'] = $filters['agencia_id'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (r.codigo_reserva LIKE :search OR p.referencia LIKE :search)";
            $params['search'] = "%" . $filters['search'] . "%";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
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

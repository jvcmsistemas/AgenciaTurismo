<?php

class Departure
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllByAgency($agencyId, $search = '', $status = '', $sort = 'fecha_salida ASC')
    {
        $sql = "SELECT s.*, t.nombre as tour_nombre, t.duracion, t.ubicacion, 
                       g.nombre as guia_nombre, tr.placa as transporte_placa
                FROM salidas s
                JOIN tours t ON s.tour_id = t.id
                LEFT JOIN guias g ON s.guia_id = g.id
                LEFT JOIN transportes tr ON s.transporte_id = tr.id
                WHERE s.agencia_id = :agencia_id";

        $params = ['agencia_id' => $agencyId];

        if (!empty($search)) {
            $sql .= " AND (t.nombre LIKE :search1 OR g.nombre LIKE :search2 OR tr.placa LIKE :search3)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
            $params['search3'] = "%$search%";
        }

        if (!empty($status)) {
            $sql .= " AND s.estado = :status";
            $params['status'] = $status;
        }

        // Whitelist sorting to prevent SQL injection
        $allowedSorts = [
            'fecha_asc' => 's.fecha_salida ASC, s.hora_salida ASC',
            'fecha_desc' => 's.fecha_salida DESC, s.hora_salida DESC',
            'cupos_asc' => 's.cupos_disponibles ASC',
            'cupos_desc' => 's.cupos_disponibles DESC'
        ];

        $orderBy = $allowedSorts[$sort] ?? 's.fecha_salida ASC, s.hora_salida ASC';
        $sql .= " ORDER BY " . $orderBy;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT s.*, t.nombre as tour_nombre 
                FROM salidas s
                JOIN tours t ON s.tour_id = t.id
                WHERE s.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO salidas (agencia_id, tour_id, fecha_salida, hora_salida, guia_id, transporte_id, cupos_totales, cupos_disponibles, precio_actual, estado) 
                VALUES (:agencia_id, :tour_id, :fecha_salida, :hora_salida, :guia_id, :transporte_id, :cupos_totales, :cupos_disponibles, :precio_actual, :estado)";

        $stmt = $this->pdo->prepare($sql);

        // Si no se define precio_actual, usar el del tour (se puede manejar en controlador, pero aquí aseguramos null si no viene)
        $precio = $data['precio_actual'] ?? null;

        $stmt->execute([
            'agencia_id' => $data['agencia_id'],
            'tour_id' => $data['tour_id'],
            'fecha_salida' => $data['fecha_salida'],
            'hora_salida' => $data['hora_salida'], // Add hora_salida
            'guia_id' => $data['guia_id'] ?: null,
            'transporte_id' => $data['transporte_id'] ?: null,
            'cupos_totales' => $data['cupos_totales'],
            'cupos_disponibles' => $data['cupos_totales'], // Al inicio disponibles = totales
            'precio_actual' => $precio,
            'estado' => $data['estado'] ?? 'programada'
        ]);

        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE salidas SET 
                tour_id = :tour_id, 
                fecha_salida = :fecha_salida, 
                hora_salida = :hora_salida,
                guia_id = :guia_id, 
                transporte_id = :transporte_id, 
                cupos_totales = :cupos_totales,
                precio_actual = :precio_actual,
                estado = :estado
                WHERE id = :id AND agencia_id = :agencia_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'tour_id' => $data['tour_id'],
            'fecha_salida' => $data['fecha_salida'],
            'hora_salida' => $data['hora_salida'], // Add hora_salida
            'guia_id' => $data['guia_id'] ?: null,
            'transporte_id' => $data['transporte_id'] ?: null,
            'cupos_totales' => $data['cupos_totales'],
            'precio_actual' => $data['precio_actual'] ?: null,
            'estado' => $data['estado'],
            'id' => $id,
            'agencia_id' => $data['agencia_id']
        ]);
    }

    public function delete($id, $agencyId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM salidas WHERE id = :id AND agencia_id = :agencia_id");
        $stmt->execute(['id' => $id, 'agencia_id' => $agencyId]);
    }

    // Método para actualizar cupos disponibles (usado al reservar)
    public function updateSeats($id, $seatsToDeduct)
    {
        $sql = "UPDATE salidas SET cupos_disponibles = cupos_disponibles - :seats1 
                WHERE id = :id AND cupos_disponibles >= :seats2";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['seats1' => $seatsToDeduct, 'seats2' => $seatsToDeduct, 'id' => $id]);
        return $stmt->rowCount() > 0; // Retorna true si se pudo actualizar (había cupo)
    }

    /**
     * Obtiene las próximas salidas de la agencia (próximas 48 horas)
     */
    public function getUpcoming($agencyId)
    {
        $sql = "SELECT s.*, t.nombre as tour_nombre, g.nombre as guia_nombre
                FROM salidas s
                JOIN tours t ON s.tour_id = t.id
                LEFT JOIN guias g ON s.guia_id = g.id
                WHERE s.agencia_id = :agencia_id 
                AND s.fecha_salida BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 2 DAY)
                AND s.estado != 'cancelada'
                ORDER BY s.fecha_salida ASC, s.hora_salida ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['agencia_id' => $agencyId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

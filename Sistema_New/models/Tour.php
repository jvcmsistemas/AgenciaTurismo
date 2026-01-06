<?php
// Sistema_New/models/Tour.php

class Tour
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllByAgency($agencyId, $search = '', $limit = null, $offset = 0, $orderBy = 'id', $orderDir = 'DESC')
    {
        $sql = "SELECT * FROM tours WHERE agencia_id = :agencia_id";
        $params = ['agencia_id' => $agencyId];

        if (!empty($search)) {
            $sql .= " AND (nombre LIKE :search OR descripcion LIKE :search OR ubicacion LIKE :search OR tags LIKE :search)";
            $params['search'] = "%$search%";
        }

        // Validar campos de ordenamiento
        $allowedSort = ['nombre', 'precio', 'duracion', 'nivel_dificultad', 'ubicacion', 'id'];
        if (!in_array($orderBy, $allowedSort))
            $orderBy = 'id';
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

    public function countAllByAgency($agencyId, $search = '')
    {
        $sql = "SELECT COUNT(*) FROM tours WHERE agencia_id = :agencia_id";
        $params = ['agencia_id' => $agencyId];

        if (!empty($search)) {
            $sql .= " AND (nombre LIKE :search OR descripcion LIKE :search OR ubicacion LIKE :search OR tags LIKE :search)";
            $params['search'] = "%$search%";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tours WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO tours (nombre, descripcion, duracion, precio, agencia_id, tags, nivel_dificultad, ubicacion) 
                VALUES (:nombre, :descripcion, :duracion, :precio, :agencia_id, :tags, :nivel_dificultad, :ubicacion)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'duracion' => $data['duracion'],
            'precio' => $data['precio'],
            'agencia_id' => $data['agencia_id'],
            'tags' => $data['tags'],
            'nivel_dificultad' => $data['nivel_dificultad'],
            'ubicacion' => $data['ubicacion']
        ]);

        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE tours SET 
                nombre = :nombre, 
                descripcion = :descripcion, 
                duracion = :duracion, 
                precio = :precio, 
                tags = :tags, 
                nivel_dificultad = :nivel_dificultad, 
                ubicacion = :ubicacion 
                WHERE id = :id AND agencia_id = :agencia_id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'duracion' => $data['duracion'],
            'precio' => $data['precio'],
            'tags' => $data['tags'],
            'nivel_dificultad' => $data['nivel_dificultad'],
            'ubicacion' => $data['ubicacion'],
            'id' => $id,
            'agencia_id' => $data['agencia_id'] // Security check
        ]);
    }

    public function delete($id, $agencyId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM tours WHERE id = :id AND agencia_id = :agencia_id");
        return $stmt->execute(['id' => $id, 'agencia_id' => $agencyId]);
    }

    /**
     * Obtiene los lugares (ubicaciones) más populares basados en el número de reservas del mes actual
     */
    public function getPopularByAgency($agencyId, $limit = 3)
    {
        $sql = "SELECT t.nombre, COUNT(DISTINCT r.id) as total_reservas 
                FROM tours t 
                LEFT JOIN salidas s ON t.id = s.tour_id 
                LEFT JOIN reserva_detalles rd ON s.id = rd.servicio_id AND rd.tipo_servicio = 'tour'
                LEFT JOIN reservas r ON rd.reserva_id = r.id 
                    AND r.estado IN ('confirmada', 'completada', 'pendiente')
                    AND MONTH(r.fecha_hora_reserva) = MONTH(CURRENT_DATE())
                    AND YEAR(r.fecha_hora_reserva) = YEAR(CURRENT_DATE())
                WHERE t.agencia_id = :agencia_id 
                GROUP BY t.id, t.nombre 
                ORDER BY total_reservas DESC 
                LIMIT :limit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':agencia_id', $agencyId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}

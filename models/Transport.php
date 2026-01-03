<?php
// Sistema_New/models/Transport.php

class Transport
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllByAgency($agencyId, $search = '', $limit = null, $offset = 0, $orderBy = 'placa', $orderDir = 'ASC')
    {
        $sql = "SELECT * FROM transportes WHERE agencia_id = :agencia_id";
        $params = ['agencia_id' => $agencyId];

        if (!empty($search)) {
            $sql .= " AND (placa LIKE :search1 OR modelo LIKE :search2 OR chofer_nombre LIKE :search3)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
            $params['search3'] = "%$search%";
        }

        // Validar campos de ordenamiento
        $allowedSort = ['placa', 'modelo', 'capacidad', 'chofer_nombre', 'estado'];
        if (!in_array($orderBy, $allowedSort))
            $orderBy = 'placa';
        $orderDir = (strtoupper($orderDir) === 'DESC') ? 'DESC' : 'ASC';

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
        $sql = "SELECT COUNT(*) FROM transportes WHERE agencia_id = :agencia_id";
        $params = ['agencia_id' => $agencyId];

        if (!empty($search)) {
            $sql .= " AND (placa LIKE :search1 OR modelo LIKE :search2 OR chofer_nombre LIKE :search3)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
            $params['search3'] = "%$search%";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transportes WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO transportes (agencia_id, placa, modelo, capacidad, chofer_nombre, chofer_telefono) 
                VALUES (:agencia_id, :placa, :modelo, :capacidad, :chofer_nombre, :chofer_telefono)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'agencia_id' => $data['agencia_id'],
            'placa' => $data['placa'],
            'modelo' => $data['modelo'] ?? null,
            'capacidad' => $data['capacidad'],
            'chofer_nombre' => $data['chofer_nombre'] ?? null,
            'chofer_telefono' => $data['chofer_telefono'] ?? null
        ]);

        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE transportes SET 
                placa = :placa, 
                modelo = :modelo, 
                capacidad = :capacidad, 
                chofer_nombre = :chofer_nombre, 
                chofer_telefono = :chofer_telefono,
                estado = :estado
                WHERE id = :id AND agencia_id = :agencia_id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'placa' => $data['placa'],
            'modelo' => $data['modelo'],
            'capacidad' => $data['capacidad'],
            'chofer_nombre' => $data['chofer_nombre'],
            'chofer_telefono' => $data['chofer_telefono'],
            'estado' => $data['estado'] ?? 'activo',
            'id' => $id,
            'agencia_id' => $data['agencia_id']
        ]);
    }

    public function delete($id, $agencyId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM transportes WHERE id = :id AND agencia_id = :agencia_id");
        return $stmt->execute(['id' => $id, 'agencia_id' => $agencyId]);
    }
}

<?php
// Sistema_New/models/Provider.php

class Provider
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllByAgency($agencyId, $search = '', $limit = null, $offset = 0, $orderBy = 'nombre', $orderDir = 'ASC')
    {
        $sql = "SELECT * FROM proveedores WHERE agencia_id = :agencia_id";
        $params = ['agencia_id' => $agencyId];

        if (!empty($search)) {
            $sql .= " AND (nombre LIKE :search1 OR tipo LIKE :search2)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
        }

        // Validar campos de ordenamiento
        $allowedSort = ['nombre', 'tipo', 'estado'];
        if (!in_array($orderBy, $allowedSort))
            $orderBy = 'nombre';
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
        $sql = "SELECT COUNT(*) FROM proveedores WHERE agencia_id = :agencia_id";
        $params = ['agencia_id' => $agencyId];

        if (!empty($search)) {
            $sql .= " AND (nombre LIKE :search1 OR tipo LIKE :search2)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM proveedores WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO proveedores (agencia_id, nombre, tipo, telefono, email, ubicacion, notas) 
                VALUES (:agencia_id, :nombre, :tipo, :telefono, :email, :ubicacion, :notas)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'agencia_id' => $data['agencia_id'],
            'nombre' => $data['nombre'],
            'tipo' => $data['tipo'],
            'telefono' => $data['telefono'] ?? null,
            'email' => $data['email'] ?? null,
            'ubicacion' => $data['direccion'] ?? null,
            'notas' => $data['notas'] ?? null
        ]);

        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE proveedores SET 
                nombre = :nombre, 
                tipo = :tipo, 
                telefono = :telefono, 
                email = :email,
                ubicacion = :ubicacion,
                notas = :notas,
                estado = :estado
                WHERE id = :id AND agencia_id = :agencia_id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nombre' => $data['nombre'],
            'tipo' => $data['tipo'],
            'telefono' => $data['telefono'],
            'email' => $data['email'] ?? null,
            'ubicacion' => $data['direccion'] ?? null,
            'notas' => $data['notas'] ?? null,
            'estado' => $data['estado'] ?? 'activo',
            'id' => $id,
            'agencia_id' => $data['agencia_id']
        ]);
    }

    public function delete($id, $agencyId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM proveedores WHERE id = :id AND agencia_id = :agencia_id");
        return $stmt->execute(['id' => $id, 'agencia_id' => $agencyId]);
    }
}

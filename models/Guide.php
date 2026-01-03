<?php
// Sistema_New/models/Guide.php

class Guide
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllByAgency($agencyId, $search = '', $limit = null, $offset = 0, $orderBy = 'nombre', $orderDir = 'ASC')
    {
        $sql = "SELECT * FROM guias WHERE agencia_id = :agencia_id";
        $params = ['agencia_id' => $agencyId];

        if (!empty($search)) {
            $sql .= " AND (nombre LIKE :search1 OR apellido LIKE :search2 OR dni LIKE :search3 OR email LIKE :search4)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
            $params['search3'] = "%$search%";
            $params['search4'] = "%$search%";
        }

        // Validar campos de ordenamiento para evitar inyección SQL
        $allowedSort = ['nombre', 'apellido', 'dni', 'genero', 'fecha_nacimiento', 'email'];
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

        // Vincular parámetros para LIMIT/OFFSET con el tipo correcto (entero)
        foreach ($params as $key => &$val) {
            $type = is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindParam($key, $val, $type);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countAllByAgency($agencyId, $search = '')
    {
        $sql = "SELECT COUNT(*) FROM guias WHERE agencia_id = :agencia_id";
        $params = ['agencia_id' => $agencyId];

        if (!empty($search)) {
            $sql .= " AND (nombre LIKE :search1 OR apellido LIKE :search2 OR dni LIKE :search3 OR email LIKE :search4)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
            $params['search3'] = "%$search%";
            $params['search4'] = "%$search%";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM guias WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO guias (agencia_id, nombre, apellido, dni, fecha_nacimiento, genero, email, telefono, direccion, ciudad_region, notas) 
                VALUES (:agencia_id, :nombre, :apellido, :dni, :fecha_nacimiento, :genero, :email, :telefono, :direccion, :ciudad_region, :notas)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'agencia_id' => $data['agencia_id'],
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'] ?? null,
            'dni' => $data['dni'] ?? null,
            'fecha_nacimiento' => $data['fecha_nacimiento'] ?? null,
            'genero' => $data['genero'] ?? null,
            'email' => $data['email'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'direccion' => $data['direccion'] ?? null,
            'ciudad_region' => $data['ciudad_region'] ?? null,
            'notas' => $data['notas'] ?? null
        ]);

        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE guias SET 
                nombre = :nombre, 
                apellido = :apellido,
                dni = :dni,
                fecha_nacimiento = :fecha_nacimiento,
                genero = :genero,
                email = :email,
                telefono = :telefono, 
                direccion = :direccion,
                ciudad_region = :ciudad_region,
                notas = :notas,
                estado = :estado
                WHERE id = :id AND agencia_id = :agencia_id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'dni' => $data['dni'],
            'fecha_nacimiento' => $data['fecha_nacimiento'],
            'genero' => $data['genero'],
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'direccion' => $data['direccion'],
            'ciudad_region' => $data['ciudad_region'],
            'notas' => $data['notas'],
            'estado' => $data['estado'] ?? 'activo',
            'id' => $id,
            'agencia_id' => $data['agencia_id']
        ]);
    }

    public function delete($id, $agencyId)
    {
        // Soft delete preferible, pero por ahora delete físico si no hay dependencias
        // O mejor, cambiar estado a inactivo si hay historial
        $stmt = $this->pdo->prepare("DELETE FROM guias WHERE id = :id AND agencia_id = :agencia_id");
        return $stmt->execute(['id' => $id, 'agencia_id' => $agencyId]);
    }
}

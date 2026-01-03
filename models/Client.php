<?php

class Client
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllByAgency($agencyId, $search = '', $limit = null, $offset = 0, $orderBy = 'nombre', $orderDir = 'ASC')
    {
        $sql = "SELECT * FROM clientes WHERE agencia_id = :agencia_id";
        $params = ['agencia_id' => $agencyId];

        if (!empty($search)) {
            $sql .= " AND (nombre LIKE :search1 OR apellido LIKE :search2 OR email LIKE :search3 OR dni LIKE :search4)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
            $params['search3'] = "%$search%";
            $params['search4'] = "%$search%";
        }

        // Validar campos de ordenamiento
        $allowedSort = ['nombre', 'apellido', 'dni', 'email', 'fecha_registro'];
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
        $sql = "SELECT COUNT(*) FROM clientes WHERE agencia_id = :agencia_id";
        $params = ['agencia_id' => $agencyId];

        if (!empty($search)) {
            $sql .= " AND (nombre LIKE :search1 OR apellido LIKE :search2 OR email LIKE :search3 OR dni LIKE :search4)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
            $params['search3'] = "%$search%";
            $params['search4'] = "%$search%";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    /**
     * Obtiene el conteo de nuevos clientes registrados este mes
     */
    public function getMonthlyCount($agencyId, $month, $year)
    {
        $sql = "SELECT COUNT(*) 
                FROM clientes 
                WHERE agencia_id = :aid 
                AND MONTH(created_at) = :month 
                AND YEAR(created_at) = :year";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'aid' => $agencyId,
            'month' => $month,
            'year' => $year
        ]);
        return $stmt->fetchColumn() ?: 0;
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM clientes WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function findByDniOrEmail($value, $agencyId)
    {
        // Busca coincidencias exactas para validación de unicidad
        $sql = "SELECT * FROM clientes WHERE (dni = :dni OR email = :email) AND agencia_id = :agencia_id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['dni' => $value, 'email' => $value, 'agencia_id' => $agencyId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO clientes (nombre, apellido, email, dni, ruc, telefono, nacionalidad, fecha_registro, agencia_id) 
                VALUES (:nombre, :apellido, :email, :dni, :ruc, :telefono, :nacionalidad, CURDATE(), :agencia_id)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'email' => $data['email'],
            'dni' => $data['dni'] ?? null,
            'ruc' => $data['ruc'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'nacionalidad' => $data['nacionalidad'] ?? 'Peruana',
            'agencia_id' => $data['agencia_id']
        ]);

        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE clientes SET 
                nombre = :nombre, 
                apellido = :apellido, 
                email = :email, 
                dni = :dni, 
                ruc = :ruc, 
                telefono = :telefono, 
                nacionalidad = :nacionalidad
                WHERE id = :id AND agencia_id = :agencia_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'email' => $data['email'],
            'dni' => $data['dni'] ?? null,
            'ruc' => $data['ruc'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'nacionalidad' => $data['nacionalidad'] ?? 'Peruana',
            'id' => $id,
            'agencia_id' => $data['agencia_id']
        ]);
    }

    public function delete($id, $agencyId)
    {
        // Validar si tiene reservas antes de eliminar (Integridad Referencial lógica)
        // Por ahora dejamos que la FK de BD maneje el error o lo hacemos aqui?
        // Mejor verificamos basicamenete
        $check = $this->pdo->prepare("SELECT COUNT(*) FROM reservas WHERE cliente_id = :id");
        $check->execute(['id' => $id]);
        if ($check->fetchColumn() > 0) {
            throw new Exception("No se puede eliminar el cliente porque tiene reservas asociadas.");
        }

        $stmt = $this->pdo->prepare("DELETE FROM clientes WHERE id = :id AND agencia_id = :agencia_id");
        $stmt->execute(['id' => $id, 'agencia_id' => $agencyId]);
    }
}

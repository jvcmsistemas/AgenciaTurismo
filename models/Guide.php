<?php
// Sistema_New/models/Guide.php

class Guide
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllByAgency($agencyId, $search = '')
    {
        $sql = "SELECT * FROM guias WHERE agencia_id = :agencia_id";
        $params = ['agencia_id' => $agencyId];

        if (!empty($search)) {
            $sql .= " AND (nombre LIKE :search1 OR dni LIKE :search2 OR email LIKE :search3)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
            $params['search3'] = "%$search%";
        }

        $sql .= " ORDER BY nombre ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM guias WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO guias (agencia_id, nombre, dni, telefono, email, idiomas) 
                VALUES (:agencia_id, :nombre, :dni, :telefono, :email, :idiomas)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'agencia_id' => $data['agencia_id'],
            'nombre' => $data['nombre'],
            'dni' => $data['dni'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'email' => $data['email'] ?? null,
            'idiomas' => $data['idiomas'] ?? 'Español'
        ]);

        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE guias SET 
                nombre = :nombre, 
                dni = :dni, 
                telefono = :telefono, 
                email = :email, 
                idiomas = :idiomas,
                estado = :estado
                WHERE id = :id AND agencia_id = :agencia_id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nombre' => $data['nombre'],
            'dni' => $data['dni'],
            'telefono' => $data['telefono'],
            'email' => $data['email'],
            'idiomas' => $data['idiomas'],
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

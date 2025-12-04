<?php
// Sistema_New/models/Provider.php

class Provider
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllByAgency($agencyId, $search = '')
    {
        $sql = "SELECT * FROM proveedores WHERE agencia_id = :agencia_id";
        $params = ['agencia_id' => $agencyId];

        if (!empty($search)) {
            $sql .= " AND (nombre LIKE :search1 OR tipo LIKE :search2 OR contacto_nombre LIKE :search3)";
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
        $stmt = $this->pdo->prepare("SELECT * FROM proveedores WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO proveedores (agencia_id, nombre, tipo, contacto_nombre, telefono, ubicacion) 
                VALUES (:agencia_id, :nombre, :tipo, :contacto_nombre, :telefono, :ubicacion)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'agencia_id' => $data['agencia_id'],
            'nombre' => $data['nombre'],
            'tipo' => $data['tipo'],
            'contacto_nombre' => $data['contacto_nombre'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'ubicacion' => $data['ubicacion'] ?? null
        ]);

        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE proveedores SET 
                nombre = :nombre, 
                tipo = :tipo, 
                contacto_nombre = :contacto_nombre, 
                telefono = :telefono, 
                ubicacion = :ubicacion,
                estado = :estado
                WHERE id = :id AND agencia_id = :agencia_id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nombre' => $data['nombre'],
            'tipo' => $data['tipo'],
            'contacto_nombre' => $data['contacto_nombre'],
            'telefono' => $data['telefono'],
            'ubicacion' => $data['ubicacion'],
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

<?php
// Sistema_New/models/Tour.php

class Tour
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllByAgency($agencyId, $search = '', $order = 'newest')
    {
        $sql = "SELECT * FROM tours WHERE agencia_id = :agencia_id";
        $params = ['agencia_id' => $agencyId];

        if (!empty($search)) {
            $sql .= " AND (nombre LIKE :search OR descripcion LIKE :search OR ubicacion LIKE :search)";
            $params['search'] = "%$search%";
        }

        switch ($order) {
            case 'name_asc':
                $sql .= " ORDER BY nombre ASC";
                break;
            case 'name_desc':
                $sql .= " ORDER BY nombre DESC";
                break;
            case 'price_asc':
                $sql .= " ORDER BY precio ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY precio DESC";
                break;
            case 'duration_asc':
                $sql .= " ORDER BY duracion ASC";
                break;
            case 'duration_desc':
                $sql .= " ORDER BY duracion DESC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY id DESC";
                break;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
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
}

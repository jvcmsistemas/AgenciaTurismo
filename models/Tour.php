<?php
// Sistema_New/models/Tour.php

class Tour
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllByAgency($agencyId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tours WHERE agencia_id = :agencia_id ORDER BY id DESC");
        $stmt->execute(['agencia_id' => $agencyId]);
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

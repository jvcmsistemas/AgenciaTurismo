<?php
// Sistema_New/models/User.php

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Método para crear usuarios (útil para futuros registros)
    public function create($data)
    {
        $sql = "INSERT INTO usuarios (nombre, apellido, email, contrasena, rol, agencia_id) 
                VALUES (:nombre, :apellido, :email, :contrasena, :rol, :agencia_id)";

        $stmt = $this->pdo->prepare($sql);

        // Hashear contraseña antes de guardar
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt->execute([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'email' => $data['email'],
            'contrasena' => $hashedPassword,
            'rol' => $data['rol'],
            'agencia_id' => $data['agencia_id'] ?? null
        ]);

        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE usuarios SET nombre = :nombre, apellido = :apellido, email = :email";
        $params = [
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'email' => $data['email'],
            'id' => $id
        ];

        // Si se proporciona contraseña, actualizarla también
        if (!empty($data['password'])) {
            $sql .= ", contrasena = :password";
            $params['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    public function getAll()
    {
        $sql = "SELECT u.*, a.nombre as nombre_agencia 
                FROM usuarios u 
                LEFT JOIN agencias a ON u.agencia_id = a.id 
                ORDER BY u.created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}

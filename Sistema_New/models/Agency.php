<?php
// Sistema_New/models/Agency.php

class Agency
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Obtener todas las agencias con filtros y ordenamiento
    public function getAll($search = '', $orderBy = 'fecha_vencimiento ASC')
    {
        $sql = "SELECT a.*, u.nombre as dueno_nombre, u.apellido as dueno_apellido, u.email as dueno_email 
                FROM agencias a 
                LEFT JOIN usuarios u ON a.dueno_id = u.id";

        $params = [];

        if (!empty($search)) {
            $sql .= " WHERE a.nombre LIKE :search OR u.email LIKE :search";
            $params['search'] = "%$search%";
        }

        // Whitelist para evitar inyección SQL en ORDER BY
        $allowedSorts = [
            'id DESC',
            'id ASC',
            'fecha_vencimiento ASC',
            'fecha_vencimiento DESC',
            'nombre ASC',
            'nombre DESC'
        ];

        if (in_array($orderBy, $allowedSorts)) {
            // Manejo especial para fechas nulas: siempre al final si es ASC
            if ($orderBy === 'fecha_vencimiento ASC') {
                $sql .= " ORDER BY CASE WHEN fecha_vencimiento IS NULL THEN 1 ELSE 0 END, fecha_vencimiento ASC";
            } else {
                $sql .= " ORDER BY " . $orderBy;
            }
        } else {
            // Default: Vencimiento más próximo (NULLs al final)
            $sql .= " ORDER BY CASE WHEN fecha_vencimiento IS NULL THEN 1 ELSE 0 END, fecha_vencimiento ASC";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Obtener agencia por ID con datos del dueño
    public function getById($id)
    {
        $sql = "SELECT a.*, u.nombre as dueno_nombre, u.apellido as dueno_apellido, u.email as dueno_email, u.id as dueno_id 
                FROM agencias a 
                LEFT JOIN usuarios u ON a.dueno_id = u.id 
                WHERE a.id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Crear nueva agencia
    public function create($data)
    {
        $sql = "INSERT INTO agencias (nombre, direccion, telefono, email, estado, tipo_suscripcion, fecha_vencimiento, dueno_id) 
                VALUES (:nombre, :direccion, :telefono, :email, 'activa', :tipo_suscripcion, :fecha_vencimiento, :dueno_id)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'nombre' => $data['nombre'],
            'direccion' => $data['direccion'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'email' => $data['email'] ?? null,
            'tipo_suscripcion' => $data['tipo_suscripcion'] ?? 'prueba',
            'fecha_vencimiento' => $data['fecha_vencimiento'] ?? null,
            'dueno_id' => $data['dueno_id']
        ]);

        return $this->pdo->lastInsertId();
    }

    // Actualizar datos de la agencia
    public function update($id, $data)
    {
        $sql = "UPDATE agencias SET 
                nombre = :nombre, 
                direccion = :direccion, 
                telefono = :telefono, 
                email = :email,
                ruc = :ruc,
                pais = :pais,
                ciudad = :ciudad,
                web = :web,
                logo_url = :logo_url,
                descripcion = :descripcion,
                tipo_suscripcion = :tipo_suscripcion,
                fecha_vencimiento = :fecha_vencimiento
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'nombre' => $data['nombre'],
            'direccion' => $data['direccion'],
            'telefono' => $data['telefono'],
            'email' => $data['email'],
            'ruc' => $data['ruc'] ?? null,
            'pais' => $data['pais'] ?? null,
            'ciudad' => $data['ciudad'] ?? null,
            'web' => $data['web'] ?? null,
            'logo_url' => $data['logo_url'] ?? null,
            'descripcion' => $data['descripcion'] ?? null,
            'tipo_suscripcion' => $data['tipo_suscripcion'],
            'fecha_vencimiento' => $data['fecha_vencimiento'],
            'id' => $id
        ]);
    }

    // Actualizar estado y suscripción
    public function updateStatus($id, $estado, $suscripcion = null, $vencimiento = null)
    {
        $sql = "UPDATE agencias SET estado = :estado";
        $params = ['estado' => $estado, 'id' => $id];

        if ($suscripcion) {
            $sql .= ", tipo_suscripcion = :suscripcion";
            $params['suscripcion'] = $suscripcion;
        }
        if ($vencimiento) {
            $sql .= ", fecha_vencimiento = :vencimiento";
            $params['vencimiento'] = $vencimiento;
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}

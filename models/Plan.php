<?php
// Sistema_New/models/Plan.php

class Plan
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM planes ORDER BY orden ASC");
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM planes WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO planes (
            codigo, nombre, descripcion, precio, duracionmeses,
            limiteclientes, limitetours, limiteusuarios, limiteempleados,
            incluye_auditorias, incluye_reportes, incluye_api, incluye_integraciones,
            incluye_soporte_premium, incluye_backup_automatico,
            orden, activo, destacado
        ) VALUES (
            :codigo, :nombre, :descripcion, :precio, :duracionmeses,
            :limiteclientes, :limitetours, :limiteusuarios, :limiteempleados,
            :incluye_auditorias, :incluye_reportes, :incluye_api, :incluye_integraciones,
            :incluye_soporte_premium, :incluye_backup_automatico,
            :orden, :activo, :destacado
        )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'codigo' => $data['codigo'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio'],
            'duracionmeses' => $data['duracionmeses'],
            'limiteclientes' => $data['limiteclientes'] !== '' ? $data['limiteclientes'] : null,
            'limitetours' => $data['limitetours'] !== '' ? $data['limitetours'] : null,
            'limiteusuarios' => $data['limiteusuarios'] !== '' ? $data['limiteusuarios'] : null,
            'limiteempleados' => $data['limiteempleados'] !== '' ? $data['limiteempleados'] : null,
            'incluye_auditorias' => isset($data['incluye_auditorias']) ? 1 : 0,
            'incluye_reportes' => isset($data['incluye_reportes']) ? 1 : 0,
            'incluye_api' => isset($data['incluye_api']) ? 1 : 0,
            'incluye_integraciones' => isset($data['incluye_integraciones']) ? 1 : 0,
            'incluye_soporte_premium' => isset($data['incluye_soporte_premium']) ? 1 : 0,
            'incluye_backup_automatico' => isset($data['incluye_backup_automatico']) ? 1 : 0,
            'orden' => $data['orden'],
            'activo' => isset($data['activo']) ? 1 : 0,
            'destacado' => isset($data['destacado']) ? 1 : 0
        ]);

        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE planes SET 
            codigo = :codigo,
            nombre = :nombre,
            descripcion = :descripcion,
            precio = :precio,
            duracionmeses = :duracionmeses,
            limiteclientes = :limiteclientes,
            limitetours = :limitetours,
            limiteusuarios = :limiteusuarios,
            limiteempleados = :limiteempleados,
            incluye_auditorias = :incluye_auditorias,
            incluye_reportes = :incluye_reportes,
            incluye_api = :incluye_api,
            incluye_integraciones = :incluye_integraciones,
            incluye_soporte_premium = :incluye_soporte_premium,
            incluye_backup_automatico = :incluye_backup_automatico,
            orden = :orden,
            activo = :activo,
            destacado = :destacado
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'codigo' => $data['codigo'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio'],
            'duracionmeses' => $data['duracionmeses'],
            'limiteclientes' => $data['limiteclientes'] !== '' ? $data['limiteclientes'] : null,
            'limitetours' => $data['limitetours'] !== '' ? $data['limitetours'] : null,
            'limiteusuarios' => $data['limiteusuarios'] !== '' ? $data['limiteusuarios'] : null,
            'limiteempleados' => $data['limiteempleados'] !== '' ? $data['limiteempleados'] : null,
            'incluye_auditorias' => isset($data['incluye_auditorias']) ? 1 : 0,
            'incluye_reportes' => isset($data['incluye_reportes']) ? 1 : 0,
            'incluye_api' => isset($data['incluye_api']) ? 1 : 0,
            'incluye_integraciones' => isset($data['incluye_integraciones']) ? 1 : 0,
            'incluye_soporte_premium' => isset($data['incluye_soporte_premium']) ? 1 : 0,
            'incluye_backup_automatico' => isset($data['incluye_backup_automatico']) ? 1 : 0,
            'orden' => $data['orden'],
            'activo' => isset($data['activo']) ? 1 : 0,
            'destacado' => isset($data['destacado']) ? 1 : 0
        ]);
    }

    public function delete($id)
    {
        // Verificar si hay agencias usando este plan
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM agencias WHERE planid = :id");
        $stmt->execute(['id' => $id]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("No se puede eliminar un plan que está siendo utilizado por agencias.");
        }

        $stmt = $this->pdo->prepare("DELETE FROM planes WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}

<?php
// Sistema_New/models/Reservation.php

class Reservation
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllByAgency($agencyId)
    {
        $sql = "SELECT r.*, 
                       c.nombre as cliente_nombre, c.apellido as cliente_apellido, c.email as cliente_email,
                       (SELECT GROUP_CONCAT(t.nombre SEPARATOR ', ') 
                        FROM reserva_detalles rd 
                        JOIN tours t ON rd.servicio_id = t.id 
                        WHERE rd.reserva_id = r.id AND rd.tipo_servicio = 'tour') as tours_nombres
                FROM reservas r
                LEFT JOIN clientes c ON r.cliente_id = c.id
                WHERE r.agencia_id = :agencia_id
                ORDER BY r.fecha_hora_reserva DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['agencia_id' => $agencyId]);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT r.*, 
                       c.nombre as cliente_nombre, c.apellido as cliente_apellido, c.email as cliente_email, c.telefono as cliente_telefono
                FROM reservas r
                LEFT JOIN clientes c ON r.cliente_id = c.id
                WHERE r.id = :id LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        try {
            $this->pdo->beginTransaction();

            // 1. Descontar cupos en la Salida (Departure)
            // Necesitamos instanciar el modelo Departure o ejecutar query directa.
            // Por limpieza, usaremos query directa similar a Departure::updateSeats pero aquí.
            // O mejor, requerimos el archivo si no está cargado.
            require_once BASE_PATH . '/models/Departure.php';
            $departureModel = new Departure($this->pdo);

            $seats = $data['cantidad_personas'];
            $salidaId = $data['salida_id'];

            if (!$departureModel->updateSeats($salidaId, $seats)) {
                throw new Exception("No hay suficientes cupos disponibles para esta salida.");
            }

            // 2. Insertar Reserva
            $sql = "INSERT INTO reservas (codigo_reserva, cliente_id, agencia_id, fecha_hora_reserva, 
                                        fecha_inicio_tour, fecha_fin_tour, estado, cantidad_personas, 
                                        precio_total, saldo_pendiente, notas, origen, salida_id) 
                    VALUES (:codigo, :cliente_id, :agencia_id, NOW(), 
                            :fecha_inicio, :fecha_fin, :estado, :cantidad, 
                            :total, :saldo, :notas, :origen, :salida_id)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'codigo' => $data['codigo_reserva'],
                'cliente_id' => $data['cliente_id'],
                'agencia_id' => $data['agencia_id'],
                'fecha_inicio' => $data['fecha_inicio_tour'],
                'fecha_fin' => $data['fecha_fin_tour'], // Puede ser null o calculada
                'estado' => $data['estado'] ?? 'confirmada', // Al reservar con cupo, suele ser confirmada
                'cantidad' => $seats,
                'total' => $data['precio_total'],
                'saldo' => $data['saldo_pendiente'] ?? 0,
                'notas' => $data['notas'] ?? '',
                'origen' => $data['origen'] ?? 'presencial',
                'salida_id' => $salidaId
            ]);

            $reservaId = $this->pdo->lastInsertId();

            // 3. Insertar Detalles (Para compatibilidad con reportes que usan esta tabla)
            // Asumimos que es un Tour único por la estructura actual
            $sqlDetalle = "INSERT INTO reserva_detalles (reserva_id, tipo_servicio, servicio_id, cantidad, precio_unitario, subtotal) 
                           VALUES (:reserva_id, 'tour', :tour_id, :cantidad, :precio_unit, :subtotal)";

            $stmtDetalle = $this->pdo->prepare($sqlDetalle);
            $stmtDetalle->execute([
                'reserva_id' => $reservaId,
                'tour_id' => $data['tour_id'], // ID del Tour base
                'cantidad' => $seats,
                'precio_unit' => $data['precio_unitario'],
                'subtotal' => $data['precio_total']
            ]);

            $this->pdo->commit();
            return $reservaId;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function updateStatus($id, $status, $agencyId)
    {
        $sql = "UPDATE reservas SET estado = :estado WHERE id = :id AND agencia_id = :agencia_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['estado' => $status, 'id' => $id, 'agencia_id' => $agencyId]);
    }
}

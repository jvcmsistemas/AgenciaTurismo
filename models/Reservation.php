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
                        WHERE rd.reserva_id = r.id AND rd.tipo_servicio = 'tour') as tours_nombres,
                       (SELECT MIN(s.fecha_salida) 
                        FROM reserva_detalles rd 
                        JOIN salidas s ON rd.servicio_id = s.id 
                        WHERE rd.reserva_id = r.id AND rd.tipo_servicio = 'tour') as fecha_inicio_tour
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


    public function getDetails($id)
    {
        $sql = "SELECT rd.*, t.nombre as servicio_nombre, s.fecha_salida, s.hora_salida 
                FROM reserva_detalles rd
                JOIN salidas s ON rd.servicio_id = s.id
                JOIN tours t ON s.tour_id = t.id
                WHERE rd.reserva_id = :id AND rd.tipo_servicio = 'tour'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        try {
            $this->pdo->beginTransaction();

            // 1. Calcular Totales y Validar Cupos
            $totalPrecio = 0;
            $items = $data['items']; // Array de items: ['salida_id', 'cantidad', 'precio_unitario', 'tipo']

            require_once BASE_PATH . '/models/Departure.php';
            $departureModel = new Departure($this->pdo);

            foreach ($items as $item) {
                if ($item['tipo'] == 'tour') {
                    // Validar y desconectar cupos
                    if (!$departureModel->updateSeats($item['salida_id'], $item['cantidad'])) {
                        // Obtener info del tour para mensaje de error
                        $dep = $departureModel->getById($item['salida_id']); // Assuming getById exists or similar
                        throw new Exception("No hay suficientes cupos para la salida seleccionada.");
                    }
                }
                $totalPrecio += ($item['cantidad'] * $item['precio_unitario']);
            }

            // 2. Insertar Reserva (Cabecera)
            $sql = "INSERT INTO reservas (codigo_reserva, cliente_id, agencia_id, fecha_hora_reserva, 
                                        estado, cantidad_personas, precio_total, saldo_pendiente, notas, origen) 
                    VALUES (:codigo, :cliente_id, :agencia_id, NOW(), 
                            :estado, :cantidad_total, 
                            :total, :saldo, :notas, :origen)";

            // Calcular cantidad total de personas (sum of quantities of tours)
            $cantidadTotal = array_reduce($items, function ($carry, $item) {
                return $carry + $item['cantidad'];
            }, 0);

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'codigo' => $data['codigo_reserva'],
                'cliente_id' => $data['cliente_id'],
                'agencia_id' => $data['agencia_id'],
                'estado' => $data['estado'] ?? 'confirmada',
                'cantidad_total' => $cantidadTotal,
                'total' => $totalPrecio,
                'saldo' => $data['saldo_pendiente'] ?? 0,
                'notas' => $data['notas'] ?? '',
                'origen' => $data['origen'] ?? 'presencial'
            ]);

            $reservaId = $this->pdo->lastInsertId();

            // 3. Insertar Detalles
            $sqlDetalle = "INSERT INTO reserva_detalles (reserva_id, tipo_servicio, servicio_id, cantidad, precio_unitario, subtotal) 
                           VALUES (:reserva_id, :tipo, :servicio_id, :cantidad, :precio_unit, :subtotal)";
            $stmtDetalle = $this->pdo->prepare($sqlDetalle);

            foreach ($items as $item) {
                $stmtDetalle->execute([
                    'reserva_id' => $reservaId,
                    'tipo' => $item['tipo'], // 'tour', etc.
                    'servicio_id' => $item['salida_id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unit' => $item['precio_unitario'],
                    'subtotal' => $item['cantidad'] * $item['precio_unitario']
                ]);
            }

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

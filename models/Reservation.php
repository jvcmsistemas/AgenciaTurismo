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
        // Consulta Polimórfica para obtener Tours y Otros Servicios
        $sql = "
            (SELECT rd.*, t.nombre as servicio_nombre, s.fecha_salida, s.hora_salida 
             FROM reserva_detalles rd
             JOIN salidas s ON rd.servicio_id = s.id
             JOIN tours t ON s.tour_id = t.id
             WHERE rd.reserva_id = :id_t AND rd.tipo_servicio = 'tour')
            UNION
            (SELECT rd.*, p.nombre as servicio_nombre, NULL as fecha_salida, NULL as hora_salida
             FROM reserva_detalles rd
             JOIN proveedores p ON rd.servicio_id = p.id
             WHERE rd.reserva_id = :id_p AND rd.tipo_servicio != 'tour')
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_t' => $id, 'id_p' => $id]);
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
                                        estado, cantidad_personas, precio_total, descuento, saldo_pendiente, notas, origen) 
                    VALUES (:codigo, :cliente_id, :agencia_id, NOW(), 
                            :estado, :cantidad_total, 
                            :total, :descuento, :saldo, :notas, :origen)";

            // Calcular cantidad total de personas (sum of quantities of tours)
            $cantidadTotal = array_reduce($items, function ($carry, $item) {
                return $carry + $item['cantidad'];
            }, 0);

            // Lógica de Descuento y Saldo
            $descuento = $data['descuento'] ?? 0;
            $precioFinal = $totalPrecio - $descuento;
            if ($precioFinal < 0)
                $precioFinal = 0;

            $pagoInicial = $data['pago_inicial'] ?? 0;
            $saldoPendiente = $precioFinal - $pagoInicial;
            if ($saldoPendiente < 0)
                $saldoPendiente = 0;

            $estado = ($saldoPendiente <= 0.01) ? 'confirmada' : 'pendiente';

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'codigo' => $data['codigo_reserva'],
                'cliente_id' => $data['cliente_id'],
                'agencia_id' => $data['agencia_id'],
                'estado' => $estado,
                'cantidad_total' => $cantidadTotal,
                'total' => $totalPrecio,
                'descuento' => $descuento,
                'saldo' => $saldoPendiente,
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
    // --- MÓDULO DE PAGOS ---

    public function addPayment($data)
    {
        try {
            $this->pdo->beginTransaction();

            // 1. Insertar el pago
            $sql = "INSERT INTO pagos (reserva_id, agencia_id, monto, metodo_pago, referencia, notas) 
                    VALUES (:reserva_id, :agencia_id, :monto, :metodo_pago, :referencia, :notas)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'reserva_id' => $data['reserva_id'],
                'agencia_id' => $data['agencia_id'],
                'monto' => $data['monto'],
                'metodo_pago' => $data['metodo_pago'],
                'referencia' => $data['referencia'] ?? null,
                'notas' => $data['notas'] ?? null
            ]);

            // 2. Actualizar saldo en la reserva
            // Primero obtenemos el saldo actual y total para asegurarnos
            $stmtRes = $this->pdo->prepare("SELECT precio_total, saldo_pendiente FROM reservas WHERE id = :id FOR UPDATE");
            $stmtRes->execute(['id' => $data['reserva_id']]);
            $reserva = $stmtRes->fetch(PDO::FETCH_ASSOC);

            $nuevoSaldo = $reserva['saldo_pendiente'] - $data['monto'];
            if ($nuevoSaldo < 0)
                $nuevoSaldo = 0; // Evitar negativos por error

            // Determinar nuevo estado
            $nuevoEstado = ($nuevoSaldo <= 0) ? 'confirmada' : 'pendiente'; // O 'pagada' si usas ese estado

            // Actualizar reserva
            $sqlUpdate = "UPDATE reservas SET saldo_pendiente = :saldo, estado = :estado WHERE id = :id";
            $this->pdo->prepare($sqlUpdate)->execute([
                'saldo' => $nuevoSaldo,
                'estado' => $nuevoEstado,
                'id' => $data['reserva_id']
            ]);

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function getPayments($reservaId)
    {
        $sql = "SELECT * FROM pagos WHERE reserva_id = :id ORDER BY fecha_pago DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $reservaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

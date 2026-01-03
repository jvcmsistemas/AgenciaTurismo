<?php
// Sistema_New/models/Reservation.php

class Reservation
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllByAgency($agencyId, $search = '', $limit = null, $offset = 0, $orderBy = 'fecha_hora_reserva', $orderDir = 'DESC')
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
                WHERE r.agencia_id = :agencia_id";

        $params = ['agencia_id' => $agencyId];

        if (!empty($search)) {
            $sql .= " AND (r.codigo_reserva LIKE :search1 OR c.nombre LIKE :search2 OR c.apellido LIKE :search3 OR c.email LIKE :search4)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
            $params['search3'] = "%$search%";
            $params['search4'] = "%$search%";
        }

        // Validar campos de ordenamiento
        $allowedSort = ['fecha_hora_reserva', 'codigo_reserva', 'precio_total', 'estado', 'cantidad_personas'];
        if (!in_array($orderBy, $allowedSort))
            $orderBy = 'fecha_hora_reserva';
        $orderDir = (strtoupper($orderDir) === 'ASC') ? 'ASC' : 'DESC';

        $sql .= " ORDER BY $orderBy $orderDir";

        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $params['limit'] = (int) $limit;
            $params['offset'] = (int) $offset;
        }

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => &$val) {
            $type = is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindParam($key, $val, $type);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countAllByAgency($agencyId, $search = '')
    {
        $sql = "SELECT COUNT(*) FROM reservas r
                LEFT JOIN clientes c ON r.cliente_id = c.id
                WHERE r.agencia_id = :agencia_id";

        $params = ['agencia_id' => $agencyId];

        if (!empty($search)) {
            $sql .= " AND (r.codigo_reserva LIKE :search1 OR c.nombre LIKE :search2 OR c.apellido LIKE :search3 OR c.email LIKE :search4)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
            $params['search3'] = "%$search%";
            $params['search4'] = "%$search%";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
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

    public function update($id, $data)
    {
        try {
            $this->pdo->beginTransaction();

            require_once BASE_PATH . '/models/Departure.php';
            $departureModel = new Departure($this->pdo);

            // 1. Devolver cupos previos
            $oldDetails = $this->getDetails($id);
            foreach ($oldDetails as $detail) {
                if ($detail['tipo_servicio'] === 'tour') {
                    $departureModel->restoreSeats($detail['servicio_id'], $detail['cantidad']);
                }
            }

            // 2. Limpiar detalles antiguos
            $stmtDel = $this->pdo->prepare("DELETE FROM reserva_detalles WHERE reserva_id = ?");
            $stmtDel->execute([$id]);

            // 3. Calcular Totales y Validar Cupos Nuevos
            $totalPrecio = 0;
            $items = $data['items'];

            foreach ($items as $item) {
                if ($item['tipo'] == 'tour') {
                    if (!$departureModel->updateSeats($item['salida_id'], $item['cantidad'])) {
                        throw new Exception("No hay suficientes cupos para la nueva salida seleccionada.");
                    }
                }
                $totalPrecio += ($item['cantidad'] * $item['precio_unitario']);
            }

            // 4. Actualizar Cabecera
            $descuento = $data['descuento'] ?? 0;
            $precioFinal = $totalPrecio - $descuento;

            // Recalcular saldo pendiente basado en pagos realizados
            $stmtPagos = $this->pdo->prepare("SELECT SUM(monto) FROM pagos WHERE reserva_id = ?");
            $stmtPagos->execute([$id]);
            $totalPagado = $stmtPagos->fetchColumn() ?: 0;

            $saldoPendiente = $precioFinal - $totalPagado;
            if ($saldoPendiente < 0)
                $saldoPendiente = 0;

            $estado = ($saldoPendiente <= 0.01) ? 'confirmada' : 'pendiente';

            $cantidadTotal = array_reduce($items, function ($carry, $item) {
                return $carry + $item['cantidad'];
            }, 0);

            $sql = "UPDATE reservas SET 
                        cliente_id = :cliente_id,
                        estado = :estado,
                        cantidad_personas = :cantidad_total,
                        precio_total = :total,
                        descuento = :descuento,
                        saldo_pendiente = :saldo,
                        notas = :notas,
                        updated_at = NOW()
                    WHERE id = :id AND agencia_id = :agencia_id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'cliente_id' => $data['cliente_id'],
                'estado' => $estado,
                'cantidad_total' => $cantidadTotal,
                'total' => $totalPrecio,
                'descuento' => $descuento,
                'saldo' => $saldoPendiente,
                'notas' => $data['notas'] ?? '',
                'id' => $id,
                'agencia_id' => $data['agencia_id']
            ]);

            // 5. Insertar Nuevos Detalles
            $sqlDetalle = "INSERT INTO reserva_detalles (reserva_id, tipo_servicio, servicio_id, cantidad, precio_unitario, subtotal) 
                           VALUES (:reserva_id, :tipo, :servicio_id, :cantidad, :precio_unit, :subtotal)";
            $stmtDetalle = $this->pdo->prepare($sqlDetalle);

            foreach ($items as $item) {
                $stmtDetalle->execute([
                    'reserva_id' => $id,
                    'tipo' => $item['tipo'],
                    'servicio_id' => $item['salida_id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unit' => $item['precio_unitario'],
                    'subtotal' => $item['cantidad'] * $item['precio_unitario']
                ]);
            }

            $this->pdo->commit();
            return true;

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
        $sql = "SELECT * FROM pagos WHERE reserva_id = :id AND deleted_at IS NULL ORDER BY fecha_pago DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $reservaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deletePayment($paymentId)
    {
        try {
            $this->pdo->beginTransaction();

            // 1. Obtener datos del pago antes de borrar
            $stmtPay = $this->pdo->prepare("SELECT * FROM pagos WHERE id = ?");
            $stmtPay->execute([$paymentId]);
            $payment = $stmtPay->fetch(PDO::FETCH_ASSOC);

            if (!$payment)
                throw new Exception("Pago no encontrado.");

            // 2. Eliminar físicamente el pago
            $stmtDel = $this->pdo->prepare("DELETE FROM pagos WHERE id = ?");
            $stmtDel->execute([$paymentId]);

            // 3. Devolver el monto al saldo_pendiente de la reserva
            $stmtRes = $this->pdo->prepare("SELECT saldo_pendiente FROM reservas WHERE id = ? FOR UPDATE");
            $stmtRes->execute([$payment['reserva_id']]);
            $reserva = $stmtRes->fetch(PDO::FETCH_ASSOC);

            $nuevoSaldo = $reserva['saldo_pendiente'] + $payment['monto'];

            // Determinar nuevo estado (si vuelve a tener saldo, vuelve a 'pendiente')
            $nuevoEstado = ($nuevoSaldo > 0) ? 'pendiente' : 'confirmada';

            $sqlUpdate = "UPDATE reservas SET saldo_pendiente = :saldo, estado = :estado WHERE id = :id";
            $this->pdo->prepare($sqlUpdate)->execute([
                'saldo' => $nuevoSaldo,
                'estado' => $nuevoEstado,
                'id' => $payment['reserva_id']
            ]);

            $this->pdo->commit();
            return $payment['reserva_id'];

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Obtiene alertas de reservas con pago pendiente que inician pronto (próximos 7 días)
     */
    public function getPendingAlerts($agencyId)
    {
        $sql = "SELECT r.*, c.nombre as cliente_nombre, c.apellido as cliente_apellido,
                       (SELECT MIN(s.fecha_salida) 
                        FROM reserva_detalles rd 
                        JOIN salidas s ON rd.servicio_id = s.id 
                        WHERE rd.reserva_id = r.id AND rd.tipo_servicio = 'tour') as fecha_inicio_tour
                FROM reservas r
                LEFT JOIN clientes c ON r.cliente_id = c.id
                WHERE r.agencia_id = :agencia_id 
                AND r.saldo_pendiente > 0 
                AND r.estado != 'cancelada'
                AND EXISTS (
                    SELECT 1 FROM reserva_detalles rd2 
                    JOIN salidas s2 ON rd2.servicio_id = s2.id 
                    WHERE rd2.reserva_id = r.id 
                    AND rd2.tipo_servicio = 'tour'
                    AND s2.fecha_salida BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                )
                ORDER BY fecha_inicio_tour ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['agencia_id' => $agencyId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene los ingresos totales del mes actual para una agencia
     */
    public function getMonthlyRevenue($agencyId, $month, $year)
    {
        $sql = "SELECT SUM(precio_total) 
                FROM reservas 
                WHERE agencia_id = :aid 
                AND (estado = 'confirmada' OR estado = 'completada')
                AND MONTH(fecha_hora_reserva) = :month 
                AND YEAR(fecha_hora_reserva) = :year";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'aid' => $agencyId,
            'month' => $month,
            'year' => $year
        ]);
        return $stmt->fetchColumn() ?: 0;
    }
}

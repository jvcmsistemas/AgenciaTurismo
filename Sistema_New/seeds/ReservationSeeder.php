<?php
// seeds/ReservationSeeder.php

class ReservationSeeder
{
    private $pdo;
    private $agencyId;

    public function __construct($pdo, $agencyId)
    {
        $this->pdo = $pdo;
        $this->agencyId = $agencyId;
    }

    public function run()
    {
        // 1. Obtener datos necesarios dinámicamente
        $stmtTour = $this->pdo->prepare("SELECT id, precio FROM tours WHERE agencia_id = ? LIMIT 1");
        $stmtTour->execute([$this->agencyId]);
        $tour = $stmtTour->fetch(PDO::FETCH_ASSOC);

        $stmtClient = $this->pdo->prepare("SELECT id FROM clientes WHERE agencia_id = ? LIMIT 1");
        $stmtClient->execute([$this->agencyId]);
        $client = $stmtClient->fetch(PDO::FETCH_ASSOC);

        $stmtUser = $this->pdo->prepare("SELECT id FROM usuarios WHERE agencia_id = ? LIMIT 1");
        $stmtUser->execute([$this->agencyId]);
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if (!$tour || !$client || !$user) {
            echo "⚠️ No se pudo crear la reserva: faltan tours, clientes o usuarios para la agencia $this->agencyId\n";
            return;
        }

        $tourId = $tour['id'];
        $clientId = $client['id'];
        $userId = $user['id'];
        $price = $tour['precio'];
        $numPeople = 2;
        $total = $price * $numPeople;

        // 2. Crear una Salida (Departure)
        $fechaSalida = date('Y-m-d', strtotime('+7 days'));
        $stmtSalida = $this->pdo->prepare("INSERT INTO salidas (agencia_id, tour_id, fecha_salida, hora_salida, estado, cupos_totales, cupos_disponibles, precio_actual) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtSalida->execute([
            $this->agencyId,
            $tourId,
            $fechaSalida,
            '08:30:00',
            'confirmada',
            20,
            18,
            $price
        ]);
        $salidaId = $this->pdo->lastInsertId();

        // 3. Crear la Reserva
        $codigoReserva = 'RES-OXA-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $stmtReserva = $this->pdo->prepare("INSERT INTO reservas (codigo_reserva, agencia_id, cliente_id, fecha_hora_reserva, fecha_inicio_tour, fecha_fin_tour, estado, cantidad_personas, precio_total, saldo_pendiente, origen, salida_id, asignado_a_usuario) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $fechaFin = date('Y-m-d', strtotime($fechaSalida . ' + 0 days')); // Tour de 1 día

        $stmtReserva->execute([
            $codigoReserva,
            $this->agencyId,
            $clientId,
            $fechaSalida . ' 08:30:00',
            $fechaFin . ' 17:30:00',
            'confirmada',
            $numPeople,
            $total,
            0.00, // Saldo pendiente 0 si está pagado
            'presencial',
            $salidaId,
            $userId
        ]);
        $reservaId = $this->pdo->lastInsertId();

        // 4. Crear Detalles de la Reserva
        $stmtDetalle = $this->pdo->prepare("INSERT INTO reserva_detalles (reserva_id, tipo_servicio, servicio_id, fecha_servicio, cantidad, precio_unitario, subtotal) VALUES (?, 'tour', ?, ?, ?, ?, ?)");
        $stmtDetalle->execute([
            $reservaId,
            $tourId,
            $fechaSalida . ' 08:30:00',
            $numPeople,
            $price,
            $total
        ]);

        echo "✅ Reserva de ejemplo creada: $codigoReserva para Agency ID: $this->agencyId\n";
    }
}

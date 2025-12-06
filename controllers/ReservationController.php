<?php
// Sistema_New/controllers/ReservationController.php

require_once BASE_PATH . '/models/Reservation.php';

class ReservationController
{
    private $pdo;
    private $reservationModel;

    public function __construct($pdo)
    {
        // Verificar acceso (Dueño o Empleado)
        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['dueno_agencia', 'empleado_agencia'])) {
            redirect('login');
        }

        $this->pdo = $pdo;
        $this->reservationModel = new Reservation($pdo);
    }

    public function index()
    {
        $agencyId = $_SESSION['agencia_id'];
        $reservations = $this->reservationModel->getAllByAgency($agencyId);
        require_once BASE_PATH . '/views/agency/reservations/index.php';
    }

    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $status = $_POST['status'];
            $agencyId = $_SESSION['agencia_id']; // Fixed typo from 'agency_id'

            $this->reservationModel->updateStatus($id, $status, $agencyId);
            redirect('agency/reservations');
        }
    }

    public function show()
    {
        if (!isset($_GET['id'])) {
            redirect('agency/reservations');
        }

        $id = $_GET['id'];
        $reservation = $this->reservationModel->getById($id);

        if (!$reservation || $reservation['agencia_id'] != $_SESSION['agencia_id']) {
            redirect('agency/reservations');
        }

        $details = $this->reservationModel->getDetails($id);

        // Obtener datos de la agencia para la factura
        require_once BASE_PATH . '/models/Agency.php';
        $agencyModel = new Agency($this->pdo);
        $agency = $agencyModel->getById($_SESSION['agencia_id']);

        require_once BASE_PATH . '/views/agency/reservations/show.php';
    }

    public function create()
    {
        require_once BASE_PATH . '/models/Tour.php';
        $tourModel = new Tour($this->pdo);
        $tours = $tourModel->getAllByAgency($_SESSION['agencia_id']);

        require_once BASE_PATH . '/views/agency/reservations/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // 1. Gestión del Cliente (Buscar o Crear)
                $clienteId = $_POST['cliente_id'] ?? null;

                if (!$clienteId) {
                    require_once BASE_PATH . '/models/Client.php';
                    // Creación rápida básica si no existe ID
                    $stmt = $this->pdo->prepare("INSERT INTO clientes (agencia_id, nombre, apellido, email, telefono, fecha_registro) VALUES (?, ?, ?, ?, ?, CURDATE())");
                    $stmt->execute([
                        $_SESSION['agencia_id'],
                        $_POST['cliente_nombre'],
                        $_POST['cliente_apellido'],
                        $_POST['cliente_email'],
                        $_POST['cliente_telefono']
                    ]);
                    $clienteId = $this->pdo->lastInsertId();
                }

                // 2. Procesar Items del Formulario
                $items = [];
                $salidas = $_POST['salidas'] ?? [];
                $cantidades = $_POST['cantidades'] ?? [];
                $precios = $_POST['precios'] ?? [];

                // Validar que son arrays y tienen la misma longitud
                if (is_array($salidas)) {
                    for ($i = 0; $i < count($salidas); $i++) {
                        if (!empty($salidas[$i])) {
                            $items[] = [
                                'tipo' => 'tour',
                                'salida_id' => $salidas[$i],
                                'cantidad' => $cantidades[$i] ?? 1,
                                'precio_unitario' => $precios[$i] ?? 0
                            ];
                        }
                    }
                }

                if (empty($items)) {
                    throw new Exception("Debes agregar al menos un servicio a la reserva.");
                }

                // 3. Preparar datos de Reserva
                $data = [
                    'codigo_reserva' => 'RES-' . strtoupper(uniqid()),
                    'cliente_id' => $clienteId,
                    'agencia_id' => $_SESSION['agencia_id'],
                    'items' => $items,
                    'estado' => 'confirmada',
                    'saldo_pendiente' => 0,
                    'notas' => $_POST['notas'] ?? '',
                    'origen' => 'presencial'
                ];

                $this->reservationModel->create($data);

                redirect('agency/reservations?success=created');

            } catch (Exception $e) {
                // En producción: redirect con mensaje de error
                // Por ahora mostramos el error para depurar si falla
                die("Error al crear reserva: " . $e->getMessage());
            }
        }
    }

    // Método AJAX para obtener salidas de un tour
    public function getDepartures()
    {
        if (!isset($_GET['tour_id'])) {
            echo json_encode([]);
            return;
        }

        $tourId = $_GET['tour_id'];
        $agencyId = $_SESSION['agencia_id'];

        $stmt = $this->pdo->prepare("SELECT * FROM salidas WHERE tour_id = :tour_id AND agencia_id = :agencia_id AND estado = 'programada' AND cupos_disponibles > 0 ORDER BY fecha_salida ASC");
        $stmt->execute(['tour_id' => $tourId, 'agencia_id' => $agencyId]);
        $departures = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($departures);
    }
}

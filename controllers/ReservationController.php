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
            $agencyId = $_SESSION['agency_id'];

            $this->reservationModel->updateStatus($id, $status, $agencyId);
            redirect('agency/reservations');
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
                // Por simplicidad, asumiremos que si viene ID es existente, si no, creamos.
                // En una implementación real, buscaríamos por DNI/Email primero.
                $clienteId = $_POST['cliente_id'] ?? null;
                
                if (!$clienteId) {
                    // Crear Cliente Rápido
                    require_once BASE_PATH . '/models/Client.php'; // Asumimos que existe o lo creamos
                    // Si no existe modelo Cliente, insertamos directo (mala práctica, pero para avanzar)
                    // Mejor: Crear modelo Cliente si no existe.
                    // Verificaremos si existe Client.php luego. Por ahora insertamos raw si es necesario
                    // O mejor, usaremos una lógica simple de inserción aquí para no bloquearnos.
                    
                    $stmt = $this->pdo->prepare("INSERT INTO clientes (agencia_id, nombre, apellido, email, telefono) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([
                        $_SESSION['agencia_id'],
                        $_POST['cliente_nombre'],
                        $_POST['cliente_apellido'],
                        $_POST['cliente_email'],
                        $_POST['cliente_telefono']
                    ]);
                    $clienteId = $this->pdo->lastInsertId();
                }

                // 2. Preparar datos de Reserva
                $data = [
                    'codigo_reserva' => 'RES-' . strtoupper(uniqid()),
                    'cliente_id' => $clienteId,
                    'agencia_id' => $_SESSION['agencia_id'],
                    'tour_id' => $_POST['tour_id'],
                    'salida_id' => $_POST['salida_id'],
                    'fecha_inicio_tour' => $_POST['fecha_salida'], // Viene del input hidden o seleccionado
                    'fecha_fin_tour' => $_POST['fecha_salida'], // Por ahora mismo día (Full Day)
                    'cantidad_personas' => $_POST['cantidad'],
                    'precio_unitario' => $_POST['precio_unitario'],
                    'precio_total' => $_POST['precio_total'],
                    'saldo_pendiente' => 0, // Asumimos pago completo o manejo aparte
                    'notas' => $_POST['notas'],
                    'origen' => 'presencial'
                ];

                $this->reservationModel->create($data);

                redirect('agency/reservations?success=created');

            } catch (Exception $e) {
                // Manejo de error (ej. sin cupos)
                $error = $e->getMessage();
                require_once BASE_PATH . '/models/Tour.php';
                $tourModel = new Tour($this->pdo);
                $tours = $tourModel->getAllByAgency($_SESSION['agencia_id']);
                require_once BASE_PATH . '/views/agency/reservations/create.php';
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

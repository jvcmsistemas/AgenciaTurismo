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
        $search = $_GET['search'] ?? '';

        // Parámetros de paginación y orden
        $limit = 10;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $sort = $_GET['sort'] ?? 'fecha_hora_reserva';
        $order = $_GET['order'] ?? 'DESC';

        $reservations = $this->reservationModel->getAllByAgency($agencyId, $search, $limit, $offset, $sort, $order);
        $totalReservations = $this->reservationModel->countAllByAgency($agencyId, $search);
        $totalPages = ceil($totalReservations / $limit);

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

        // Obtener historial de pagos
        $payments = $this->reservationModel->getPayments($id);

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
                // 1. Gestión del Cliente (Buscar o Crear)
                $clienteId = $_POST['cliente_id'] ?? null;

                if (empty($clienteId)) {
                    // Si no hay ID, verificar si hay datos para crear uno nuevo
                    if (empty($_POST['cliente_nombre'])) {
                        throw new Exception("Debes seleccionar un cliente válido.");
                    }

                    // Si hay nombre, creamos (Lógica legacy o para futuro)
                    require_once BASE_PATH . '/models/Client.php';
                    $stmt = $this->pdo->prepare("INSERT INTO clientes (agencia_id, nombre, apellido, email, telefono, fecha_registro) VALUES (?, ?, ?, ?, ?, CURDATE())");
                    $stmt->execute([
                        $_SESSION['agencia_id'],
                        $_POST['cliente_nombre'] ?? 'Sin Nombre',
                        $_POST['cliente_apellido'] ?? '',
                        $_POST['cliente_email'] ?? '',
                        $_POST['cliente_telefono'] ?? ''
                    ]);
                    $clienteId = $this->pdo->lastInsertId();
                }

                // 2. Procesar Items del Formulario
                $items = [];
                $tipos = $_POST['tipos'] ?? [];
                $servicios = $_POST['servicios'] ?? []; // Provider ID en caso de Hotel/Rest, Tour ID en caso de Tour (pero Tour ID no se usa directo en detalles)
                $detalles = $_POST['detalles'] ?? [];   // Departure ID en caso de Tour
                $cantidades = $_POST['cantidades'] ?? [];
                $precios = $_POST['precios'] ?? [];

                // Validar
                if (is_array($tipos)) {
                    for ($i = 0; $i < count($tipos); $i++) {
                        $tipo = $tipos[$i];
                        $servicioId = 0;

                        if ($tipo === 'tour') {
                            $servicioId = $detalles[$i] ?? 0; // Para tours, el ID clave es la salida
                        } else {
                            $servicioId = $servicios[$i] ?? 0; // Para otros, es el proveedor
                        }

                        if ($servicioId > 0) {
                            $items[] = [
                                'tipo' => $tipo,
                                'salida_id' => $servicioId, // Usamos 'salida_id' genéricamente para pasar al modelo (que luego lo mapea a servicio_id)
                                'cantidad' => $cantidades[$i] ?? 1,
                                'precio_unitario' => $precios[$i] ?? 0
                            ];
                        }
                    }
                }

                if (empty($items)) {
                    throw new Exception("Debes agregar al menos un servicio válido a la reserva.");
                }

                // 3. Preparar datos de Reserva
                $data = [
                    'codigo_reserva' => 'RES-' . strtoupper(uniqid()),
                    'cliente_id' => $clienteId,
                    'agencia_id' => $_SESSION['agencia_id'],
                    'items' => $items,
                    'notas' => $_POST['notas'] ?? '',
                    'origen' => 'presencial',
                    'descuento' => $_POST['descuento'] ?? 0,
                    // Datos de Pago Inicial
                    'pago_inicial' => $_POST['pago_inicial'] ?? 0,
                    'metodo_pago' => $_POST['metodo_pago'] ?? 'efectivo',
                    'referencia_pago' => $_POST['referencia'] ?? null
                ];

                $reservaId = $this->reservationModel->create($data); // Ahora devuelve ID

                redirect('agency/reservations/show?id=' . $reservaId . '&success=created');

            } catch (Exception $e) {
                redirect('agency/reservations/create?error=' . urlencode($e->getMessage()));
            }
        }
    }

    public function edit()
    {
        if (!isset($_GET['id'])) {
            redirect('agency/reservations');
        }

        $id = $_GET['id'];
        $agencyId = $_SESSION['agencia_id'];
        $reservation = $this->reservationModel->getById($id);

        if (!$reservation || $reservation['agencia_id'] != $agencyId) {
            redirect('agency/reservations');
        }

        $details = $this->reservationModel->getDetails($id);

        require_once BASE_PATH . '/models/Tour.php';
        $tourModel = new Tour($this->pdo);
        $tours = $tourModel->getAllByAgency($agencyId);

        require_once BASE_PATH . '/views/agency/reservations/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id'];
                $agencyId = $_SESSION['agencia_id'];

                // Validar pertenencia
                $reservation = $this->reservationModel->getById($id);
                if (!$reservation || $reservation['agencia_id'] != $agencyId) {
                    throw new Exception("No tienes permiso para editar esta reserva.");
                }

                // 1. Gestión del Cliente
                $clienteId = $_POST['cliente_id'] ?? null;
                if (empty($clienteId)) {
                    throw new Exception("Debes seleccionar un cliente válido.");
                }

                // 2. Procesar Items del Formulario
                $items = [];
                $tipos = $_POST['tipos'] ?? [];
                $servicios = $_POST['servicios'] ?? [];
                $detalles = $_POST['detalles'] ?? [];
                $cantidades = $_POST['cantidades'] ?? [];
                $precios = $_POST['precios'] ?? [];

                if (is_array($tipos)) {
                    for ($i = 0; $i < count($tipos); $i++) {
                        $tipo = $tipos[$i];
                        $servicioId = ($tipo === 'tour') ? ($detalles[$i] ?? 0) : ($servicios[$i] ?? 0);

                        if ($servicioId > 0) {
                            $items[] = [
                                'tipo' => $tipo,
                                'salida_id' => $servicioId,
                                'cantidad' => $cantidades[$i] ?? 1,
                                'precio_unitario' => $precios[$i] ?? 0
                            ];
                        }
                    }
                }

                if (empty($items)) {
                    throw new Exception("Debes agregar al menos un servicio válido.");
                }

                $data = [
                    'cliente_id' => $clienteId,
                    'agencia_id' => $agencyId,
                    'items' => $items,
                    'notas' => $_POST['notas'] ?? '',
                    'descuento' => $_POST['descuento'] ?? 0
                ];

                $this->reservationModel->update($id, $data);

                redirect('agency/reservations/show?id=' . $id . '&success=updated');

            } catch (Exception $e) {
                redirect('agency/reservations/edit?id=' . ($_POST['id'] ?? '') . '&error=' . urlencode($e->getMessage()));
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

    // --- MÓDULO DE PAGOS ---
    public function addPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'reserva_id' => $_POST['reserva_id'],
                    'agencia_id' => $_SESSION['agencia_id'],
                    'monto' => $_POST['monto'],
                    'metodo_pago' => $_POST['metodo_pago'],
                    'referencia' => $_POST['referencia'],
                    'notas' => $_POST['notas']
                ];

                $this->reservationModel->addPayment($data);

                redirect('agency/reservations/show?id=' . $_POST['reserva_id'] . '&success=payment_added');

            } catch (Exception $e) {
                // En un caso real, manejaríamos mejor el error (flash message)
                redirect('agency/reservations/show?id=' . $_POST['reserva_id'] . '&error=' . urlencode($e->getMessage()));
            }
        }
    }

    public function deletePayment()
    {
        try {
            $id = $_GET['id'] ?? null;
            if (!$id)
                throw new Exception("ID de pago no válido.");

            $reservaId = $this->reservationModel->deletePayment($id);

            redirect('agency/reservations/show?id=' . $reservaId . '&success=payment_deleted');

        } catch (Exception $e) {
            redirect('agency/reservations?error=' . urlencode($e->getMessage()));
        }
    }
}

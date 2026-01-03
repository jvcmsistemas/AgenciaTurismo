<?php
// Sistema_New/controllers/AgencyController.php

require_once BASE_PATH . '/models/Tour.php';
require_once BASE_PATH . '/models/Reservation.php';
require_once BASE_PATH . '/models/Agency.php';

class AgencyController
{
    private $pdo;
    private $tourModel;
    private $reservationModel;

    public function __construct($pdo)
    {
        // Verificar acceso
        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['dueno_agencia', 'empleado_agencia'])) {
            redirect('login');
        }

        $this->pdo = $pdo;
        $this->tourModel = new Tour($pdo);
        $this->reservationModel = new Reservation($pdo);
    }

    public function index()
    {
        if (!isset($_SESSION['agencia_id'])) {
            // Fallback or error handling if agency_id is missing
            $agencyId = null;
            // You might want to fetch it from DB based on user_id if not in session
            // For now, redirect to login or show error
            redirect('login');
        }
        $agencyId = $_SESSION['agencia_id'];

        // Asegurar que el nombre de la agencia esté en sesión para mostrarlo en el dashboard
        if (!isset($_SESSION['agencia_nombre'])) {
            $agencyModel = new Agency($this->pdo);
            $agency = $agencyModel->getById($agencyId);
            $_SESSION['agencia_nombre'] = $agency['nombre'] ?? 'Mi Agencia';
        }

        // Fetch KPIs
        $tours = $this->tourModel->getAllByAgency($agencyId);
        $reservations = $this->reservationModel->getAllByAgency($agencyId);

        $totalTours = count($tours);
        $totalReservations = count($reservations);

        // --- NEW KPIs ---
        $currentMonth = date('m');
        $currentYear = date('Y');

        $monthlyRevenue = 0;
        if ($_SESSION['user_role'] === 'dueno_agencia') {
            $monthlyRevenue = $this->reservationModel->getMonthlyRevenue($agencyId, $currentMonth, $currentYear);
        }

        require_once BASE_PATH . '/models/Client.php';
        $clientModel = new Client($this->pdo);
        $monthlyNewClients = $clientModel->getMonthlyCount($agencyId, $currentMonth, $currentYear);

        require_once BASE_PATH . '/models/Ticket.php';
        $ticketModel = new Ticket($this->pdo);
        $pendingRepliesCount = $ticketModel->getPendingRepliesCount($agencyId);

        // Fetch Excellent Metrics (Top Guide & Transport)
        require_once BASE_PATH . '/models/Guide.php';
        require_once BASE_PATH . '/models/Transport.php';
        $guideModel = new Guide($this->pdo);
        $transportModel = new Transport($this->pdo);

        $topGuide = $guideModel->getTopActive($agencyId);
        $topTransport = $transportModel->getTopUsed($agencyId);

        // Fetch Popular Rankings
        $popularTours = $this->tourModel->getPopularByAgency($agencyId, 3);
        $topGuidesRanking = $guideModel->getMonthlyRanking($agencyId, 3);

        // Calculate total historical revenue (Only if Owner)
        $totalRevenue = 0;
        if ($_SESSION['user_role'] === 'dueno_agencia') {
            foreach ($reservations as $res) {
                if ($res['estado'] === 'confirmada' || $res['estado'] === 'completada') {
                    $totalRevenue += $res['precio_total'];
                }
            }
        }

        // Get recent reservations (last 5)
        $recentReservations = array_slice($reservations, 0, 5);

        // --- Fetch Dashboard Alerts ---
        require_once BASE_PATH . '/models/Departure.php';
        $departureModel = new Departure($this->pdo);

        $upcomingDepartures = $departureModel->getUpcoming($agencyId);
        $pendingPaymentAlerts = $this->reservationModel->getPendingAlerts($agencyId);
        $avgOccupancy = $departureModel->getAverageOccupancy($agencyId);

        require_once BASE_PATH . '/views/dashboard/index.php';
    }

    public function profile()
    {
        require_once BASE_PATH . '/models/Agency.php';
        $agencyModel = new Agency($this->pdo);

        // Obtener datos de la agencia
        $agency = $agencyModel->getById($_SESSION['agencia_id']);

        // Si es empleado, necesitamos sus datos específicos de usuario
        require_once BASE_PATH . '/models/User.php';
        $userModel = new User($this->pdo);
        $currUser = $userModel->getById($_SESSION['user_id']);

        require_once BASE_PATH . '/views/agency/profile/index.php';
    }

    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once BASE_PATH . '/models/Agency.php';
            require_once BASE_PATH . '/models/User.php';

            $agencyModel = new Agency($this->pdo);
            $userModel = new User($this->pdo);

            $agencyId = $_SESSION['agencia_id'];
            $userId = $_SESSION['user_id'];

            // 1. Actualizar Datos de Agencia (SOLO DUEÑO)
            if ($_SESSION['user_role'] === 'dueno_agencia') {
                $agencyData = [
                    'nombre' => $_POST['nombre_agencia'],
                    'direccion' => $_POST['direccion'],
                    'telefono' => $_POST['telefono'],
                    'email' => $_POST['email_agencia'],
                    'tipo_suscripcion' => $_POST['tipo_suscripcion'],
                    'fecha_vencimiento' => $_POST['fecha_vencimiento']
                ];
                $agencyModel->update($agencyId, $agencyData);
            }

            // 2. Actualizar Datos de Usuario (Cualquier rol para su propia cuenta)
            $userData = [
                'nombre' => $_POST['nombre_usuario'],
                'apellido' => $_POST['apellido_usuario'],
                'email' => $_POST['email_usuario'],
                'es_activo' => 1 // Siempre activo si está editando
            ];

            // Solo actualizar password si se escribió algo
            if (!empty($_POST['password'])) {
                $userData['password'] = $_POST['password'];
            }

            $userModel->update($userId, $userData);

            // Actualizar sesión si cambió el nombre
            $_SESSION['user_name'] = $_POST['nombre_usuario'] . ' ' . $_POST['apellido_usuario'];

            // Redireccionar con éxito
            header('Location: ' . BASE_URL . 'agency/profile?success=1');
            exit;
        }
    }
}

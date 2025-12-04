<?php
// Sistema_New/controllers/AgencyController.php

require_once BASE_PATH . '/models/Tour.php';
require_once BASE_PATH . '/models/Reservation.php';

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

        // Fetch KPIs
        $tours = $this->tourModel->getAllByAgency($agencyId);
        $reservations = $this->reservationModel->getAllByAgency($agencyId);

        $totalTours = count($tours);
        $totalReservations = count($reservations);

        // Calculate total revenue from confirmed reservations
        $totalRevenue = 0;
        foreach ($reservations as $res) {
            if ($res['estado'] === 'confirmada' || $res['estado'] === 'completada') {
                $totalRevenue += $res['precio_total'];
            }
        }

        // Get recent reservations (last 5)
        $recentReservations = array_slice($reservations, 0, 5);

        require_once BASE_PATH . '/views/dashboard/index.php';
    }

    public function profile()
    {
        // Placeholder for profile
        echo "Perfil de Agencia (Próximamente)";
    }
}

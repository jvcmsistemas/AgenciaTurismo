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
        $agencyId = $_SESSION['agency_id'];
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
    }
}

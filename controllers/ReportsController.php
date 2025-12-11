<?php
// Sistema_New/controllers/ReportsController.php

require_once BASE_PATH . '/models/Report.php';

class ReportsController
{
    private $pdo;
    private $reportModel;

    public function __construct($pdo)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador_general') {
            redirect('login');
        }
        $this->pdo = $pdo;
        $this->reportModel = new Report($pdo);
    }

    public function index()
    {
        // 1. Financiero
        $incomeTrends = $this->reportModel->getIncomeByPeriod();
        $topAgencies = $this->reportModel->getIncomeByAgency();

        // 2. Operativo
        $reservationStats = $this->reportModel->getReservationStats();
        $totalReservas = array_sum($reservationStats);

        // 3. Satisfacción
        $satisfaction = $this->reportModel->getSatisfactionStats();
        $recentReviews = $this->reportModel->getRecentReviews();

        require_once BASE_PATH . '/views/admin/reports/index.php';
    }
}

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
        // Filtros (Opcional por ahora, pero preparados)
        $startDate = $_GET['start'] ?? date('Y-m-01');
        $endDate = $_GET['end'] ?? date('Y-m-t');

        // 1. KPIs Generales
        $kpis = $this->reportModel->getOperationalKPIs();

        // 2. Desempeño Financiero y Agencias
        $agencyPerformance = $this->reportModel->getAgencyFinancialPerformance();
        $incomeTrends = $this->reportModel->getIncomeByPeriod();

        // 3. Insights de Mercado
        $marketInsights = $this->reportModel->getMarketInsights();

        // 4. Soporte y Servicio
        $supportLoad = $this->reportModel->getSupportLoadByAgency();

        // 5. Estadísticas de Reservas (Existente)
        $reservationStats = $this->reportModel->getReservationStats();
        $totalReservas = array_sum($reservationStats);

        require_once BASE_PATH . '/views/admin/reports/index.php';
    }

}

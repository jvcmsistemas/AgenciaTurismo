<?php
// Sistema_New/controllers/AgencyReportsController.php

require_once BASE_PATH . '/models/AgencyReport.php';

class AgencyReportsController
{
    private $pdo;
    private $reportModel;

    public function __construct($pdo)
    {
        // Solo accesible para dueños de agencia
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'dueno_agencia') {
            redirect('agency/dashboard');
        }
        $this->pdo = $pdo;
        $this->reportModel = new AgencyReport($pdo);
    }

    public function profitability()
    {
        $agencyId = $_SESSION['agencia_id'];

        $startDate = $_GET['start_date'] ?? date('Y-m-01'); // Inicio del mes actual
        $endDate = $_GET['end_date'] ?? date('Y-m-t');   // Fin del mes actual

        $reports = $this->reportModel->getProfitabilityPerDeparture($agencyId, $startDate, $endDate);

        require_once BASE_PATH . '/views/agency/reports/profitability.php';
    }
}

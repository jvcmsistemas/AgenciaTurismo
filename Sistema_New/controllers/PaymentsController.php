<?php
// Sistema_New/controllers/PaymentsController.php

require_once BASE_PATH . '/models/Payment.php';

class PaymentsController
{
    private $pdo;
    private $paymentModel;

    public function __construct($pdo)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador_general') {
            redirect('dashboard');
        }

        $this->pdo = $pdo;
        $this->paymentModel = new Payment($pdo);
    }

    public function index()
    {
        // Obtener filtros si existen
        $startDate = $_GET['start_date'] ?? date('Y-m-01'); // Inicio de mes por defecto
        $endDate = $_GET['end_date'] ?? date('Y-m-d');

        // Datos para el dashboard
        $payments = $this->paymentModel->getAll(['start_date' => $startDate . ' 00:00:00', 'end_date' => $endDate . ' 23:59:59']);
        $statsByMethod = $this->paymentModel->getStatsByMethod();
        $statsByAgency = $this->paymentModel->getStatsByAgency();

        // Calcular total global
        $totalIncome = 0;
        foreach ($payments as $p) {
            if ($p['estado'] === 'aprobado') {
                $totalIncome += $p['monto'];
            }
        }

        require_once BASE_PATH . '/views/admin/payments/index.php';
    }
}

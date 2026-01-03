<?php
// Sistema_New/controllers/AgencyPaymentsController.php

require_once BASE_PATH . '/models/Payment.php';

class AgencyPaymentsController
{
    private $pdo;
    private $paymentModel;

    public function __construct($pdo)
    {
        // Solo Dueño de Agencia
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'dueno_agencia') {
            redirect('dashboard');
        }

        $this->pdo = $pdo;
        $this->paymentModel = new Payment($pdo);
    }

    public function index()
    {
        $agencyId = $_SESSION['agencia_id'];

        // Filtros básicos
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $search = $_GET['search'] ?? '';

        // Parámetros de paginación y orden
        $limit = 10;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $sort = $_GET['sort'] ?? 'p.fecha_pago';
        $order = $_GET['order'] ?? 'DESC';

        $filters = [
            'agencia_id' => $agencyId,
            'start_date' => $startDate . ' 00:00:00',
            'end_date' => $endDate . ' 23:59:59',
            'search' => $search
        ];

        $payments = $this->paymentModel->getAll($filters, $limit, $offset, $sort, $order);
        $totalPayments = $this->paymentModel->countAll($filters);
        $totalPages = ceil($totalPayments / $limit);

        // Estadísticas simples para la agencia (para el resumen superior, quizás convenga un método separado si hay muchos datos, pero por ahora getAll sin limit para stats)
        $allPaymentsForStats = $this->paymentModel->getAll($filters);
        $totalIncome = 0;
        foreach ($allPaymentsForStats as $p) {
            if ($p['estado'] === 'aprobado') {
                $totalIncome += $p['monto'];
            }
        }

        require_once BASE_PATH . '/views/agency/payments/index.php';
    }
}

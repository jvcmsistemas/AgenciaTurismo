<?php
// Sistema_New/controllers/AgencyExpensesController.php

require_once BASE_PATH . '/models/Expense.php';
require_once BASE_PATH . '/models/Departure.php';

class AgencyExpensesController
{
    private $pdo;
    private $expenseModel;

    public function __construct($pdo)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'dueno_agencia') {
            redirect('agency/dashboard');
        }
        $this->pdo = $pdo;
        $this->expenseModel = new Expense($pdo);
    }

    public function index()
    {
        $agencyId = $_SESSION['agencia_id'];

        $filters = [
            'categoria' => $_GET['categoria'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'start_date' => $_GET['start_date'] ?? date('Y-m-01'),
            'end_date' => $_GET['end_date'] ?? date('Y-m-t')
        ];

        $expenses = $this->expenseModel->getAllByAgency($agencyId, $filters);

        // Para el modal de registro rápido
        $departureModel = new Departure($this->pdo);
        $activeDepartures = $departureModel->getAllByAgency($agencyId); // Asumimos que existe este método o similar

        require_once BASE_PATH . '/views/agency/expenses/index.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'agencia_id' => $_SESSION['agencia_id'],
                'salida_id' => !empty($_POST['salida_id']) ? $_POST['salida_id'] : null,
                'categoria' => $_POST['categoria'],
                'beneficiario' => $_POST['beneficiario'],
                'monto' => $_POST['monto'],
                'fecha_gasto' => $_POST['fecha_gasto'],
                'estado' => $_POST['estado'] ?? 'pendiente',
                'notas' => $_POST['notas']
            ];

            if ($this->expenseModel->create($data)) {
                redirect('agency/expenses?success=1');
            } else {
                redirect('agency/expenses?error=1');
            }
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                redirect('agency/expenses?error=missing_id');
            }

            $data = [
                'salida_id' => !empty($_POST['salida_id']) ? $_POST['salida_id'] : null,
                'categoria' => $_POST['categoria'],
                'beneficiario' => $_POST['beneficiario'],
                'monto' => $_POST['monto'],
                'fecha_gasto' => $_POST['fecha_gasto'],
                'estado' => $_POST['estado'] ?? 'pendiente',
                'notas' => $_POST['notas']
            ];

            if ($this->expenseModel->update($id, $_SESSION['agencia_id'], $data)) {
                redirect('agency/expenses?updated=1');
            } else {
                redirect('agency/expenses?error=1');
            }
        }
    }

    public function updateStatus()
    {
        $id = $_GET['id'] ?? null;
        $status = $_GET['status'] ?? 'pagado';

        if ($id && $this->expenseModel->updateStatus($id, $_SESSION['agencia_id'], $status)) {
            redirect('agency/expenses?updated=1');
        } else {
            redirect('agency/expenses?error=1');
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id && $this->expenseModel->delete($id, $_SESSION['agencia_id'])) {
            redirect('agency/expenses?deleted=1');
        } else {
            redirect('agency/expenses?error=1');
        }
    }
}

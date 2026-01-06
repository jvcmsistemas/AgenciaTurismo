<?php
// Sistema_New/controllers/PlansController.php

require_once BASE_PATH . '/models/Plan.php';

class PlansController
{
    private $pdo;
    private $planModel;

    public function __construct($pdo)
    {
        // Verificar acceso de Super Admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador_general') {
            redirect('dashboard');
        }

        $this->pdo = $pdo;
        $this->planModel = new Plan($pdo);
    }

    public function index()
    {
        $plans = $this->planModel->getAll();
        require_once BASE_PATH . '/views/admin/plans/index.php';
    }

    public function create()
    {
        require_once BASE_PATH . '/views/admin/plans/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->planModel->create($_POST);
                redirect('admin/plans');
            } catch (Exception $e) {
                $error = "Error al crear plan: " . $e->getMessage();
                require_once BASE_PATH . '/views/admin/plans/create.php';
            }
        }
    }

    public function edit($id)
    {
        $plan = $this->planModel->getById($id);
        if (!$plan) {
            redirect('admin/plans');
        }
        require_once BASE_PATH . '/views/admin/plans/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            try {
                $this->planModel->update($id, $_POST);
                redirect('admin/plans');
            } catch (Exception $e) {
                $error = "Error al actualizar plan: " . $e->getMessage();
                $plan = $this->planModel->getById($id);
                require_once BASE_PATH . '/views/admin/plans/edit.php';
            }
        }
    }

    public function delete($id)
    {
        try {
            $this->planModel->delete($id);
            redirect('admin/plans');
        } catch (Exception $e) {
            // Manejar error (podríamos pasar un mensaje de error a la vista usando sesión)
            // Por ahora, redirigir con query param de error es simple
            redirect('admin/plans?error=' . urlencode($e->getMessage()));
        }
    }
}

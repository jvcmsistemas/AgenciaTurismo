<?php
// Sistema_New/controllers/TransportController.php

require_once BASE_PATH . '/models/Transport.php';

class TransportController
{
    private $pdo;
    private $transportModel;

    public function __construct($pdo)
    {
        // Verificar acceso (Dueño o Empleado de Agencia)
        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['dueno_agencia', 'empleado_agencia'])) {
            redirect('login');
        }

        $this->pdo = $pdo;
        $this->transportModel = new Transport($pdo);
    }

    public function index()
    {
        $agencyId = $_SESSION['agencia_id'];
        $search = $_GET['search'] ?? '';
        $sort = $_GET['sort'] ?? 'placa';
        $order = $_GET['order'] ?? 'ASC';

        // Paginación
        $limit = 10;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $transports = $this->transportModel->getAllByAgency($agencyId, $search, $limit, $offset, $sort, $order);
        $totalTransports = $this->transportModel->countAllByAgency($agencyId, $search);
        $totalPages = ceil($totalTransports / $limit);

        require_once BASE_PATH . '/views/agency/transport/index.php';
    }

    public function create()
    {
        require_once BASE_PATH . '/views/agency/transport/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'agencia_id' => $_SESSION['agencia_id'],
                'placa' => $_POST['placa'],
                'modelo' => $_POST['modelo'],
                'capacidad' => $_POST['capacidad'],
                'chofer_nombre' => $_POST['chofer_nombre'],
                'chofer_telefono' => $_POST['chofer_telefono']
            ];

            $this->transportModel->create($data);
            redirect('agency/transport');
        }
    }

    public function edit($id)
    {
        if (!$id)
            redirect('agency/transport');

        $transport = $this->transportModel->getById($id);

        if (!$transport || $transport['agencia_id'] != $_SESSION['agencia_id']) {
            redirect('agency/transport');
        }

        require_once BASE_PATH . '/views/agency/transport/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'agencia_id' => $_SESSION['agencia_id'],
                'placa' => $_POST['placa'],
                'modelo' => $_POST['modelo'],
                'capacidad' => $_POST['capacidad'],
                'chofer_nombre' => $_POST['chofer_nombre'],
                'chofer_telefono' => $_POST['chofer_telefono'],
                'estado' => $_POST['estado'] ?? 'activo'
            ];

            $this->transportModel->update($id, $data);
            redirect('agency/transport');
        }
    }

    public function delete($id)
    {
        // Solo Dueño puede borrar transporte
        if ($_SESSION['user_role'] !== 'dueno_agencia') {
            redirect('agency/transport?error=nopermission');
        }

        if ($id) {
            $this->transportModel->delete($id, $_SESSION['agencia_id']);
        }
        redirect('agency/transport');
    }
}

<?php
// Sistema_New/controllers/ProviderController.php

require_once BASE_PATH . '/models/Provider.php';

class ProviderController
{
    private $pdo;
    private $providerModel;

    public function __construct($pdo)
    {
        // Verificar acceso
        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['dueno_agencia', 'empleado_agencia'])) {
            redirect('login');
        }

        $this->pdo = $pdo;
        $this->providerModel = new Provider($pdo);
    }

    public function index()
    {
        $agencyId = $_SESSION['agencia_id'];
        $search = $_GET['search'] ?? '';
        $sort = $_GET['sort'] ?? 'nombre';
        $order = $_GET['order'] ?? 'ASC';

        // Paginación
        $limit = 10;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $providers = $this->providerModel->getAllByAgency($agencyId, $search, $limit, $offset, $sort, $order);
        $totalProviders = $this->providerModel->countAllByAgency($agencyId, $search);
        $totalPages = ceil($totalProviders / $limit);

        require_once BASE_PATH . '/views/agency/providers/index.php';
    }

    public function create()
    {
        require_once BASE_PATH . '/views/agency/providers/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'agencia_id' => $_SESSION['agencia_id'],
                'nombre' => $_POST['nombre'],
                'tipo' => $_POST['tipo'],
                'telefono' => $_POST['telefono'],
                'email' => $_POST['email'] ?? null,
                'direccion' => $_POST['direccion'] ?? null,
                'notas' => $_POST['notas'] ?? null
            ];

            $this->providerModel->create($data);
            redirect('agency/providers');
        }
    }

    public function edit($id)
    {
        if (!$id)
            redirect('agency/providers');

        $provider = $this->providerModel->getById($id);

        if (!$provider || $provider['agencia_id'] != $_SESSION['agencia_id']) {
            redirect('agency/providers');
        }

        require_once BASE_PATH . '/views/agency/providers/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'agencia_id' => $_SESSION['agencia_id'],
                'nombre' => $_POST['nombre'],
                'tipo' => $_POST['tipo'],
                'telefono' => $_POST['telefono'],
                'email' => $_POST['email'] ?? null,
                'direccion' => $_POST['direccion'] ?? null,
                'estado' => $_POST['estado'] ?? 'activo',
                'notas' => $_POST['notas'] ?? null
            ];

            $this->providerModel->update($id, $data);
            redirect('agency/providers');
        }
    }

    public function delete($id)
    {
        // Solo Dueño puede borrar proveedores
        if ($_SESSION['user_role'] !== 'dueno_agencia') {
            redirect('agency/providers?error=nopermission');
        }

        if ($id) {
            $this->providerModel->delete($id, $_SESSION['agencia_id']);
        }
        redirect('agency/providers');
    }
}

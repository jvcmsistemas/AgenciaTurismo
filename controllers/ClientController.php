<?php

require_once BASE_PATH . '/models/Client.php';

class ClientController
{
    private $pdo;
    private $clientModel;

    public function __construct($pdo)
    {
        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['dueno_agencia', 'empleado_agencia'])) {
            redirect('login');
        }

        $this->pdo = $pdo;
        $this->clientModel = new Client($pdo);
    }

    public function index()
    {
        $agencyId = $_SESSION['agencia_id'];
        $search = $_GET['search'] ?? '';

        $clients = $this->clientModel->getAllByAgency($agencyId, $search);
        require_once BASE_PATH . '/views/agency/clients/index.php';
    }

    public function create()
    {
        require_once BASE_PATH . '/views/agency/clients/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'agencia_id' => $_SESSION['agencia_id'],
                    'nombre' => $_POST['nombre'],
                    'apellido' => $_POST['apellido'],
                    'email' => $_POST['email'],
                    'dni' => $_POST['dni'],
                    'ruc' => $_POST['ruc'],
                    'telefono' => $_POST['telefono'],
                    'nacionalidad' => $_POST['nacionalidad']
                ];

                $this->clientModel->create($data);
                redirect('agency/clients?success=created');
            } catch (Exception $e) {
                // Manejo básico de error (e.j. duplicados)
                redirect('agency/clients/create?error=' . urlencode($e->getMessage()));
            }
        }
    }

    public function edit($id)
    {
        $agencyId = $_SESSION['agencia_id'];
        $client = $this->clientModel->getById($id);

        if (!$client || $client['agencia_id'] != $agencyId) {
            redirect('agency/clients');
        }

        require_once BASE_PATH . '/views/agency/clients/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'agencia_id' => $_SESSION['agencia_id'],
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'email' => $_POST['email'],
                'dni' => $_POST['dni'],
                'ruc' => $_POST['ruc'],
                'telefono' => $_POST['telefono'],
                'nacionalidad' => $_POST['nacionalidad']
            ];

            $this->clientModel->update($id, $data);
            redirect('agency/clients?success=updated');
        }
    }

    public function delete($id)
    {
        try {
            $this->clientModel->delete($id, $_SESSION['agencia_id']);
            redirect('agency/clients?success=deleted');
        } catch (Exception $e) {
            redirect('agency/clients?error=' . urlencode($e->getMessage()));
        }
    }

    // Método AJAX para autocompletado en reservas
    public function searchApi()
    {
        header('Content-Type: application/json');
        $agencyId = $_SESSION['agencia_id'];
        $query = $_GET['q'] ?? '';

        if (strlen($query) < 2) {
            echo json_encode([]);
            return;
        }

        $clients = $this->clientModel->getAllByAgency($agencyId, $query);
        echo json_encode($clients);
    }
}

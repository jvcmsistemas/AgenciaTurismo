<?php
// Sistema_New/controllers/ResourceController.php

require_once BASE_PATH . '/models/Guide.php';
require_once BASE_PATH . '/models/Transport.php';
require_once BASE_PATH . '/models/Provider.php';

class ResourceController
{
    private $pdo;
    private $guideModel;
    private $transportModel;
    private $providerModel;

    public function __construct($pdo)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'dueno_agencia') {
            redirect('login');
        }

        $this->pdo = $pdo;
        $this->guideModel = new Guide($pdo);
        $this->transportModel = new Transport($pdo);
        $this->providerModel = new Provider($pdo);
    }

    public function index()
    {
        $agencyId = $_SESSION['agencia_id'];
        $search = $_GET['search'] ?? '';
        $tab = $_GET['tab'] ?? 'guides';

        $guides = $this->guideModel->getAllByAgency($agencyId, ($tab === 'guides') ? $search : '');
        $transports = $this->transportModel->getAllByAgency($agencyId, ($tab === 'transport') ? $search : '');
        $providers = $this->providerModel->getAllByAgency($agencyId, ($tab === 'providers') ? $search : '');

        require_once BASE_PATH . '/views/agency/resources/index.php';
    }

    // --- GUÍAS ---
    public function storeGuide()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'agencia_id' => $_SESSION['agencia_id'],
                'nombre' => $_POST['nombre'],
                'dni' => $_POST['dni'],
                'telefono' => $_POST['telefono'],
                'email' => $_POST['email'],
                'idiomas' => $_POST['idiomas']
            ];
            $this->guideModel->create($data);
            redirect('agency/resources?tab=guides');
        }
    }

    public function deleteGuide()
    {
        if (isset($_GET['id'])) {
            $this->guideModel->delete($_GET['id'], $_SESSION['agencia_id']);
            redirect('agency/resources?tab=guides');
        }
    }

    // --- TRANSPORTES ---
    public function storeTransport()
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
            redirect('agency/resources?tab=transport');
        }
    }

    public function deleteTransport()
    {
        if (isset($_GET['id'])) {
            $this->transportModel->delete($_GET['id'], $_SESSION['agencia_id']);
            redirect('agency/resources?tab=transport');
        }
    }

    // --- PROVEEDORES ---
    public function storeProvider()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'agencia_id' => $_SESSION['agencia_id'],
                'nombre' => $_POST['nombre'],
                'tipo' => $_POST['tipo'],
                'contacto_nombre' => $_POST['contacto_nombre'],
                'telefono' => $_POST['telefono'],
                'ubicacion' => $_POST['ubicacion']
            ];
            $this->providerModel->create($data);
            redirect('agency/resources?tab=providers');
        }
    }

    public function deleteProvider()
    {
        if (isset($_GET['id'])) {
            $this->providerModel->delete($_GET['id'], $_SESSION['agencia_id']);
            redirect('agency/resources?tab=providers');
        }
    }
    // --- UPDATE METHODS ---
    public function updateGuide()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'agencia_id' => $_SESSION['agencia_id'],
                'nombre' => $_POST['nombre'],
                'dni' => $_POST['dni'],
                'telefono' => $_POST['telefono'],
                'email' => $_POST['email'],
                'idiomas' => $_POST['idiomas'],
                'estado' => $_POST['estado'] ?? 'activo'
            ];
            $this->guideModel->update($id, $data);
            redirect('agency/resources?tab=guides');
        }
    }

    public function updateTransport()
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
            redirect('agency/resources?tab=transport');
        }
    }

    public function updateProvider()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'agencia_id' => $_SESSION['agencia_id'],
                'nombre' => $_POST['nombre'],
                'tipo' => $_POST['tipo'],
                'contacto_nombre' => $_POST['contacto_nombre'],
                'telefono' => $_POST['telefono'],
                'ubicacion' => $_POST['ubicacion'],
                'estado' => $_POST['estado'] ?? 'activo'
            ];
            $this->providerModel->update($id, $data);
            redirect('agency/resources?tab=providers');
        }
    }

    // --- API JSON Methods ---
    public function getByTypeApi()
    {
        header('Content-Type: application/json');

        $type = $_GET['type'] ?? '';
        $agencyId = $_SESSION['agencia_id'];

        // Si el tipo es 'tour', devolvemos tours (aunque usemos otro endpoint generalmente)
        // Aquí nos enfocamos en proveedores

        try {
            $sql = "SELECT id, nombre, tipo FROM proveedores WHERE agencia_id = :agid AND estado = 'activo'";
            $params = ['agid' => $agencyId];

            if ($type && $type !== 'all') {
                $sql .= " AND tipo = :tipo";
                $params['tipo'] = $type;
            }

            $sql .= " ORDER BY nombre ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

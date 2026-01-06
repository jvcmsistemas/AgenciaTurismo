<?php
// Sistema_New/controllers/ResourceController.php

require_once BASE_PATH . '/models/Guide.php';
require_once BASE_PATH . '/models/Provider.php';

class ResourceController
{
    private $pdo;
    private $guideModel;

    public function __construct($pdo)
    {
        // Verificar acceso (Dueño o Empleado de Agencia)
        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['dueno_agencia', 'empleado_agencia'])) {
            redirect('login');
        }

        $this->pdo = $pdo;
        $this->guideModel = new Guide($pdo);
    }

    public function index()
    {
        $agencyId = $_SESSION['agencia_id'];
        $search = $_GET['search'] ?? '';
        $tab = $_GET['tab'] ?? 'guides';

        // Parámetros de paginación y orden
        $limit = 10;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $sort = $_GET['sort'] ?? 'nombre';
        $order = $_GET['order'] ?? 'ASC';

        // Solo el tab activo (que ahora es el único: Guías) usa búsqueda, paginación y orden avanzado
        $guides = $this->guideModel->getAllByAgency($agencyId, $search, $limit, $offset, $sort, $order);
        $totalGuides = $this->guideModel->countAllByAgency($agencyId, $search);
        $totalPages = ceil($totalGuides / $limit);

        require_once BASE_PATH . '/views/agency/resources/index.php';
    }

    // --- GUÍAS ---
    public function storeGuide()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'agencia_id' => $_SESSION['agencia_id'],
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'dni' => $_POST['dni'],
                'fecha_nacimiento' => $_POST['fecha_nacimiento'],
                'genero' => $_POST['genero'],
                'email' => $_POST['email'],
                'telefono' => $_POST['telefono'],
                'direccion' => $_POST['direccion'],
                'ciudad_region' => $_POST['ciudad_region'],
                'notas' => $_POST['notas']
            ];
            $this->guideModel->create($data);
            redirect('agency/resources?tab=guides');
        }
    }

    public function deleteGuide()
    {
        // Solo Dueño puede borrar guías
        if ($_SESSION['user_role'] !== 'dueno_agencia') {
            redirect('agency/resources?tab=guides&error=nopermission');
        }

        if (isset($_GET['id'])) {
            $this->guideModel->delete($_GET['id'], $_SESSION['agencia_id']);
            redirect('agency/resources?tab=guides');
        }
    }

    public function updateGuide()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'agencia_id' => $_SESSION['agencia_id'],
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'dni' => $_POST['dni'],
                'fecha_nacimiento' => $_POST['fecha_nacimiento'],
                'genero' => $_POST['genero'],
                'email' => $_POST['email'],
                'telefono' => $_POST['telefono'],
                'direccion' => $_POST['direccion'],
                'ciudad_region' => $_POST['ciudad_region'],
                'notas' => $_POST['notas'],
                'estado' => $_POST['estado'] ?? 'activo'
            ];
            $this->guideModel->update($id, $data);
            redirect('agency/resources?tab=guides');
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

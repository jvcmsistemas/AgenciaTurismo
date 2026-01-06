<?php
require_once BASE_PATH . '/models/User.php';

class UserController
{
    private $pdo;
    private $userModel;

    public function __construct($pdo)
    {
        // Solo Super Admin puede ver esto
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador_general') {
            redirect('dashboard');
        }
        $this->pdo = $pdo;
        $this->userModel = new User($pdo);
    }

    public function index()
    {
        // Obtener parámetros de filtro
        $search = $_GET['search'] ?? '';
        $sort = $_GET['sort'] ?? 'created_at DESC';
        $agencyId = $_GET['agency_id'] ?? '';

        // Obtener usuarios filtrados
        $users = $this->userModel->getAll($search, $sort, $agencyId);

        // Obtener lista de agencias para el dropdown de filtro
        require_once BASE_PATH . '/models/Agency.php';
        $agencyModel = new Agency($this->pdo);
        $agencies = $agencyModel->getAll(); // Obtener todas para el select

        require_once BASE_PATH . '/views/admin/users/index.php';
    }
}

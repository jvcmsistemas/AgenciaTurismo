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
        $users = $this->userModel->getAll();
        require_once BASE_PATH . '/views/admin/users/index.php';
    }
}

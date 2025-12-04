<?php
// Sistema_New/controllers/AdminController.php

require_once BASE_PATH . '/models/Agency.php';
require_once BASE_PATH . '/models/User.php';

class AdminController
{
    private $pdo;
    private $agencyModel;

    public function __construct($pdo)
    {
        // Verificar acceso de Super Admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador_general') {
            redirect('dashboard'); // O mostrar error 403
        }

        $this->pdo = $pdo;
        $this->agencyModel = new Agency($pdo);
    }

    public function index()
    {
        // Dashboard solo necesita contadores por ahora, pero pasamos agencias para los KPIs
        $agencies = $this->agencyModel->getAll();
        require_once BASE_PATH . '/views/admin/dashboard.php';
    }

    public function agencies()
    {
        $search = $_GET['search'] ?? '';
        $orderBy = $_GET['sort'] ?? 'fecha_vencimiento ASC';

        $agencies = $this->agencyModel->getAll($search, $orderBy);
        require_once BASE_PATH . '/views/admin/agencies/index.php';
    }

    public function create()
    {
        require_once BASE_PATH . '/views/admin/agencies/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->pdo->beginTransaction();

                // 1. Crear Usuario (Dueño)
                $userModel = new User($this->pdo);
                $userData = [
                    'nombre' => $_POST['nombre_dueno'],
                    'apellido' => $_POST['apellido_dueno'],
                    'email' => $_POST['email_dueno'],
                    'password' => $_POST['password'],
                    'rol' => 'dueno_agencia',
                    'agencia_id' => null // Se actualizará después
                ];
                $userId = $userModel->create($userData);

                // 2. Crear Agencia
                $agencyData = [
                    'nombre' => $_POST['nombre_agencia'],
                    'direccion' => $_POST['direccion'],
                    'telefono' => $_POST['telefono'],
                    'email' => $_POST['email_agencia'],
                    'dueno_id' => $userId,
                    'tipo_suscripcion' => $_POST['tipo_suscripcion'],
                    'fecha_vencimiento' => $this->calculateExpirationDate($_POST['tipo_suscripcion'])
                ];
                $agencyId = $this->agencyModel->create($agencyData);

                // 3. Vincular Agencia al Usuario
                $stmt = $this->pdo->prepare("UPDATE usuarios SET agencia_id = :agencia_id WHERE id = :id");
                $stmt->execute(['agencia_id' => $agencyId, 'id' => $userId]);

                $this->pdo->commit();
                redirect('admin/dashboard');

            } catch (Exception $e) {
                $this->pdo->rollBack();
                $error = "Error al registrar: " . $e->getMessage();
                require_once BASE_PATH . '/views/admin/agencies/create.php';
            }
        }
    }

    public function edit($id)
    {
        $agency = $this->agencyModel->getById($id);
        if (!$agency) {
            redirect('admin/dashboard');
        }
        require_once BASE_PATH . '/views/admin/agencies/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $agencyId = $_POST['id'];
            $duenoId = $_POST['dueno_id'];

            try {
                $this->pdo->beginTransaction();

                // 1. Actualizar Agencia
                $currentAgency = $this->agencyModel->getById($agencyId);

                $agencyData = [
                    'nombre' => $_POST['nombre_agencia'],
                    'direccion' => $_POST['direccion'],
                    'telefono' => $_POST['telefono'],
                    'email' => $_POST['email_agencia'],
                    'tipo_suscripcion' => $_POST['tipo_suscripcion'],
                    'fecha_vencimiento' => !empty($_POST['fecha_vencimiento']) ? $_POST['fecha_vencimiento'] : $currentAgency['fecha_vencimiento']
                ];
                $this->agencyModel->update($agencyId, $agencyData);

                // 2. Actualizar Dueño
                $userModel = new User($this->pdo);
                $userData = [
                    'nombre' => $_POST['nombre_dueno'],
                    'apellido' => $_POST['apellido_dueno'],
                    'email' => $_POST['email_dueno'],
                    'password' => $_POST['password_dueno'] ?? null
                ];
                $userModel->update($duenoId, $userData);

                $this->pdo->commit();
                redirect('admin/dashboard');

            } catch (Exception $e) {
                $this->pdo->rollBack();
                $error = "Error al actualizar: " . $e->getMessage();
                $agency = $this->agencyModel->getById($agencyId);
                require_once BASE_PATH . '/views/admin/agencies/edit.php';
            }
        }
    }

    public function toggleStatus($id)
    {
        // Lógica simple para alternar estado (demo)
        // En producción recibiríamos el estado por POST
        // Aquí asumiremos que viene por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status']; // activa, inactiva, suspendida
            $this->agencyModel->updateStatus($id, $status);
            redirect('admin/dashboard');
        }
    }

    private function calculateExpirationDate($plan)
    {
        $date = new DateTime();
        switch ($plan) {
            case 'prueba':
                $date->modify('+1 month');
                break;
            case 'basico':
                $date->modify('+6 months');
                break;
            case 'premium':
                $date->modify('+1 year');
                break;
            default:
                $date->modify('+1 month');
        }
        return $date->format('Y-m-d');
    }
}

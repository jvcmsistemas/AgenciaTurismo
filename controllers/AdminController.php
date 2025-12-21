<?php
// Sistema_New/controllers/AdminController.php

require_once BASE_PATH . '/models/Agency.php';
require_once BASE_PATH . '/models/User.php';
require_once BASE_PATH . '/models/Payment.php';
require_once BASE_PATH . '/models/Ticket.php';

class AdminController
{
    private $pdo;
    private $agencyModel;
    private $paymentModel;
    private $ticketModel;

    public function __construct($pdo)
    {
        // Verificar acceso de Super Admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador_general') {
            redirect('admin/login');
        }

        $this->pdo = $pdo;
        $this->agencyModel = new Agency($pdo);
        $this->paymentModel = new Payment($pdo);
        $this->ticketModel = new Ticket($pdo);
    }

    public function index()
    {
        // 1. Estadísticas de Agencias
        $agencies = $this->agencyModel->getAll();

        // 2. Estadísticas de Usuarios
        $userModel = new User($this->pdo);
        $users = $userModel->getAll();
        $userCount = count($users);

        // 3. Ingresos Mensuales (KPI)
        // Usamos el metodo getIncomeByPeriod del Report o Payment model. 
        // Payment model tiene getPaymentsByMethod, create... 
        // Vamos a hacer una query simple aqui o llamar a Report si fuera necesario.
        // Haremos una query rapida para "Total Pagos Mes Actual"
        $currentMonth = date('Y-m');
        $stmt = $this->pdo->prepare("SELECT SUM(monto) FROM pagos WHERE DATE_FORMAT(created_at, '%Y-%m') = :mes AND estado = 'completado'");
        $stmt->execute(['mes' => $currentMonth]);
        $monthlyRevenue = $stmt->fetchColumn() ?: 0;

        // 4. Tickets Abiertos
        // Ticket model tiene getAll($filters)
        $openTickets = $this->ticketModel->getAll(['estado' => 'abierto']);
        $openTicketsCount = count($openTickets);

        // 5. Actividad Reciente (Agencias)
        $recentAgencies = array_slice($this->agencyModel->getAll('', 'id DESC'), 0, 5);

        // 6. Tickets Recientes
        $recentTickets = array_slice($openTickets, 0, 5);

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
                    'fecha_vencimiento' => !empty($_POST['fecha_vencimiento']) ? $_POST['fecha_vencimiento'] : $this->calculateExpirationDate($_POST['tipo_suscripcion'])
                ];
                $agencyId = $this->agencyModel->create($agencyData);

                // 3. Vincular Agencia al Usuario
                $stmt = $this->pdo->prepare("UPDATE usuarios SET agencia_id = :agencia_id WHERE id = :id");
                $stmt->execute(['agencia_id' => $agencyId, 'id' => $userId]);

                $this->pdo->commit();
                redirect('admin/agencies');

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
                redirect('admin/agencies');

            } catch (Exception $e) {
                $this->pdo->rollBack();
                $error = "Error al actualizar: " . $e->getMessage();
                $agency = $this->agencyModel->getById($agencyId);
                require_once BASE_PATH . '/views/admin/agencies/edit.php';
            }
        }
    }

    public function toggleStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $status = $_POST['status'];
            $this->agencyModel->updateStatus($id, $status);
            redirect('admin/agencies');
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

    // --- PERFIL DE SUPERADMIN ---
    public function profile()
    {
        $userId = $_SESSION['user_id'];
        $userModel = new User($this->pdo);
        $user = $userModel->getById($userId);
        require_once BASE_PATH . '/views/admin/profile/index.php';
    }

    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $userModel = new User($this->pdo);

            try {
                // Datos básicos
                $data = [
                    'nombre' => $_POST['nombre'],
                    'apellido' => $_POST['apellido'],
                    'email' => $_POST['email']
                ];

                // Cambio de contraseña si se proporciona
                if (!empty($_POST['password'])) {
                    if ($_POST['password'] !== $_POST['password_confirm']) {
                        throw new Exception("Las contraseñas no coinciden.");
                    }
                    $data['password'] = $_POST['password'];
                }

                $userModel->update($userId, $data);

                // Actualizar sesión si cambió el nombre
                $_SESSION['user_name'] = $data['nombre'];

                // Redirigir con éxito (podrías añadir flash messages después)
                redirect('admin/profile'); // TODO: Add success param

            } catch (Exception $e) {
                // Manejo de error
                $error = $e->getMessage();
                $user = $userModel->getById($userId); // Recargar usuario para la vista
                require_once BASE_PATH . '/views/admin/profile/index.php';
            }
        }
    }
}

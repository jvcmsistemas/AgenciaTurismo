<?php
// Sistema_New/controllers/AgencyUserController.php

require_once BASE_PATH . '/models/User.php';

class AgencyUserController
{
    private $pdo;
    private $userModel;

    public function __construct($pdo)
    {
        // Solo Dueño de Agencia puede gestionar personal
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'dueno_agencia') {
            redirect('dashboard');
        }

        $this->pdo = $pdo;
        $this->userModel = new User($pdo);
    }

    public function index()
    {
        $agencyId = $_SESSION['agencia_id'];
        $search = $_GET['search'] ?? '';
        $sort = $_GET['sort'] ?? 'nombre ASC';

        // Usamos el modelo User existente pero filtrado por la agencia actual
        $collaborators = $this->userModel->getAll($search, $sort, $agencyId);

        // El listado de getAll incluye al propio dueño, lo ideal es mostrar solo empleados 
        // o diferenciar los roles en la vista.

        require_once BASE_PATH . '/views/agency/users/index.php';
    }

    public function create()
    {
        require_once BASE_PATH . '/views/agency/users/create.php';
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id)
            redirect('agency/users');

        $user = $this->userModel->getById($id);
        if (!$user || $user['agencia_id'] != $_SESSION['agencia_id'] || $user['rol'] !== 'empleado_agencia') {
            redirect('agency/users');
        }

        require_once BASE_PATH . '/views/agency/users/edit.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'rol' => 'empleado_agencia', // Siempre empleado por defecto en este módulo
                'agencia_id' => $_SESSION['agencia_id']
            ];

            // Validar que el email no exista
            if ($this->userModel->findByEmail($data['email'])) {
                $_SESSION['error'] = "El correo electrónico ya está registrado.";
                redirect('agency/users/create');
            }

            $this->userModel->create($data);
            redirect('agency/users?success=1');
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if (!$id)
                redirect('agency/users');

            // Verificar pertenencia a la agencia
            $user = $this->userModel->getById($id);
            if (!$user || $user['agencia_id'] != $_SESSION['agencia_id'] || $user['rol'] !== 'empleado_agencia') {
                redirect('agency/users');
            }

            $data = [
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'email' => $_POST['email'],
                'es_activo' => $_POST['es_activo'] ?? 1
            ];

            // Solo incluir password si se envió una nueva
            if (!empty($_POST['password'])) {
                $data['password'] = $_POST['password'];
            }

            $this->userModel->update($id, $data);
            redirect('agency/users?updated=1');
        }
    }

    public function delete($id)
    {
        if ($id) {
            // Verificar que el usuario a borrar sea de la misma agencia y NO sea un dueño o admin
            $user = $this->userModel->getById($id);
            if ($user && $user['agencia_id'] == $_SESSION['agencia_id'] && $user['rol'] === 'empleado_agencia') {
                $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = :id");
                $stmt->execute(['id' => $id]);
            }
        }
        redirect('agency/users');
    }
}

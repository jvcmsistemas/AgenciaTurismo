<?php
// Sistema_New/controllers/TourController.php

require_once BASE_PATH . '/models/Tour.php';

class TourController
{
    private $pdo;
    private $tourModel;

    public function __construct($pdo)
    {
        // Verificar acceso (Dueño o Empleado)
        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['dueno_agencia', 'empleado_agencia'])) {
            redirect('login');
        }

        $this->pdo = $pdo;
        $this->tourModel = new Tour($pdo);
    }

    public function index()
    {
        $agencyId = $_SESSION['agencia_id'];
        $search = $_GET['search'] ?? '';
        $order = $_GET['order'] ?? 'newest';

        $tours = $this->tourModel->getAllByAgency($agencyId, $search, $order);
        require_once BASE_PATH . '/views/agency/tours/index.php';
    }

    public function create()
    {
        require_once BASE_PATH . '/views/agency/tours/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'duracion' => ($_POST['duracion_unidad'] === 'horas') ? ($_POST['duracion_valor'] / 24) : $_POST['duracion_valor'],
                'precio' => $_POST['precio'],
                'agencia_id' => $_SESSION['agencia_id'],
                'tags' => $_POST['tags'],
                'nivel_dificultad' => $_POST['nivel_dificultad'],
                'ubicacion' => $_POST['ubicacion']
            ];

            $this->tourModel->create($data);
            redirect('agency/tours');
        }
    }

    public function edit($id)
    {
        $tour = $this->tourModel->getById($id);

        // Verificar propiedad
        if (!$tour || $tour['agencia_id'] != $_SESSION['agencia_id']) {
            redirect('agency/tours');
        }

        require_once BASE_PATH . '/views/agency/tours/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'duracion' => ($_POST['duracion_unidad'] === 'horas') ? ($_POST['duracion_valor'] / 24) : $_POST['duracion_valor'],
                'precio' => $_POST['precio'],
                'tags' => $_POST['tags'],
                'nivel_dificultad' => $_POST['nivel_dificultad'],
                'ubicacion' => $_POST['ubicacion'],
                'agencia_id' => $_SESSION['agencia_id']
            ];

            $this->tourModel->update($id, $data);
            redirect('agency/tours');
        }
    }

    public function delete($id)
    {
        $this->tourModel->delete($id, $_SESSION['agencia_id']);
        redirect('agency/tours');
    }
}

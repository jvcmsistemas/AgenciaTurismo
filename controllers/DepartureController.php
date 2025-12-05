<?php

require_once BASE_PATH . '/models/Departure.php';
require_once BASE_PATH . '/models/Tour.php';
require_once BASE_PATH . '/models/Guide.php';
require_once BASE_PATH . '/models/Transport.php';

class DepartureController
{
    private $pdo;
    private $departureModel;
    private $tourModel;
    private $guideModel;
    private $transportModel;

    public function __construct($pdo)
    {
        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['dueno_agencia', 'empleado_agencia'])) {
            redirect('login');
        }

        $this->pdo = $pdo;
        $this->departureModel = new Departure($pdo);
        $this->tourModel = new Tour($pdo);
        $this->guideModel = new Guide($pdo);
        $this->transportModel = new Transport($pdo);
    }

    public function index()
    {
        $agencyId = $_SESSION['agencia_id'];
        $departures = $this->departureModel->getAllByAgency($agencyId);
        require_once BASE_PATH . '/views/agency/departures/index.php';
    }

    public function create()
    {
        $agencyId = $_SESSION['agencia_id'];
        // Obtener datos para los selectores
        $tours = $this->tourModel->getAllByAgency($agencyId);
        $guides = $this->guideModel->getAllByAgency($agencyId);
        $transports = $this->transportModel->getAllByAgency($agencyId);

        require_once BASE_PATH . '/views/agency/departures/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'agencia_id' => $_SESSION['agencia_id'],
                'tour_id' => $_POST['tour_id'],
                'fecha_salida' => $_POST['fecha_salida'] . ' ' . $_POST['hora_salida'], // Combinar fecha y hora
                'guia_id' => $_POST['guia_id'],
                'transporte_id' => $_POST['transporte_id'],
                'cupos_totales' => $_POST['cupos_totales'],
                'precio_actual' => !empty($_POST['precio_actual']) ? $_POST['precio_actual'] : null,
                'estado' => 'programada'
            ];

            $this->departureModel->create($data);
            redirect('agency/departures');
        }
    }

    public function delete($id)
    {
        $this->departureModel->delete($id, $_SESSION['agencia_id']);
        redirect('agency/departures');
    }
}

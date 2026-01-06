<?php
// Sistema_New/controllers/AgencySettingsController.php

require_once BASE_PATH . '/models/Agency.php';

class AgencySettingsController
{
    private $pdo;
    private $agencyModel;

    public function __construct($pdo)
    {
        // Solo accesible para dueños de agencia
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'dueno_agencia') {
            redirect('agency/dashboard');
        }
        $this->pdo = $pdo;
        $this->agencyModel = new Agency($pdo);
    }

    public function index()
    {
        $agencyId = $_SESSION['agencia_id'];
        $agency = $this->agencyModel->getById($agencyId);

        require_once BASE_PATH . '/views/agency/settings/index.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $agencyId = $_SESSION['agencia_id'];
            $currentAgency = $this->agencyModel->getById($agencyId);

            $data = [
                'nombre' => $_POST['nombre'] ?? $currentAgency['nombre'],
                'direccion' => $_POST['direccion'] ?? $currentAgency['direccion'],
                'telefono' => $_POST['telefono'] ?? $currentAgency['telefono'],
                'email' => $_POST['email'] ?? $currentAgency['email'],
                'ruc' => $_POST['ruc'] ?? $currentAgency['ruc'],
                'pais' => $_POST['pais'] ?? $currentAgency['pais'],
                'ciudad' => $_POST['ciudad'] ?? $currentAgency['ciudad'],
                'web' => $_POST['web'] ?? $currentAgency['web'],
                'logo_url' => $_POST['logo_url'] ?? $currentAgency['logo_url'],
                'descripcion' => $_POST['descripcion'] ?? $currentAgency['descripcion'],
                'tipo_suscripcion' => $currentAgency['tipo_suscripcion'], // No editable desde aquí por seguridad
                'fecha_vencimiento' => $currentAgency['fecha_vencimiento'] // No editable desde aquí
            ];

            if ($this->agencyModel->update($agencyId, $data)) {
                // Actualizar nombre en sesión si cambió
                $_SESSION['agencia_nombre'] = $data['nombre'];
                redirect('agency/settings?success=1');
            } else {
                redirect('agency/settings?error=update_failed');
            }
        }
    }
}

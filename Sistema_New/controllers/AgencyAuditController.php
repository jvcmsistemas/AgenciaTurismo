<?php
// Sistema_New/controllers/AgencyAuditController.php

require_once BASE_PATH . '/models/Audit.php';

class AgencyAuditController
{
    private $pdo;
    private $auditModel;

    public function __construct($pdo)
    {
        // Solo accesible para dueños de agencia
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'dueno_agencia') {
            redirect('agency/dashboard');
        }
        $this->pdo = $pdo;
        $this->auditModel = new Audit($pdo);
    }

    public function index()
    {
        $agencyId = $_SESSION['agencia_id'];

        // Obtener logs de acceso y actividad
        $accessLogs = $this->auditModel->getAccessLogs($agencyId);
        $activityLogs = $this->auditModel->getActivityLogs($agencyId);

        require_once BASE_PATH . '/views/agency/audit/index.php';
    }
}

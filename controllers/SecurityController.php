<?php
// Sistema_New/controllers/SecurityController.php

class SecurityController
{
    private $pdo;

    public function __construct($pdo)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador_general') {
            redirect('login');
        }
        $this->pdo = $pdo;
    }

    public function index()
    {
        // 1. Fetch KPI: Total Logins Today
        $stmt_logins = $this->pdo->query("SELECT COUNT(*) FROM logs_acceso WHERE tipo_evento = 'login' AND DATE(fecha_hora) = CURDATE()");
        $totalLoginsToday = $stmt_logins->fetchColumn();

        // 2. Fetch KPI: Failed Attempts Today
        $stmt_failed = $this->pdo->query("SELECT COUNT(*) FROM intentos_fallidos WHERE DATE(fecha_intento) = CURDATE()");
        $totalFailedToday = $stmt_failed->fetchColumn();

        // 3. Fetch KPI: Active Sessions
        $stmt_sessions = $this->pdo->query("SELECT COUNT(*) FROM sesiones WHERE activa = 1 AND fecha_expiracion > NOW()");
        $activeSessions = $stmt_sessions->fetchColumn();

        // 4. Fetch Recent Logs
        $stmt_logs = $this->pdo->query("SELECT l.*, u.nombre as usuario 
                                        FROM logs_acceso l 
                                        LEFT JOIN usuarios u ON l.usuarioid = u.id 
                                        ORDER BY l.fecha_hora DESC LIMIT 50");
        $logs = $stmt_logs->fetchAll();

        // 5. Fetch Recent Audits (Changes)
        $stmt_audits = $this->pdo->query("SELECT a.*, u.nombre as usuario 
                                          FROM auditorias a 
                                          LEFT JOIN usuarios u ON a.usuarioid = u.id 
                                          ORDER BY a.fecha_hora DESC LIMIT 20");
        $audits = $stmt_audits->fetchAll();

        require_once BASE_PATH . '/views/admin/security/index.php';
    }
}

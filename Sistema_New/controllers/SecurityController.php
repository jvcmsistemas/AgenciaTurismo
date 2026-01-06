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
        $stmt_logins = $this->pdo->query("SELECT COUNT(*) FROM logs_acceso WHERE accion = 'login' AND DATE(fecha_hora) = CURDATE()");
        $totalLoginsToday = $stmt_logins->fetchColumn();

        // 2. Fetch KPI: Failed Attempts Today
        $stmt_failed = $this->pdo->query("SELECT COUNT(*) FROM logs_acceso WHERE estado = 'fallido' AND DATE(fecha_hora) = CURDATE()");
        $totalFailedToday = $stmt_failed->fetchColumn();

        // 3. Fetch KPI: Active Change Audit (Today)
        $stmt_sessions = $this->pdo->query("SELECT COUNT(*) FROM auditoria WHERE DATE(fecha_hora) = CURDATE()");
        $activeAuditsToday = $stmt_sessions->fetchColumn();

        // 4. Fetch Recent Logs (Access)
        $stmt_logs = $this->pdo->query("SELECT l.*, u.nombre as usuario, u.email 
                                        FROM logs_acceso l 
                                        LEFT JOIN usuarios u ON l.usuario_id = u.id 
                                        ORDER BY l.fecha_hora DESC LIMIT 50");
        $logs = $stmt_logs->fetchAll();

        // 5. Fetch Recent Audits (Changes)
        $stmt_audits = $this->pdo->query("SELECT a.*, u.nombre as usuario 
                                          FROM auditoria a 
                                          LEFT JOIN usuarios u ON a.usuario_id = u.id 
                                          ORDER BY a.fecha_hora DESC LIMIT 20");
        $audits = $stmt_audits->fetchAll();

        // 6. Data for Security Trends Chart (Last 7 days)
        $stmt_trends = $this->pdo->query("
            SELECT DATE(fecha_hora) as fecha, 
                   SUM(CASE WHEN estado = 'fallido' THEN 1 ELSE 0 END) as fallidos,
                   SUM(CASE WHEN estado = 'exitoso' THEN 1 ELSE 0 END) as exitosos
            FROM logs_acceso 
            WHERE fecha_hora >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY fecha 
            ORDER BY fecha ASC
        ");
        $securityTrends = $stmt_trends->fetchAll();

        require_once BASE_PATH . '/views/admin/security/index.php';
    }
}

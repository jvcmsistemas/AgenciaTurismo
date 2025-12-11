<?php
// Sistema_New/controllers/SettingsController.php

require_once BASE_PATH . '/models/Setting.php';

class SettingsController
{
    private $pdo;
    private $settingModel;

    public function __construct($pdo)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador_general') {
            redirect('login');
        }
        $this->pdo = $pdo;
        $this->settingModel = new Setting($pdo);
    }

    public function index()
    {
        $groupedSettings = $this->settingModel->getGrouped();
        require_once BASE_PATH . '/views/admin/settings/index.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST as $key => $value) {
                // Ignorar campos que no son settings
                if ($key !== 'action') {
                    $this->settingModel->update($key, $value);
                }
            }
            redirect('admin/settings?success=1');
        }
    }

    public function backup()
    {
        // Simple Backup Logic for MySQL
        // Hardcoded credentials to work around constant limitations in this context
        $dbHost = '127.0.0.1';
        $dbUser = 'root';
        $dbPass = '';
        $dbName = 'agencia_turismo_db';

        $backupFile = 'backup_' . date('Y-m-d_H-i-s') . '.sql';

        // Command for Windows (mysqldump path might vary, assuming it's in path or relative)
        // For XAMPP default: c:\xampp\mysql\bin\mysqldump
        $dumpPath = 'c:\xampp\mysql\bin\mysqldump.exe'; // Ajustar si es necesario
        if (!file_exists($dumpPath)) {
            $dumpPath = 'mysqldump'; // Try global path
        }

        $command = "\"$dumpPath\" --user=$dbUser --password=$dbPass --host=$dbHost $dbName > \"$backupFile\"";

        system($command, $output);

        if (file_exists($backupFile)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($backupFile) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($backupFile));
            readfile($backupFile);
            unlink($backupFile); // Delete after download
            exit;
        } else {
            redirect('admin/settings?error=backup_failed');
        }
    }
}

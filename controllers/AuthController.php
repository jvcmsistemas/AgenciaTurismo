<?php
// Sistema_New/controllers/AuthController.php

require_once BASE_PATH . '/models/User.php';

class AuthController
{
    private $pdo;
    private $userModel;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->userModel = new User($pdo);
    }

    public function login()
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = "Por favor ingrese email y contraseña.";
            } else {
                $user = $this->userModel->findByEmail($email);

                if ($user && password_verify($password, $user['contrasena'])) {
                    // Verificar que NO sea administrador
                    if ($user['rol'] === 'administrador_general') {
                        $error = "Acceso restringido. Los administradores deben usar el Portal Administrativo.";
                    } elseif ($user['agencia_estado'] !== 'activa') {
                        $error = "Su cuenta de agencia está " . ($user['agencia_estado'] ?? 'inactiva') . ". Por favor, contacte al soporte.";
                        $this->auditAccess($user['id'], 'bloqueado_agencia_inactiva');
                    } else {
                        // Login exitoso para Agencias
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['nombre'] . ' ' . $user['apellido'];
                        $_SESSION['user_role'] = $user['rol'];
                        $_SESSION['agencia_id'] = $user['agencia_id'];

                        // Regenerar ID de sesión por seguridad
                        session_regenerate_id(true);

                        // Actualizar último acceso
                        $this->userModel->updateLastLogin($user['id']);

                        // Auditoría de acceso exitoso
                        $this->auditAccess($user['id'], 'exitoso');

                        redirect('dashboard');
                    }
                } else {
                    $error = "Credenciales incorrectas.";
                    // Auditoría de acceso fallido (si el usuario existe)
                    if ($user) {
                        $this->auditAccess($user['id'], 'fallido');
                    }
                }
            }
        }

        // Cargar vista premium para Agencias
        require_once BASE_PATH . '/views/auth/login_agency.php';
    }

    private function auditAccess($userId, $estado)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO logs_acceso (usuario_id, accion, recurso, ip_origen, user_agent, estado) 
                                        VALUES (:usuario_id, 'login', 'portal_agencia', :ip, :ua, :estado)");
            $stmt->execute([
                'usuario_id' => $userId,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'ua' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                'estado' => $estado
            ]);
        } catch (Exception $e) {
            // No bloquear el login si falla la auditoría, pero registrar error si es necesario
        }
    }

    public function loginAdmin()
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = "Por favor ingrese email y contraseña.";
            } else {
                $user = $this->userModel->findByEmail($email);

                if ($user && password_verify($password, $user['contrasena'])) {
                    if ($user['rol'] === 'administrador_general') {
                        // Login exitoso
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['nombre'];
                        $_SESSION['user_role'] = $user['rol'];
                        $_SESSION['agencia_id'] = $user['agencia_id'];
                        redirect('admin/dashboard');
                    } else {
                        $error = "Acceso no autorizado para este rol.";
                    }
                } else {
                    $error = "Credenciales incorrectas.";
                }
            }
        }

        // Cargar vista de Admin
        require_once BASE_PATH . '/views/auth/login_admin.php';
    }

    public function logout()
    {
        session_destroy();
        redirect('login');
    }

    // --- Recuperación de Contraseña ---

    public function showForgotPassword()
    {
        require_once BASE_PATH . '/views/auth/forgot_password.php';
    }

    public function sendResetLink()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $user = $this->userModel->findByEmail($email);

            if ($user) {
                // Generar token único
                $token = bin2hex(random_bytes(32));

                // Guardar token en BD (Simulado: Debería haber una tabla password_resets)
                // Por ahora usaremos la tabla que creamos
                $stmt = $this->pdo->prepare("INSERT INTO password_resets (email, token) VALUES (:email, :token)");
                $stmt->execute(['email' => $email, 'token' => $token]);

                // Simular envío de correo
                $resetLink = BASE_URL . "reset-password?token=" . $token . "&email=" . urlencode($email);
                $success = "Hemos enviado un enlace de recuperación a tu correo.";
            } else {
                // Por seguridad, no decimos si el correo existe o no, pero para demo sí
                $error = "No encontramos un usuario con ese correo.";
            }
        }
        require_once BASE_PATH . '/views/auth/forgot_password.php';
    }

    public function showResetPassword()
    {
        $token = $_GET['token'] ?? '';
        $email = $_GET['email'] ?? '';

        // Validar token (Simple check)
        $stmt = $this->pdo->prepare("SELECT * FROM password_resets WHERE email = :email AND token = :token LIMIT 1");
        $stmt->execute(['email' => $email, 'token' => $token]);
        $resetRequest = $stmt->fetch();

        if (!$resetRequest) {
            die("Enlace inválido o expirado.");
        }

        require_once BASE_PATH . '/views/auth/reset_password.php';
    }

    public function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordConfirm = $_POST['password_confirm'];

            if ($password !== $passwordConfirm) {
                $error = "Las contraseñas no coinciden.";
                require_once BASE_PATH . '/views/auth/reset_password.php';
                return;
            }

            // Actualizar contraseña
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("UPDATE usuarios SET contrasena = :password WHERE email = :email");
            $stmt->execute(['password' => $hashedPassword, 'email' => $email]);

            // Eliminar token usado
            $stmt = $this->pdo->prepare("DELETE FROM password_resets WHERE email = :email");
            $stmt->execute(['email' => $email]);

            // Redirigir al login con éxito
            // Podríamos pasar un mensaje flash, pero por ahora simple redirect
            redirect('login');
        }
    }
}

<?php
// Sistema_New/index.php

// Error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define constants
define('BASE_PATH', __DIR__);
define('BASE_URL', '/AgenciaTurismo/Sistema_New/');

// Autoloader (Simple manual require for now, can be upgraded to Composer later)
require_once BASE_PATH . '/config/db.php';
require_once BASE_PATH . '/includes/functions.php';

// Simple Router
$request = $_SERVER['REQUEST_URI'];
$path = str_replace(BASE_URL, '', $request);
$path = strtok($path, '?'); // Remove query string

// Iniciar sesión PHP
session_start();

switch ($path) {
    case '':
    case 'index.php':
    case 'dashboard':
        require_once BASE_PATH . '/controllers/AgencyController.php';
        $agencyController = new AgencyController($pdo);
        $agencyController->index();
        break;
    case 'login':
        require_once BASE_PATH . '/controllers/AuthController.php';
        $auth = new AuthController($pdo);
        $auth->login();
        break;
    case 'admin/login':
        require_once BASE_PATH . '/controllers/AuthController.php';
        $auth = new AuthController($pdo);
        $auth->loginAdmin();
        break;
    case 'logout':
        require_once BASE_PATH . '/controllers/AuthController.php';
        $auth = new AuthController($pdo);
        $auth->logout();
        break;

    // Recuperación de Contraseña
    case 'forgot-password':
        require_once BASE_PATH . '/controllers/AuthController.php';
        $auth = new AuthController($pdo);
        $auth->showForgotPassword();
        break;
    case 'forgot-password/send':
        require_once BASE_PATH . '/controllers/AuthController.php';
        $auth = new AuthController($pdo);
        $auth->sendResetLink();
        break;
    case 'reset-password':
        require_once BASE_PATH . '/controllers/AuthController.php';
        $auth = new AuthController($pdo);
        $auth->showResetPassword();
        break;
    case 'admin/dashboard':
        require_once BASE_PATH . '/controllers/AdminController.php';
        $admin = new AdminController($pdo);
        $admin->index();
        break;
    case 'admin/agencies':
        require_once BASE_PATH . '/controllers/AdminController.php';
        $admin = new AdminController($pdo);
        $admin->agencies();
        break;
    case 'admin/agencies/create':
        require_once BASE_PATH . '/controllers/AdminController.php';
        $admin = new AdminController($pdo);
        $admin->create();
        break;
    case 'admin/agencies/store':
        require_once BASE_PATH . '/controllers/AdminController.php';
        $admin = new AdminController($pdo);
        $admin->store();
        break;
    case 'admin/agencies/edit':
        require_once BASE_PATH . '/controllers/AdminController.php';
        $admin = new AdminController($pdo);
        $id = $_GET['id'] ?? null;
        if ($id) {
            $admin->edit($id);
        } else {
            redirect('admin/dashboard');
        }
        break;
    case 'admin/agencies/update':
        require_once BASE_PATH . '/controllers/AdminController.php';
        $admin = new AdminController($pdo);
        $admin->update();
        break;
    case 'admin/users':
        require_once BASE_PATH . '/controllers/UserController.php';
        $userController = new UserController($pdo);
        $userController->index();
        break;

    // RUTAS DE AGENCIA
    case 'agency/tours':
        require_once BASE_PATH . '/controllers/TourController.php';
        $tourController = new TourController($pdo);
        $tourController->index();
        break;
    case 'agency/tours/create':
        require_once BASE_PATH . '/controllers/TourController.php';
        $tourController = new TourController($pdo);
        $tourController->create();
        break;
    case 'agency/tours/store':
        require_once BASE_PATH . '/controllers/TourController.php';
        $tourController = new TourController($pdo);
        $tourController->store();
        break;
    case 'agency/tours/edit':
        require_once BASE_PATH . '/controllers/TourController.php';
        $tourController = new TourController($pdo);
        $tourController->edit($_GET['id']);
        break;
    case 'agency/tours/update':
        require_once BASE_PATH . '/controllers/TourController.php';
        $tourController = new TourController($pdo);
        $tourController->update();
        break;
    case 'agency/tours/delete':
        require_once BASE_PATH . '/controllers/TourController.php';
        $tourController = new TourController($pdo);
        $tourController->delete($_GET['id']);
        break;

    // RUTAS DE RESERVAS
    case 'agency/reservations':
        require_once BASE_PATH . '/controllers/ReservationController.php';
        $resController = new ReservationController($pdo);
        $resController->index();
        break;
    case 'agency/reservations/update_status':
        require_once BASE_PATH . '/controllers/ReservationController.php';
        $resController = new ReservationController($pdo);
        $resController->updateStatus();
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}

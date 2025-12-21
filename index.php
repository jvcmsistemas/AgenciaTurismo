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

    // ADMIN: PERFIL
    case 'admin/profile':
        require_once BASE_PATH . '/controllers/AdminController.php';
        $admin = new AdminController($pdo);
        $admin->profile();
        break;
    case 'admin/profile/update':
        require_once BASE_PATH . '/controllers/AdminController.php';
        $admin = new AdminController($pdo);
        $admin->updateProfile();
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
    case 'admin/agencies/toggle_status':
        require_once BASE_PATH . '/controllers/AdminController.php';
        $admin = new AdminController($pdo);
        $admin->toggleStatus();
        break;
    case 'admin/users':
        require_once BASE_PATH . '/controllers/UserController.php';
        $userController = new UserController($pdo);
        $userController->index();
        break;

    // ADMIN: PLANES DE SUSCRIPCIÓN
    case 'admin/plans':
        require_once BASE_PATH . '/controllers/PlansController.php';
        $controller = new PlansController($pdo);
        $controller->index();
        break;
    case 'admin/plans/create':
        require_once BASE_PATH . '/controllers/PlansController.php';
        $controller = new PlansController($pdo);
        $controller->create();
        break;
    case 'admin/plans/store':
        require_once BASE_PATH . '/controllers/PlansController.php';
        $controller = new PlansController($pdo);
        $controller->store();
        break;
    case 'admin/plans/edit':
        require_once BASE_PATH . '/controllers/PlansController.php';
        $controller = new PlansController($pdo);
        $controller->edit($_GET['id']);
        break;
    case 'admin/plans/update':
        require_once BASE_PATH . '/controllers/PlansController.php';
        $controller = new PlansController($pdo);
        $controller->update();
        break;
    case 'admin/plans/delete':
        require_once BASE_PATH . '/controllers/PlansController.php';
        $controller = new PlansController($pdo);
        $controller->delete($_GET['id']);
        break;

    case 'admin/payments':
        require_once BASE_PATH . '/controllers/PaymentsController.php';
        $controller = new PaymentsController($pdo);
        $controller->index();
        break;

    case 'admin/security':
        require_once BASE_PATH . '/controllers/SecurityController.php';
        $controller = new SecurityController($pdo);
        $controller->index();
        break;

    case 'admin/settings':
        require_once BASE_PATH . '/controllers/SettingsController.php';
        $controller = new SettingsController($pdo);
        $controller->index();
        break;

    case 'admin/settings/update':
        require_once BASE_PATH . '/controllers/SettingsController.php';
        $controller = new SettingsController($pdo);
        $controller->update();
        break;

    case 'admin/settings/backup':
        require_once BASE_PATH . '/controllers/SettingsController.php';
        $controller = new SettingsController($pdo);
        $controller->backup();
        break;

    case 'admin/reports':
        require_once BASE_PATH . '/controllers/ReportsController.php';
        $controller = new ReportsController($pdo);
        $controller->index();
        break;

    case 'admin/support':
        require_once BASE_PATH . '/controllers/SupportController.php';
        $controller = new SupportController($pdo);
        $controller->index();
        break;

    case 'admin/support/show':
        require_once BASE_PATH . '/controllers/SupportController.php';
        $controller = new SupportController($pdo);
        $controller->show();
        break;

    case 'admin/support/create':
        require_once BASE_PATH . '/controllers/SupportController.php';
        $controller = new SupportController($pdo);
        $controller->create();
        break;

    case 'admin/support/reply':
        require_once BASE_PATH . '/controllers/SupportController.php';
        $controller = new SupportController($pdo);
        $controller->reply();
        break;

    case 'admin/support/close':
        require_once BASE_PATH . '/controllers/SupportController.php';
        $controller = new SupportController($pdo);
        $controller->close();
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
    case 'agency/reservations/create':
        require_once BASE_PATH . '/controllers/ReservationController.php';
        $resController = new ReservationController($pdo);
        $resController->create();
        break;
    case 'agency/reservations/store':
        require_once BASE_PATH . '/controllers/ReservationController.php';
        $resController = new ReservationController($pdo);
        $resController->store();
        break;
    case 'agency/reservations/get-departures':
        require_once BASE_PATH . '/controllers/ReservationController.php';
        $resController = new ReservationController($pdo);
        $resController->getDepartures();
        break;
    case 'agency/reservations/update_status':
        require_once BASE_PATH . '/controllers/ReservationController.php';
        $resController = new ReservationController($pdo);
        $resController->updateStatus();
        break;
    case 'agency/reservations/show':
        require_once BASE_PATH . '/controllers/ReservationController.php';
        $resController = new ReservationController($pdo);
        $resController->show();
        break;
    case 'agency/reservations/payment/add':
        require_once BASE_PATH . '/controllers/ReservationController.php';
        $resController = new ReservationController($pdo);
        $resController->addPayment();
        break;

    // RUTAS DE SALIDAS (DEPARTURES)
    case 'agency/departures':
        require_once BASE_PATH . '/controllers/DepartureController.php';
        $controller = new DepartureController($pdo);
        $controller->index();
        break;
    case 'agency/departures/create':
        require_once BASE_PATH . '/controllers/DepartureController.php';
        $controller = new DepartureController($pdo);
        $controller->create();
        break;
    case 'agency/departures/store':
        require_once BASE_PATH . '/controllers/DepartureController.php';
        $controller = new DepartureController($pdo);
        $controller->store();
        break;
    case 'agency/departures/edit':
        require_once BASE_PATH . '/controllers/DepartureController.php';
        $controller = new DepartureController($pdo);
        $controller->edit($_GET['id']);
        break;
    case 'agency/departures/update':
        require_once BASE_PATH . '/controllers/DepartureController.php';
        $controller = new DepartureController($pdo);
        $controller->update();
        break;
    case 'agency/departures/delete':
        require_once BASE_PATH . '/controllers/DepartureController.php';
        $controller = new DepartureController($pdo);
        $controller->delete($_GET['id']);
        break;

    // --- MÓDULO CLIENTES ---
    case 'agency/clients':
        require_once BASE_PATH . '/controllers/ClientController.php';
        $controller = new ClientController($pdo);
        $controller->index();
        break;
    case 'agency/clients/create':
        require_once BASE_PATH . '/controllers/ClientController.php';
        $controller = new ClientController($pdo);
        $controller->create();
        break;
    case 'agency/clients/store':
        require_once BASE_PATH . '/controllers/ClientController.php';
        $controller = new ClientController($pdo);
        $controller->store();
        break;
    case 'agency/clients/edit':
        require_once BASE_PATH . '/controllers/ClientController.php';
        $controller = new ClientController($pdo);
        $controller->edit($_GET['id']);
        break;
    case 'agency/clients/update':
        require_once BASE_PATH . '/controllers/ClientController.php';
        $controller = new ClientController($pdo);
        $controller->update();
        break;
    case 'agency/clients/delete':
        require_once BASE_PATH . '/controllers/ClientController.php';
        $controller = new ClientController($pdo);
        $controller->delete($_GET['id']);
        break;
    case 'agency/clients/search-api': // Ruta para AJAX
        require_once BASE_PATH . '/controllers/ClientController.php';
        $controller = new ClientController($pdo);
        $controller->searchApi();
        break;

    // RUTAS DE RECURSOS (AGENCIA)
    case 'agency/resources':
        require_once BASE_PATH . '/controllers/ResourceController.php';
        $controller = new ResourceController($pdo);
        $controller->index();
        break;
    case 'agency/resources/get-by-type': // API
        require_once BASE_PATH . '/controllers/ResourceController.php';
        $controller = new ResourceController($pdo);
        $controller->getByTypeApi();
        break;
    case 'agency/resources/store-guide':
        require_once BASE_PATH . '/controllers/ResourceController.php';
        $controller = new ResourceController($pdo);
        $controller->storeGuide();
        break;
    case 'agency/resources/delete-guide':
        require_once BASE_PATH . '/controllers/ResourceController.php';
        $controller = new ResourceController($pdo);
        $controller->deleteGuide();
        break;
    case 'agency/resources/store-transport':
        require_once BASE_PATH . '/controllers/ResourceController.php';
        $controller = new ResourceController($pdo);
        $controller->storeTransport();
        break;
    case 'agency/resources/delete-transport':
        require_once BASE_PATH . '/controllers/ResourceController.php';
    case 'agency/resources/update-guide':
        require_once BASE_PATH . '/controllers/ResourceController.php';
        $controller = new ResourceController($pdo);
        $controller->updateGuide();
        break;
    case 'agency/resources/update-transport':
        require_once BASE_PATH . '/controllers/ResourceController.php';
        $controller = new ResourceController($pdo);
        $controller->updateTransport();
        break;
    case 'agency/resources/update-provider':
        require_once BASE_PATH . '/controllers/ResourceController.php';
        $controller = new ResourceController($pdo);
        $controller->updateProvider();
        break;

    // RUTAS DE PERFIL
    case 'agency/profile':
        require_once BASE_PATH . '/controllers/AgencyController.php';
        $agencyController = new AgencyController($pdo);
        $agencyController->profile();
        break;
    case 'agency/profile/update':
        require_once BASE_PATH . '/controllers/AgencyController.php';
        $agencyController = new AgencyController($pdo);
        $agencyController->updateProfile();
        break;

    default:
        http_response_code(404);
        echo "404 Not Found. Path attempted: [" . htmlspecialchars($path) . "]";
        break;
}

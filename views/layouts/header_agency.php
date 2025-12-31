<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agencia de Turismo - Panel</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css?v=<?php echo time() . rand(1, 1000); ?>">

    <style>
        /* CRITICAL: Force Sidebar Mobile Styles Inline */
        @media (max-width: 768px) {
            #sidebar {
                position: fixed !important;
                top: 0;
                left: -100% !important;
                height: 100vh;
                z-index: 10001 !important;
                margin-left: 0 !important;
                transition: left 0.3s ease-in-out;
                background-color: var(--primary-color) !important;
                color: #ffffff !important;
                overflow-y: auto;
                width: 280px !important;
            }

            body.mobile-sidebar-active #sidebar {
                left: 0 !important;
            }
        }
    </style>

    <script>
        window.handleSidebarToggle = function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (window.innerWidth > 768) {
                document.body.classList.toggle('sidebar-toggled');
            } else {
                document.body.classList.add('mobile-sidebar-active');
                if (document.getElementById('sidebar-overlay'))
                    document.getElementById('sidebar-overlay').style.display = 'block';
            }
        };

        window.closeMobileMenu = function (e) {
            if (e) { e.preventDefault(); e.stopPropagation(); }
            document.body.classList.remove('mobile-sidebar-active');
            if (document.getElementById('sidebar-overlay'))
                document.getElementById('sidebar-overlay').style.display = 'none';
        }
    </script>
</head>

<body class="<?php
$theme = 'agency-theme';
if (strpos($_SERVER['REQUEST_URI'], 'admin/login') !== false || ($_SESSION['user_role'] ?? '') === 'administrador_general') {
    $theme = 'superadmin-theme';
}
echo $theme;
?>">

    <?php
    // Detectar si es página de login para no mostrar sidebar
    $isLoginPage = strpos($_SERVER['REQUEST_URI'], 'login') !== false;

    function isActive($path)
    {
        $uri = $_SERVER['REQUEST_URI'];
        // Ajuste para Dashboard de Agencia
        if ($path === 'dashboard') {
            return (strpos($uri, 'dashboard') !== false) ? 'active' : '';
        }
        return (strpos($uri, $path) !== false) ? 'active' : '';
    }
    ?>

    <?php if (!$isLoginPage && isset($_SESSION['user_id'])): ?>
        <div class="wrapper">
            <!-- Sidebar -->
            <nav id="sidebar" class="agency-sidebar">
                <div class="sidebar-header">
                    <h4 class="fw-bold mb-0">Mi Agencia</h4>
                    <small class="text-white-50">Panel de Gestión</small>
                </div>

                <ul class="list-unstyled components">
                    <li class="<?php echo isActive('dashboard'); ?>">
                        <a href="<?php echo BASE_URL; ?>dashboard">
                            <i class="bi bi-speedometer2 me-2"></i> Inicio
                        </a>
                    </li>
                    <li class="<?php echo isActive('agency/reservations'); ?>">
                        <a href="<?php echo BASE_URL; ?>agency/reservations">
                            <i class="bi bi-calendar-check me-2"></i> Reservas
                        </a>
                    </li>
                    <li class="<?php echo isActive('agency/payments'); ?>">
                        <a href="<?php echo BASE_URL; ?>agency/payments">
                            <i class="bi bi-cash-coin me-2"></i> Pagos
                        </a>
                    </li>
                    <li class="<?php echo isActive('agency/clients'); ?>">
                        <a href="<?php echo BASE_URL; ?>agency/clients">
                            <i class="bi bi-people me-2"></i> Clientes
                        </a>
                    </li>
                    <li class="<?php echo isActive('agency/tours'); ?>">
                        <a href="<?php echo BASE_URL; ?>agency/tours">
                            <i class="bi bi-map me-2"></i> Tours
                        </a>
                    </li>
                    <li class="<?php echo isActive('agency/departures'); ?>">
                        <a href="<?php echo BASE_URL; ?>agency/departures">
                            <i class="bi bi-calendar-week me-2"></i> Salidas
                        </a>
                    </li>
                    <li class="<?php echo isActive('agency/guides'); ?>">
                        <a href="<?php echo BASE_URL; ?>agency/guides">
                            <i class="bi bi-person-badge me-2"></i> Guías
                        </a>
                    </li>
                    <li class="<?php echo isActive('agency/transports'); ?>">
                        <a href="<?php echo BASE_URL; ?>agency/transports">
                            <i class="bi bi-truck me-2"></i> Transportes
                        </a>
                    </li>
                    <li class="<?php echo isActive('agency/providers'); ?>">
                        <a href="<?php echo BASE_URL; ?>agency/providers">
                            <i class="bi bi-shop me-2"></i> Proveedores
                        </a>
                    </li>
                    <li class="<?php echo isActive('agency/reports'); ?>">
                        <a href="<?php echo BASE_URL; ?>agency/reports">
                            <i class="bi bi-bar-chart me-2"></i> Reportes
                        </a>
                    </li>
                    <li class="<?php echo isActive('agency/audit'); ?>">
                        <a href="<?php echo BASE_URL; ?>agency/audit">
                            <i class="bi bi-eye me-2"></i> Auditoría
                        </a>
                    </li>
                    <li class="<?php echo isActive('agency/users'); ?>">
                        <a href="<?php echo BASE_URL; ?>agency/users">
                            <i class="bi bi-person-gear me-2"></i> Usuarios
                        </a>
                    </li>
                    <li class="<?php echo isActive('agency/settings'); ?>">
                        <a href="<?php echo BASE_URL; ?>agency/settings">
                            <i class="bi bi-gear me-2"></i> Configuración
                        </a>
                    </li>
                    <li class="<?php echo isActive('agency/support'); ?>">
                        <a href="<?php echo BASE_URL; ?>agency/support">
                            <i class="bi bi-life-preserver me-2"></i> Soporte
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Page Content -->
            <div id="content">
                <nav class="navbar navbar-expand-lg navbar-light glass-header navbar-custom">
                    <div class="container-fluid">
                        <button type="button" id="sidebarCollapse" class="btn btn-outline-primary"
                            onclick="handleSidebarToggle(event)">
                            <i class="bi bi-list fs-4"></i>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="nav navbar-nav ms-auto align-items-center">
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <li class="nav-item me-3">
                                        <div class="theme-toggle-container">
                                            <span class="theme-toggle-label">☀️</span>
                                            <label class="theme-toggle-switch">
                                                <input type="checkbox" id="themeToggle">
                                                <span class="theme-toggle-slider"></span>
                                            </label>
                                            <span class="theme-toggle-label">🌙</span>
                                        </div>
                                    </li>
                                <?php endif; ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle fw-bold text-primary" href="#" id="navbarDropdown"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-person-circle me-1"></i> <?php echo $_SESSION['user_name']; ?>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end glass-card border-0"
                                        aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>agency/profile">Mi
                                                Perfil</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>logout">Cerrar
                                                Sesión</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <div class="container-fluid p-4">
                <?php else: ?>
                    <!-- Contenedor simple para login -->
                    <div class="container-fluid p-0">
                    <?php endif; ?>
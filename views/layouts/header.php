<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agencia de Turismo</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css?v=<?php echo time() . rand(1, 1000); ?>">

    <style>
        /* CRITICAL: Force Sidebar Mobile Styles Inline (Bypasses external CSS issues) */
        @media (max-width: 768px) {
            #sidebar {
                position: fixed !important;
                top: 0;
                left: -100% !important;
                /* Start hidden */
                height: 100vh;
                z-index: 10001 !important;
                /* Above overlay (9990) */
                margin-left: 0 !important;
                transition: left 0.3s ease-in-out;
                background-color: #0f3460 !important;
                color: #ffffff !important;
                overflow-y: auto;
                width: 280px !important;
            }

            /* Logic for Open State */
            body.mobile-sidebar-active #sidebar {
                left: 0 !important;
            }
        }
    </style>

    <script>
        // Universal Sidebar Handler
        window.handleSidebarToggle = function (e) {
            e.preventDefault();
            e.stopPropagation();

            if (window.innerWidth > 768) {
                // Desktop: Toggle Collapse
                document.body.classList.toggle('sidebar-toggled');
            } else {
                // Mobile: Open Overlay Mode
                document.body.classList.add('mobile-sidebar-active');
                const overlay = document.getElementById('sidebar-overlay');
                if (overlay) overlay.style.display = 'block';
            }
        };

        // Mobile Only Close Function
        window.closeMobileMenu = function (e) {
            if (e) { e.preventDefault(); e.stopPropagation(); }

            document.body.classList.remove('mobile-sidebar-active');
            const overlay = document.getElementById('sidebar-overlay');
            if (overlay) overlay.style.display = 'none';
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
    ?>

    <?php if (!$isLoginPage && isset($_SESSION['user_id'])): ?>
        <div class="wrapper">
            <!-- Sidebar -->
            <nav id="sidebar">
                <div class="sidebar-header position-relative">
                    <h4 class="fw-bold mb-0">Turismo Oxapampa</h4>
                    <small class="text-white-50">Sistema de Gestión</small>
                    <!-- Mobile Close Button -->
                    <button type="button" id="sidebarCloseMobile"
                        class="btn-close btn-close-white position-absolute top-0 end-0 m-3 d-md-none" aria-label="Close"
                        onclick="closeMobileMenu(event)"></button>
                </div>

                <ul class="list-unstyled components">
                    <?php if ($_SESSION['user_role'] === 'administrador_general'): ?>
                        <li>
                            <a href="<?php echo BASE_URL; ?>admin/dashboard"><i class="bi bi-speedometer2 me-2"></i>
                                Dashboard</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>admin/agencies"><i class="bi bi-building me-2"></i> Agencias</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>admin/users"><i class="bi bi-people me-2"></i> Usuarios Globales</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>admin/payments"><i class="bi bi-cash-stack me-2"></i> Pagos &
                                Facturación</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>admin/plans"><i class="bi bi-journal-check me-2"></i> Planes
                                Suscripción</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>admin/security"><i class="bi bi-shield-lock me-2"></i> Seguridad &
                                Auditoría</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>admin/settings"><i class="bi bi-gear me-2"></i> Configuración</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>admin/reports"><i class="bi bi-bar-chart me-2"></i> Reportes</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>admin/support"><i class="bi bi-life-preserver me-2"></i> Soporte</a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="<?php echo BASE_URL; ?>dashboard"><i class="bi bi-speedometer2 me-2"></i> Inicio</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>agency/reservations"><i class="bi bi-calendar-check me-2"></i>
                                Reservas</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>agency/tours"><i class="bi bi-map me-2"></i> Tours</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>agency/clients"><i class="bi bi-people me-2"></i> Clientes</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>agency/resources"><i class="bi bi-briefcase me-2"></i> Recursos</a>
                        </li>
                    <?php endif; ?>
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
                                <?php if (($_SESSION['user_role'] ?? '') === 'administrador_general'): ?>
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
                                    <a class="nav-link dropdown-toggle fw-bold" href="#" id="navbarDropdown" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-person-circle me-1"></i> <?php echo $_SESSION['user_name']; ?>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end glass-card border-0"
                                        aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin/profile">Perfil</a>
                                        </li>
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
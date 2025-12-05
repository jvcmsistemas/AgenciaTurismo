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
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
</head>

<body>

    <?php
    // Detectar si es página de login para no mostrar sidebar
    $isLoginPage = strpos($_SERVER['REQUEST_URI'], 'login') !== false;
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
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2 <?php echo (strpos($_SERVER['REQUEST_URI'], 'agency/dashboard') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo BASE_URL; ?>agency/dashboard">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2 <?php echo (strpos($_SERVER['REQUEST_URI'], 'agency/departures') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo BASE_URL; ?>agency/departures">
                            <i class="bi bi-calendar-week"></i> Salidas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2 <?php echo (strpos($_SERVER['REQUEST_URI'], 'agency/tours') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo BASE_URL; ?>agency/tours">
                            <i class="bi bi-map"></i> Mis Tours
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2 <?php echo (strpos($_SERVER['REQUEST_URI'], 'agency/reservations') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo BASE_URL; ?>agency/reservations">
                            <i class="bi bi-calendar-check"></i> Reservas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2 <?php echo (strpos($_SERVER['REQUEST_URI'], 'agency/resources') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo BASE_URL; ?>agency/resources">
                            <i class="bi bi-box-seam"></i> Recursos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2 <?php echo (strpos($_SERVER['REQUEST_URI'], 'agency/clients') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo BASE_URL; ?>agency/clients">
                            <i class="bi bi-people"></i> Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2 <?php echo (strpos($_SERVER['REQUEST_URI'], 'agency/profile') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo BASE_URL; ?>agency/profile">
                            <i class="bi bi-person-gear"></i> Perfil
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Page Content -->
            <div id="content">
                <nav class="navbar navbar-expand-lg navbar-light glass-header navbar-custom">
                    <div class="container-fluid">
                        <button type="button" id="sidebarCollapse" class="btn btn-light text-primary">
                            <i class="bi bi-list fs-4"></i>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="nav navbar-nav ms-auto">
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
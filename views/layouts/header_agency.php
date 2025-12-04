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
                    <li>
                        <a href="<?php echo BASE_URL; ?>dashboard"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>agency/tours"><i class="bi bi-map me-2"></i> Mis Tours</a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>agency/reservations"><i class="bi bi-calendar-check me-2"></i>
                            Reservas</a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>agency/resources"><i class="bi bi-box-seam me-2"></i> Recursos</a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>agency/clients"><i class="bi bi-people me-2"></i> Clientes</a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>agency/profile"><i class="bi bi-person-gear me-2"></i> Perfil</a>
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
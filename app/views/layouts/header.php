<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/sistema/css/general.css?v=1.2">
    <?php
    $route = $_GET['route'] ?? 'dashboard/index';
    $routeParts = explode('/', $route);
    $module = $routeParts[0];

    // Include specific CSS based on module
    switch ($module) {
        case 'dashboard':
            echo '<link rel="stylesheet" href="/sistema/css/dashboard.css?v=1.2">';
            break;
        case 'inventario':
            echo '<link rel="stylesheet" href="/sistema/css/inventario.css?v=1.2">';
            echo '<link rel="stylesheet" href="/sistema/css/ventas.css?v=1.2">';
            break;
        case 'ventas':
            echo '<link rel="stylesheet" href="/sistema/css/ventas.css?v=1.2">';
            break;
        case 'reportes':
            echo '<link rel="stylesheet" href="/sistema/css/reportes.css?v=1.2">';
            break;
        case 'usuario':
            echo '<link rel="stylesheet" href="/sistema/css/usuario.css?v=1.2">';
            echo '<link rel="stylesheet" href="/sistema/css/ventas.css?v=1.2">';
            break;
        case 'auth':
            echo '<link rel="stylesheet" href="/sistema/css/login.css?v=1.2">';
            break;
    }
    ?>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="top-navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <i class="fas fa-boxes"></i>
                <span>Sistema de Inventario</span>
            </div>

            <div class="nav-menu">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php $rol = $_SESSION['rol']; ?>
                    <a href="/sistema/?route=dashboard" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    <?php if ($rol == 'admin' || $rol == 'vendedor'): ?>
                        <a href="/sistema/?route=ventas" class="nav-link">
                            <i class="fas fa-cash-register"></i>
                            <span>Ventas</span>
                        </a>
                    <?php endif; ?>
                    <?php if ($rol == 'admin' || $rol == 'inventario'): ?>
                        <a href="/sistema/?route=inventario" class="nav-link">
                            <i class="fas fa-warehouse"></i>
                            <span>Inventario</span>
                        </a>
                    <?php endif; ?>
                    <?php if ($rol == 'admin'): ?>
                        <a href="/sistema/?route=reportes" class="nav-link">
                            <i class="fas fa-chart-bar"></i>
                            <span>Reportes</span>
                        </a>
                        <a href="/sistema/?route=usuario" class="nav-link">
                            <i class="fas fa-users"></i>
                            <span>Usuarios</span>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="nav-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-info">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo $_SESSION['user']['nombre'] ?? 'Usuario'; ?></span>
                    </div>
                    <a href="/sistema/?route=auth/logout" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Salir</span>
                    </a>
                <?php else: ?>
                    <a href="/sistema/?route=auth/login" class="login-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Iniciar Sesión</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
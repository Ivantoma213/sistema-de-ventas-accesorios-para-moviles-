<?php
// Inicializar aplicación
require_once __DIR__ . '/bootstrap.php';

// Obtener parámetros de la URL (query string)
$route = $_GET['route'] ?? 'dashboard/index';

// Dividir la ruta en partes
$parts = explode('/', $route);
$controller = $parts[0] ?? 'dashboard';
$action = $parts[1] ?? 'index';
$id = $parts[2] ?? null;

// Enrutamiento
switch ($controller) {
    case '':
    case 'dashboard':
        $dashboardController = new DashboardController();
        if ($action === 'index') {
            $dashboardController->index();
        } elseif ($action === 'getAlertasAjax') {
            $dashboardController->getAlertasAjax();
        } else {
            // Acción no encontrada
            http_response_code(404);
            echo "Página no encontrada";
        }
        break;

    case 'inventario':
        $inventarioController = new InventarioController();
        switch ($action) {
            case 'index':
                $inventarioController->index();
                break;
            case 'create':
                $inventarioController->create();
                break;
            case 'store':
                $inventarioController->store();
                break;
            case 'edit':
                if ($id) {
                    $inventarioController->edit($id);
                } else {
                    http_response_code(404);
                    echo "ID requerido";
                }
                break;
            case 'update':
                if ($id) {
                    $inventarioController->update($id);
                } else {
                    http_response_code(404);
                    echo "ID requerido";
                }
                break;
            case 'delete':
                if ($id) {
                    $inventarioController->delete($id);
                } else {
                    http_response_code(404);
                    echo "ID requerido";
                }
                break;
            case 'entradas':
                $inventarioController->entradas();
                break;
            case 'alertas':
                $inventarioController->alertas();
                break;
            default:
                http_response_code(404);
                echo "Página no encontrada";
        }
        break;

    case 'venta':
    case 'ventas':
        $ventaController = new VentaController();
        switch ($action) {
            case 'index':
                $ventaController->index();
                break;
            case 'pos':
                $ventaController->pos();
                break;
            case 'processSale':
                $ventaController->processSale();
                break;
            case 'lista':
            case 'listSales':
                $ventaController->listSales();
                break;
            case 'detalle':
                if ($id) {
                    $ventaController->detalle($id);
                } else {
                    http_response_code(404);
                    echo "ID requerido";
                }
                break;
            case 'edit':
                if ($id) {
                    $ventaController->edit($id);
                } else {
                    http_response_code(404);
                    echo "ID requerido";
                }
                break;
            case 'update':
                if ($id) {
                    $ventaController->update($id);
                } else {
                    http_response_code(404);
                    echo "ID requerido";
                }
                break;
            case 'delete':
                if ($id) {
                    $ventaController->delete($id);
                } else {
                    http_response_code(404);
                    echo "ID requerido";
                }
                break;
            case 'closeCash':
                $ventaController->closeCash();
                break;
            default:
                http_response_code(404);
                echo "Página no encontrada";
        }
        break;

    case 'usuario':
        $usuarioController = new UsuarioController();
        switch ($action) {
            case 'index':
                $usuarioController->index();
                break;
            case 'create':
                $usuarioController->create();
                break;
            case 'store':
                $usuarioController->store();
                break;
            case 'edit':
                if ($id) {
                    $usuarioController->edit($id);
                } else {
                    http_response_code(404);
                    echo "ID requerido";
                }
                break;
            case 'update':
                if ($id) {
                    $usuarioController->update($id);
                } else {
                    http_response_code(404);
                    echo "ID requerido";
                }
                break;
            case 'delete':
                if ($id) {
                    $usuarioController->delete($id);
                } else {
                    http_response_code(404);
                    echo "ID requerido";
                }
                break;
            default:
                http_response_code(404);
                echo "Página no encontrada";
        }
        break;

    case 'reporte':
    case 'reportes':
        $reporteController = new ReporteController();
        switch ($action) {
            case 'index':
                $reporteController->index();
                break;
            case 'salesReport':
                $reporteController->salesReport();
                break;
            case 'inventoryReport':
                $reporteController->inventoryReport();
                break;
            case 'profitReport':
                $reporteController->profitReport();
                break;
            default:
                http_response_code(404);
                echo "Página no encontrada";
        }
        break;

    case 'auth':
        $authController = new AuthController();
        if ($action === 'login') {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $authController->showLoginForm();
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authController->login();
            }
        } elseif ($action === 'logout') {
            $authController->logout();
        } else {
            http_response_code(404);
            echo "Página no encontrada";
        }
        break;

    case 'login':
        $authController = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $authController->showLoginForm();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        }
        break;

    case 'logout':
        $authController = new AuthController();
        $authController->logout();
        break;

    default:
        http_response_code(404);
        echo "Página no encontrada";
}
?>
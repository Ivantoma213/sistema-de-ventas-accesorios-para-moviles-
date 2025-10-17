<?php
require_once __DIR__ . '/Controller.php';

class DashboardController extends Controller {
    private $reporteModel;
    private $inventarioService;

    public function __construct() {
        $this->checkAuth();
        $this->reporteModel = $this->model('ReporteModel');
        $this->inventarioService = $this->service('InventarioService');
    }

    public function index() {
        $rol = $_SESSION['rol'] ?? 'usuario'; // Obtener rol de la sesión

        // Obtener ventas del día
        $hoy = date('Y-m-d');
        $ventasDia = $this->reporteModel->ventasPorPeriodo($hoy, $hoy)['total_ventas'] ?? 0;

        // Meta de ventas del día (configurable, aquí fijo en 5000)
        $metaVentas = 5000;
        $porcentajeMeta = $ventasDia > 0 ? ($ventasDia / $metaVentas) * 100 : 0;

        // Alertas ROP - Usar la misma lógica que lista_productos.php
        $productos = $this->model('ProductoModel')->findAll();
        $alertasROP = array_filter($productos, function($p) {
            return $p['stock'] <= $p['stock_minimo'];
        });

        // Pasar datos a la vista
        $data = [
            'ventasDia' => $ventasDia,
            'metaVentas' => $metaVentas,
            'porcentajeMeta' => $porcentajeMeta,
            'alertasROP' => $alertasROP,
            'rol' => $rol
        ];

        $this->view('dashboard/index', $data);
    }

    // Método para polling de alertas (para AJAX)
    public function getAlertasAjax() {
        header('Content-Type: application/json');
        $productos = $this->model('ProductoModel')->findAll();
        $alertas = array_filter($productos, function($p) {
            return $p['stock'] <= $p['stock_minimo'];
        });
        // Convertir a array indexado para JSON
        $alertasArray = array_values(array_map(function($p) {
            return [
                'id' => $p['id'],
                'nombre' => $p['nombre'],
                'stock' => $p['stock']
            ];
        }, $alertas));
        echo json_encode($alertasArray);
    }
}
?>
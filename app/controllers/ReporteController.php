<?php
require_once __DIR__ . '/Controller.php';

class ReporteController extends Controller {
    private $reporteModel;

    public function __construct() {
        $this->checkAuth();
        $this->reporteModel = $this->model('ReporteModel');
    }

    public function index() {
        // Mostrar página principal de reportes
        $this->view('reportes/index');
    }

    public function salesReport() {
        $fechaInicio = $_GET['inicio'] ?? date('Y-m-d', strtotime('-30 days'));
        $fechaFin = $_GET['fin'] ?? date('Y-m-d');
        $ventas = $this->reporteModel->ventasPorPeriodo($fechaInicio, $fechaFin);
        $this->view('reportes/sales_report', ['ventas' => $ventas]);
    }

    public function inventoryReport() {
        $fechaInicio = $_GET['inicio'] ?? date('Y-m-d', strtotime('-30 days'));
        $fechaFin = $_GET['fin'] ?? date('Y-m-d');
        $movimientos = $this->reporteModel->movimientosInventario($fechaInicio, $fechaFin);
        $this->view('reportes/inventory_report', ['movimientos' => $movimientos]);
    }

    public function profitReport() {
        $fechaInicio = $_GET['inicio'] ?? date('Y-m-d', strtotime('-30 days'));
        $fechaFin = $_GET['fin'] ?? date('Y-m-d');
        $ganancias = $this->reporteModel->ganancias($fechaInicio, $fechaFin);
        $this->view('reportes/profit_report', ['ganancias' => $ganancias]);
    }
}
?>
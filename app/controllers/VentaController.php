<?php
require_once __DIR__ . '/Controller.php';

class VentaController extends Controller {
    private $ventaModel;
    private $productoModel;
    private $ventaService;

    public function __construct() {
        $this->checkAuth();
        // Restricción por rol: solo admin y vendedor
        if (!in_array($_SESSION['user']['rol'] ?? '', ['admin', 'vendedor'])) {
            header('Location: /sistema/?route=dashboard');
            exit;
        }
        $this->ventaModel = $this->model('VentaModel');
        $this->productoModel = $this->model('ProductoModel');
        $this->ventaService = $this->service('VentaService');
    }

    public function index() {
        // Redirigir a la lista de ventas por defecto
        header('Location: /sistema/?route=ventas/lista');
        exit;
    }

    public function pos() {
        $productos = $this->productoModel->findAll();
        $tiposPago = $this->ventaModel->getTiposPago();
        $cajas = $this->ventaModel->getCajas();
        $this->view('ventas/pos', [
            'productos' => $productos,
            'tiposPago' => $tiposPago,
            'cajas' => $cajas
        ]);
    }

    public function processSale() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $descuento = isset($_POST['descuento']) ? floatval($_POST['descuento']) : 0;
            $subtotal = floatval($_POST['total']); // Este ya viene con descuento aplicado desde JS

            $ventaData = [
                'fecha' => date('Y-m-d H:i:s'),
                'total' => $subtotal,
                'descuento' => $descuento,
                'id_usuario' => $_SESSION['user_id'] ?? 1,
                'id_caja' => $_POST['id_caja'],
                'id_tipo_pago' => $_POST['id_tipo_pago']
            ];

            $detalles = [];
            $productosData = json_decode($_POST['productos'], true);
            foreach ($productosData as $prod) {
                $detalles[] = [
                    'id_producto' => $prod['id'],
                    'cantidad' => $prod['cantidad'],
                    'precio_unitario' => $prod['precio'],
                    'subtotal' => $prod['precio'] * $prod['cantidad']
                ];
            }

            try {
                $ventaId = $this->ventaService->processSale($ventaData, $detalles);
                header('Location: /sistema/?route=ventas/lista&success=1');
                exit;
            } catch (Exception $e) {
                header('Location: /sistema/?route=ventas/pos&error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    public function listSales() {
        $ventas = $this->ventaModel->findAll();
        $this->view('ventas/lista_ventas', ['ventas' => $ventas]);
    }

    public function detalle($id) {
        $venta = $this->ventaModel->findById($id);
        if (!$venta) {
            http_response_code(404);
            echo "Venta no encontrada";
            return;
        }
        $detalles = $this->ventaModel->getDetallesVenta($id);
        $this->view('ventas/detalle', [
            'venta' => $venta,
            'detalles' => $detalles
        ]);
    }

    public function edit($id) {
        $venta = $this->ventaModel->findById($id);
        if (!$venta) {
            http_response_code(404);
            echo "Venta no encontrada";
            return;
        }
        $detalles = $this->ventaModel->getDetallesVenta($id);
        $productos = $this->productoModel->findAll();
        $tiposPago = $this->ventaModel->getTiposPago();
        $cajas = $this->ventaModel->getCajas();

        $this->view('ventas/edit', [
            'venta' => $venta,
            'detalles' => $detalles,
            'productos' => $productos,
            'tiposPago' => $tiposPago,
            'cajas' => $cajas
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrfToken();

            $ventaData = [
                'id_caja' => $_POST['id_caja'],
                'id_tipo_pago' => $_POST['id_tipo_pago']
            ];

            // Solo actualizar caja y tipo de pago (fecha y usuario no cambian)
            $this->ventaModel->update($id, $ventaData);

            header('Location: /sistema/?route=ventas/lista&updated=1');
            exit;
        }
    }

    public function delete($id) {
        $venta = $this->ventaModel->findById($id);
        if (!$venta) {
            http_response_code(404);
            echo "Venta no encontrada";
            return;
        }

        // Obtener detalles de la venta para restaurar stock
        $detalles = $this->ventaModel->getDetallesVenta($id);

        DB::beginTransaction();
        try {
            // Restaurar stock de productos
            foreach ($detalles as $detalle) {
                $this->productoModel->updateStock($detalle['id_producto'], $detalle['cantidad']);
            }

            // Registrar movimientos de inventario (entrada por devolución)
            foreach ($detalles as $detalle) {
                $this->productoModel->registrarMovimiento(
                    $detalle['id_producto'],
                    'entrada',
                    $detalle['cantidad'],
                    'Devolución por eliminación de venta #' . $id,
                    $venta['id_usuario']
                );
            }

            // Eliminar detalles primero (por foreign key)
            $this->ventaModel->deleteDetallesByVentaId($id);
            // Luego eliminar la venta
            $this->ventaModel->delete($id);

            DB::commit();
            header('Location: /sistema/?route=ventas/lista&deleted=1');
            exit;

        } catch (Exception $e) {
            DB::rollBack();
            header('Location: /sistema/?route=ventas/lista&error=' . urlencode('Error al eliminar venta: ' . $e->getMessage()));
            exit;
        }
    }

    public function closeCash() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idCaja = $_POST['id_caja'];
            $fecha = $_POST['fecha'];
            $totalEsperado = $_POST['total_esperado'];

            try {
                $cierreId = $this->ventaService->closeCash($idCaja, $fecha, $totalEsperado);
                header('Location: /sistema/?route=ventas/closeCash&success=1');
                exit;
            } catch (Exception $e) {
                header('Location: /sistema/?route=ventas/closeCash&error=' . urlencode($e->getMessage()));
                exit;
            }
        } else {
            $cajas = $this->ventaModel->getCajas();
            $this->view('ventas/cierre_caja', ['cajas' => $cajas]);
        }
    }
}
?>
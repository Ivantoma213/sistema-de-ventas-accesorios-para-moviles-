<?php
require_once __DIR__ . '/Controller.php';

class InventarioController extends Controller {
    private $productoModel;
    private $compraModel;
    private $inventarioService;

    public function __construct() {
        $this->checkAuth();
        // Restricción: solo admin e inventario
        if (!in_array($_SESSION['user']['rol'] ?? '', ['admin', 'inventario'])) {
            header('Location: /sistema/?route=dashboard');
            exit;
        }
        $this->productoModel = $this->model('ProductoModel');
        $this->compraModel = $this->model('CompraModel');
        $this->inventarioService = $this->service('InventarioService');
    }

    // Productos CRUD
    public function index() {
        $productos = $this->productoModel->findAll();
        $this->view('inventario/lista_productos', ['productos' => $productos]);
    }

    public function create() {
        $categorias = $this->productoModel->getCategorias();
        $unidades = $this->productoModel->getUnidadesMedida();
        $estados = $this->productoModel->getEstadosProducto();
        $this->view('inventario/form_producto', [
            'categorias' => $categorias,
            'unidades' => $unidades,
            'estados' => $estados
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // $this->checkCsrfToken();
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'precio' => $_POST['precio'],
                'stock' => $_POST['stock'],
                'stock_minimo' => $_POST['stock_minimo'],
                'id_categoria' => $_POST['id_categoria'],
                'id_unidad_medida' => $_POST['id_unidad_medida'],
                'id_estado' => $_POST['id_estado']
            ];
            $this->productoModel->create($data);
            header('Location: /sistema/?route=inventario');
            exit;
        }
    }

    public function edit($id) {
        $producto = $this->productoModel->findById($id);
        $categorias = $this->productoModel->getCategorias();
        $unidades = $this->productoModel->getUnidadesMedida();
        $estados = $this->productoModel->getEstadosProducto();
        $this->view('inventario/form_producto', [
            'producto' => $producto,
            'categorias' => $categorias,
            'unidades' => $unidades,
            'estados' => $estados
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // $this->checkCsrfToken();
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'precio' => $_POST['precio'],
                'stock' => $_POST['stock'],
                'stock_minimo' => $_POST['stock_minimo'],
                'id_categoria' => $_POST['id_categoria'],
                'id_unidad_medida' => $_POST['id_unidad_medida'],
                'id_estado' => $_POST['id_estado']
            ];
            $this->productoModel->update($id, $data);
            header('Location: /sistema/?route=inventario');
            exit;
        }
    }

    public function delete($id) {
        $this->productoModel->delete($id);
        header('Location: /sistema/?route=inventario');
        exit;
    }

    // Entradas de inventario
    public function entradas() {
        $compras = $this->compraModel->findAllCompras();
        $this->view('inventario/entradas', ['compras' => $compras]);
    }

    public function alertas() {
        $alertas = $this->inventarioService->getAlertas();
        $this->view('inventario/alertas', ['alertas' => $alertas]);
    }
}
?>
<?php
require_once __DIR__ . '/DB.php';

class CompraModel {
    private $db;

    public function __construct() {
        $this->db = DB::conectar();
    }

    // Métodos para compras
    public function findAllCompras() {
        $stmt = $this->db->prepare("
            SELECT c.*, p.nombre as proveedor
            FROM compras c
            LEFT JOIN proveedores p ON c.id_proveedor = p.id
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findCompraById($id) {
        $stmt = $this->db->prepare("
            SELECT c.*, p.nombre as proveedor
            FROM compras c
            LEFT JOIN proveedores p ON c.id_proveedor = p.id
            WHERE c.id = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createCompra($data) {
        $stmt = $this->db->prepare("
            INSERT INTO compras (fecha, id_proveedor, total)
            VALUES (:fecha, :id_proveedor, :total)
        ");
        $stmt->bindParam(':fecha', $data['fecha']);
        $stmt->bindParam(':id_proveedor', $data['id_proveedor']);
        $stmt->bindParam(':total', $data['total']);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function updateCompra($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE compras SET
                fecha = :fecha,
                id_proveedor = :id_proveedor,
                total = :total
            WHERE id = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':fecha', $data['fecha']);
        $stmt->bindParam(':id_proveedor', $data['id_proveedor']);
        $stmt->bindParam(':total', $data['total']);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function deleteCompra($id) {
        // Primero eliminar detalles
        $this->deleteDetallesByCompra($id);
        $stmt = $this->db->prepare("DELETE FROM compras WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    // Métodos para detalle_compra
    public function getDetallesByCompra($compraId) {
        $stmt = $this->db->prepare("
            SELECT dc.*, p.nombre as producto
            FROM detalle_compra dc
            LEFT JOIN productos p ON dc.id_producto = p.id
            WHERE dc.id_compra = :id_compra
        ");
        $stmt->bindParam(':id_compra', $compraId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createDetalle($compraId, $data) {
        $stmt = $this->db->prepare("
            INSERT INTO detalle_compra (id_compra, id_producto, cantidad, precio_unitario, subtotal)
            VALUES (:id_compra, :id_producto, :cantidad, :precio_unitario, :subtotal)
        ");
        $stmt->bindParam(':id_compra', $compraId);
        $stmt->bindParam(':id_producto', $data['id_producto']);
        $stmt->bindParam(':cantidad', $data['cantidad']);
        $stmt->bindParam(':precio_unitario', $data['precio_unitario']);
        $stmt->bindParam(':subtotal', $data['subtotal']);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function updateDetalle($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE detalle_compra SET
                id_producto = :id_producto,
                cantidad = :cantidad,
                precio_unitario = :precio_unitario,
                subtotal = :subtotal
            WHERE id = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':id_producto', $data['id_producto']);
        $stmt->bindParam(':cantidad', $data['cantidad']);
        $stmt->bindParam(':precio_unitario', $data['precio_unitario']);
        $stmt->bindParam(':subtotal', $data['subtotal']);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function deleteDetalle($id) {
        $stmt = $this->db->prepare("DELETE FROM detalle_compra WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function deleteDetallesByCompra($compraId) {
        $stmt = $this->db->prepare("DELETE FROM detalle_compra WHERE id_compra = :id_compra");
        $stmt->bindParam(':id_compra', $compraId);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function getProveedores() {
        $stmt = $this->db->prepare("SELECT * FROM proveedores");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
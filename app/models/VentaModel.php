<?php
require_once __DIR__ . '/BaseModel.php';

class VentaModel extends BaseModel {
    protected $table = 'ventas';

    public function findAll() {
        $sql = "
            SELECT v.*,
                   u.nombre as usuario_nombre,
                   c.nombre as caja_nombre,
                   tp.nombre as tipo_pago_nombre
            FROM ventas v
            LEFT JOIN usuarios u ON v.id_usuario = u.id
            LEFT JOIN cajas c ON v.id_caja = c.id
            LEFT JOIN tipos_pago tp ON v.id_tipo_pago = tp.id
            ORDER BY v.fecha DESC
        ";
        return $this->query($sql)->fetchAll();
    }

    public function findById($id) {
        $sql = "
            SELECT v.*,
                   u.nombre as usuario_nombre,
                   c.nombre as caja_nombre,
                   tp.nombre as tipo_pago_nombre
            FROM ventas v
            LEFT JOIN usuarios u ON v.id_usuario = u.id
            LEFT JOIN cajas c ON v.id_caja = c.id
            LEFT JOIN tipos_pago tp ON v.id_tipo_pago = tp.id
            WHERE v.id = ?
        ";
        return $this->query($sql, [$id])->fetch();
    }

    public function findDetalleByVentaId($ventaId) {
        $sql = "
            SELECT dv.*, p.nombre as producto_nombre
            FROM detalle_venta dv
            JOIN productos p ON dv.id_producto = p.id
            WHERE dv.id_venta = ?
        ";
        return $this->query($sql, [$ventaId])->fetchAll();
    }

    public function getDetallesVenta($ventaId) {
        $sql = "
            SELECT dv.*, p.nombre as producto_nombre, p.descripcion as producto_descripcion
            FROM detalle_venta dv
            JOIN productos p ON dv.id_producto = p.id
            WHERE dv.id_venta = ?
            ORDER BY dv.id
        ";
        return $this->query($sql, [$ventaId])->fetchAll();
    }

    public function getVentasByFecha($fecha) {
        return $this->query("SELECT * FROM ventas WHERE DATE(fecha) = ? ORDER BY fecha DESC", [$fecha])->fetchAll();
    }

    public function getTotalVentasByFecha($fecha) {
        $result = $this->query("SELECT SUM(total) as total FROM ventas WHERE DATE(fecha) = ?", [$fecha])->fetch();
        return $result['total'] ?? 0;
    }

    public function create($data) {
        $this->query("INSERT INTO ventas (fecha, total, id_usuario, id_caja, id_tipo_pago) VALUES (?, ?, ?, ?, ?)", [
            $data['fecha'],
            $data['total'],
            $data['id_usuario'],
            $data['id_caja'],
            $data['id_tipo_pago']
        ]);
        return DB::lastInsertId();
    }

    public function createDetalle($data) {
        $this->query("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)", [
            $data['id_venta'],
            $data['id_producto'],
            $data['cantidad'],
            $data['precio_unitario'],
            $data['subtotal']
        ]);
        return DB::lastInsertId();
    }

    public function getTiposPago() {
        return $this->query("SELECT * FROM tipos_pago")->fetchAll();
    }

    public function getCajas() {
        return $this->query("SELECT * FROM cajas")->fetchAll();
    }

    public function update($id, $data) {
        $setParts = [];
        $params = [];

        if (isset($data['id_caja'])) {
            $setParts[] = "id_caja = ?";
            $params[] = $data['id_caja'];
        }
        if (isset($data['id_tipo_pago'])) {
            $setParts[] = "id_tipo_pago = ?";
            $params[] = $data['id_tipo_pago'];
        }

        if (empty($setParts)) {
            return 0; // Nothing to update
        }

        $setClause = implode(', ', $setParts);
        $params[] = $id;
        $stmt = $this->query("UPDATE ventas SET $setClause WHERE id = ?", $params);
        return $stmt->rowCount();
    }

    public function deleteDetallesByVentaId($ventaId) {
        return $this->query("DELETE FROM detalle_venta WHERE id_venta = ?", [$ventaId]);
    }
}
?>
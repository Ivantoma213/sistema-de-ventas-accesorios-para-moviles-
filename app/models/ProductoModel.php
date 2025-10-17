<?php
require_once __DIR__ . '/BaseModel.php';

class ProductoModel extends BaseModel {
    protected $table = 'productos';

    public function findAll() {
        $sql = "
            SELECT p.*, c.nombre as categoria, u.nombre as unidad_medida, e.nombre as estado
            FROM productos p
            LEFT JOIN categorias c ON p.id_categoria = c.id
            LEFT JOIN unidades_medida u ON p.id_unidad_medida = u.id
            LEFT JOIN estados_producto e ON p.id_estado = e.id
        ";
        return $this->query($sql)->fetchAll();
    }

    public function findById($id) {
        $sql = "
            SELECT p.*, c.nombre as categoria, u.nombre as unidad_medida, e.nombre as estado
            FROM productos p
            LEFT JOIN categorias c ON p.id_categoria = c.id
            LEFT JOIN unidades_medida u ON p.id_unidad_medida = u.id
            LEFT JOIN estados_producto e ON p.id_estado = e.id
            WHERE p.id = ?
        ";
        return $this->query($sql, [$id])->fetch();
    }

    public function create($data) {
        $sql = "
            INSERT INTO productos (nombre, descripcion, precio, stock, stock_minimo, id_categoria, id_unidad_medida, id_estado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $this->query($sql, [
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['stock'],
            $data['stock_minimo'],
            $data['id_categoria'],
            $data['id_unidad_medida'],
            $data['id_estado']
        ]);
        return DB::lastInsertId();
    }

    public function update($id, $data) {
        $sql = "
            UPDATE productos SET
                nombre = ?,
                descripcion = ?,
                precio = ?,
                stock = ?,
                stock_minimo = ?,
                id_categoria = ?,
                id_unidad_medida = ?,
                id_estado = ?
            WHERE id = ?
        ";
        $stmt = $this->query($sql, [
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['stock'],
            $data['stock_minimo'],
            $data['id_categoria'],
            $data['id_unidad_medida'],
            $data['id_estado'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $sql = "DELETE FROM productos WHERE id = ?";
        $stmt = $this->query($sql, [$id]);
        return $stmt->rowCount();
    }

    public function getCategorias() {
        $sql = "SELECT * FROM categorias";
        return $this->query($sql)->fetchAll();
    }

    public function getUnidadesMedida() {
        $sql = "SELECT * FROM unidades_medida";
        return $this->query($sql)->fetchAll();
    }

    public function getEstadosProducto() {
        $sql = "SELECT * FROM estados_producto";
        return $this->query($sql)->fetchAll();
    }

    public function updateStock($id, $cantidad) {
        $sql = "UPDATE productos SET stock = stock + ? WHERE id = ?";
        $stmt = $this->query($sql, [$cantidad, $id]);
        return $stmt->rowCount();
    }

    public function registrarMovimiento($idProducto, $tipoMovimiento, $cantidad, $motivo, $idUsuario) {
        $sql = "
            INSERT INTO movimientos_inventario
            (id_producto, tipo_movimiento, cantidad, fecha, id_usuario, motivo)
            VALUES (?, ?, ?, CURDATE(), ?, ?)
        ";
        $this->query($sql, [$idProducto, $tipoMovimiento, $cantidad, $idUsuario, $motivo]);
        return DB::lastInsertId();
    }
}
?>
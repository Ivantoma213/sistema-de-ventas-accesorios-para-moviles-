<?php
require_once __DIR__ . '/DB.php';

class ReporteModel {
    private $db;

    public function __construct() {
        $this->db = DB::conectar();
    }

    public function ventasPorPeriodo($fechaInicio, $fechaFin) {
        $stmt = $this->db->prepare("SELECT SUM(total) as total_ventas FROM ventas WHERE fecha BETWEEN :inicio AND :fin");
        $stmt->bindParam(':inicio', $fechaInicio);
        $stmt->bindParam(':fin', $fechaFin);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function productosMasVendidos($limite = 10) {
        $stmt = $this->db->prepare("SELECT p.nombre, SUM(dv.cantidad) as cantidad_vendida FROM detalle_venta dv JOIN productos p ON dv.id_producto = p.id GROUP BY dv.id_producto ORDER BY cantidad_vendida DESC LIMIT :limite");
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function movimientosInventario($fechaInicio, $fechaFin) {
        $stmt = $this->db->prepare("
            SELECT mi.*, p.nombre as nombre_producto, mi.tipo_movimiento as tipo
            FROM movimientos_inventario mi
            LEFT JOIN productos p ON mi.id_producto = p.id
            WHERE mi.fecha BETWEEN :inicio AND :fin
            ORDER BY mi.fecha DESC
        ");
        $stmt->bindParam(':inicio', $fechaInicio);
        $stmt->bindParam(':fin', $fechaFin);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function ganancias($fechaInicio, $fechaFin) {
        $stmt = $this->db->prepare("SELECT SUM(total) as ganancias FROM ventas WHERE fecha BETWEEN :inicio AND :fin");
        $stmt->bindParam(':inicio', $fechaInicio);
        $stmt->bindParam(':fin', $fechaFin);
        $stmt->execute();
        return $stmt->fetch();
    }
}
?>
<?php
require_once __DIR__ . '/../models/DB.php';

class InventarioService {

    public function calcularROP($productoId) {
        // Obtener demanda diaria promedio: total salidas en los últimos 30 días dividido por 30
        $stmt = DB::ejecutar("
            SELECT SUM(cantidad) as total_salidas
            FROM movimientos_inventario
            WHERE id_producto = ?
            AND tipo_movimiento = 'salida'
            AND fecha >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        ", [$productoId]);
        $result = $stmt->fetch();
        $demandaDiaria = $result['total_salidas'] ? $result['total_salidas'] / 30 : 0;

        // Obtener tiempo de entrega y stock mínimo del producto
        $stmt = DB::ejecutar("SELECT tiempo_entrega, stock_minimo FROM productos WHERE id = ?", [$productoId]);
        $producto = $stmt->fetch();
        $tiempoEntrega = $producto['tiempo_entrega'] ?? 7; // default 7 días
        $stockMinimo = $producto['stock_minimo'] ?? 0;

        // ROP = Demanda diaria × Tiempo de entrega
        $rop = $demandaDiaria * $tiempoEntrega;

        // Si no hay demanda histórica, usar stock mínimo + buffer de seguridad
        if ($rop == 0) {
            $rop = max($stockMinimo + 5, 10); // Stock mínimo + 5 unidades de buffer, mínimo 10
        }

        return max($rop, 1); // Mínimo ROP de 1
    }

    public function getAlertas() {
        $productos = [];
        $stmt = DB::ejecutar("SELECT id, nombre, stock FROM productos");
        $prods = $stmt->fetchAll();

        foreach ($prods as $prod) {
            $rop = $this->calcularROP($prod['id']);
            if ($prod['stock'] <= $rop) {
                $productos[] = [
                    'id' => $prod['id'],
                    'nombre' => $prod['nombre'],
                    'stock' => $prod['stock'],
                    'rop' => $rop
                ];
            }
        }
        return $productos;
    }
}
?>
<?php
require_once __DIR__ . '/../models/DB.php';
require_once __DIR__ . '/../models/VentaModel.php';

class VentaService {
    private $ventaModel;

    public function __construct() {
        $this->ventaModel = new VentaModel();
    }

    public function processSale($ventaData, $detalles) {
        DB::beginTransaction();
        try {
            // Insertar venta
            $ventaId = $this->ventaModel->create($ventaData);

            $totalCalculado = 0;

            foreach ($detalles as $detalle) {
                // Verificar stock
                $stmt = DB::ejecutar("SELECT stock, precio FROM productos WHERE id = ?", [$detalle['id_producto']]);
                $producto = $stmt->fetch();

                if (!$producto || $producto['stock'] < $detalle['cantidad']) {
                    throw new Exception("Stock insuficiente para producto ID: " . $detalle['id_producto']);
                }

                // Usar el precio unitario enviado desde el frontend (ya incluye cualquier descuento)
                $precioUnitario = $detalle['precio_unitario'];
                $subtotal = $precioUnitario * $detalle['cantidad'];
                $totalCalculado += $subtotal;

                // Insertar detalle
                $detalleData = [
                    'id_venta' => $ventaId,
                    'id_producto' => $detalle['id_producto'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $subtotal
                ];
                $this->ventaModel->createDetalle($detalleData);

                // Actualizar stock
                DB::ejecutar("UPDATE productos SET stock = ? WHERE id = ?", [$producto['stock'] - $detalle['cantidad'], $detalle['id_producto']]);

                // Registrar movimiento de inventario
                DB::ejecutar("INSERT INTO movimientos_inventario (id_producto, tipo_movimiento, cantidad, fecha, id_usuario, motivo) VALUES (?, 'salida', ?, CURDATE(), ?, 'Venta')",
                    [$detalle['id_producto'], $detalle['cantidad'], $ventaData['id_usuario']]);
            }

            // Actualizar el total calculado (ya incluye descuento aplicado)
            DB::ejecutar("UPDATE ventas SET total = ? WHERE id = ?", [$totalCalculado, $ventaId]);

            DB::commit();
            return $ventaId;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function getPromocionActiva($idProducto) {
        $sql = "
            SELECT * FROM promociones
            WHERE id_producto = ?
            AND activa = 1
            AND fecha_inicio <= CURDATE()
            AND fecha_fin >= CURDATE()
            LIMIT 1
        ";
        $stmt = DB::ejecutar($sql, [$idProducto]);
        return $stmt->fetch();
    }

    public function closeCash($idCaja, $fecha, $totalEsperado) {
        // Calcular total real de ventas en la caja para la fecha
        $sql = "SELECT SUM(total) as total_real FROM ventas WHERE id_caja = ? AND DATE(fecha) = ?";
        $stmt = DB::ejecutar($sql, [$idCaja, $fecha]);
        $result = $stmt->fetch();
        $totalReal = $result['total_real'] ?? 0;

        // Insertar cierre
        $sql = "INSERT INTO cierres_caja (id_caja, fecha, total_esperado, total_real, diferencia) VALUES (?, ?, ?, ?, ?)";
        $diferencia = $totalReal - $totalEsperado;
        DB::ejecutar($sql, [$idCaja, $fecha, $totalEsperado, $totalReal, $diferencia]);

        return DB::lastInsertId();
    }
}
?>
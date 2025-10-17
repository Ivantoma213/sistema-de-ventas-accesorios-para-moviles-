<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-receipt"></i> Detalle de Venta #<?php echo $venta['id']; ?></h1>
        <div class="header-actions">
            <a href="/sistema/?route=ventas/lista" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a Lista
            </a>
        </div>
    </div>

    <div class="venta-detalle-container">
        <!-- Información General de la Venta -->
        <div class="venta-info-card">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Información de la Venta</h3>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>ID de Venta:</label>
                        <span><?php echo $venta['id']; ?></span>
                    </div>
                    <div class="info-item">
                        <label>Fecha:</label>
                        <span><?php echo date('d/m/Y H:i:s', strtotime($venta['fecha'])); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Total:</label>
                        <span class="total-amount">S/ <?php echo number_format($venta['total'], 2); ?></span>
                    </div>
                    <?php if (isset($venta['descuento']) && $venta['descuento'] > 0): ?>
                    <div class="info-item">
                        <label>Descuento aplicado:</label>
                        <span><?php echo number_format($venta['descuento'], 2); ?>%</span>
                    </div>
                    <?php endif; ?>
                    <div class="info-item">
                        <label>Usuario:</label>
                        <span><?php echo htmlspecialchars($venta['usuario_nombre'] ?? 'Usuario #' . $venta['id_usuario']); ?></span>
                    </div>
                    <?php if (isset($venta['caja_nombre'])): ?>
                    <div class="info-item">
                        <label>Caja:</label>
                        <span><?php echo htmlspecialchars($venta['caja_nombre']); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (isset($venta['tipo_pago_nombre'])): ?>
                    <div class="info-item">
                        <label>Tipo de Pago:</label>
                        <span><?php echo htmlspecialchars($venta['tipo_pago_nombre']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Detalles de Productos -->
        <div class="productos-card">
            <div class="card-header">
                <h3><i class="fas fa-shopping-cart"></i> Productos Vendidos</h3>
            </div>
            <div class="card-body">
                <?php if (empty($detalles)): ?>
                    <div class="empty-state">
                        <i class="fas fa-shopping-basket"></i>
                        <p>No hay detalles de productos para esta venta.</p>
                    </div>
                <?php else: ?>
                    <div class="productos-table">
                        <div class="table-header">
                            <div class="col-producto">Producto</div>
                            <div class="col-cantidad">Cantidad</div>
                            <div class="col-precio">Precio Unit.</div>
                            <div class="col-subtotal">Subtotal</div>
                        </div>
                        <?php foreach ($detalles as $detalle): ?>
                        <div class="table-row">
                            <div class="col-producto">
                                <div class="producto-info">
                                    <strong><?php echo htmlspecialchars($detalle['producto_nombre']); ?></strong>
                                    <?php if (!empty($detalle['producto_descripcion'])): ?>
                                    <small><?php echo htmlspecialchars($detalle['producto_descripcion']); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-cantidad">
                                <span class="cantidad-badge"><?php echo $detalle['cantidad']; ?></span>
                            </div>
                            <div class="col-precio">
                                S/ <?php echo number_format($detalle['precio_unitario'], 2); ?>
                            </div>
                            <div class="col-subtotal">
                                <strong>S/ <?php echo number_format($detalle['subtotal'], 2); ?></strong>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="venta-total">
                        <div class="total-row">
                            <span>Total de la Venta:</span>
                            <strong>S/ <?php echo number_format($venta['total'], 2); ?></strong>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.venta-detalle-container {
    display: grid;
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
}

.venta-info-card, .productos-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px 25px;
    border-bottom: 3px solid #5c6bc0;
}

.card-header h3 {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.2em;
}

.card-body {
    padding: 25px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.info-item label {
    font-weight: 600;
    color: #666;
    font-size: 0.9em;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item span {
    font-size: 1.1em;
    color: #2c3e50;
    font-weight: 500;
}

.total-amount {
    color: #28a745 !important;
    font-size: 1.3em !important;
    font-weight: 700 !important;
}

.productos-table {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
}

.table-header {
    background: #f8f9fa;
    display: grid;
    grid-template-columns: 3fr 1fr 1fr 1fr;
    gap: 15px;
    padding: 15px 20px;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.table-row {
    display: grid;
    grid-template-columns: 3fr 1fr 1fr 1fr;
    gap: 15px;
    padding: 15px 20px;
    border-bottom: 1px solid #e9ecef;
    align-items: center;
}

.table-row:last-child {
    border-bottom: none;
}

.producto-info strong {
    color: #2c3e50;
    display: block;
    margin-bottom: 2px;
}

.producto-info small {
    color: #6c757d;
    font-size: 0.85em;
}

.cantidad-badge {
    background: #007bff;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-weight: 600;
    display: inline-block;
}

.col-precio, .col-subtotal {
    text-align: right;
    font-weight: 500;
}

.venta-total {
    margin-top: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 1.2em;
    font-weight: 700;
    color: #2c3e50;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.empty-state i {
    font-size: 3em;
    margin-bottom: 15px;
    opacity: 0.5;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e9ecef;
}

.page-header h1 {
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .venta-detalle-container {
        gap: 20px;
    }

    .info-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .table-header, .table-row {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .table-row {
        text-align: center;
        padding: 20px;
    }

    .page-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .header-actions {
        width: 100%;
        justify-content: center;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
<?php require_once __DIR__ . '/layouts/header.php'; ?>

<?php
// Extraer variables del array de datos
$movimientos = $movimientos ?? [];
?>

<div class="page-container">
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-warehouse"></i>
            <h1>Reporte de Inventario</h1>
        </div>
        <div class="page-actions">
            <a href="/sistema/?route=reportes" class="action-btn secondary">
                <i class="fas fa-arrow-left"></i>
                Volver a Reportes
            </a>
        </div>
    </div>

    <div class="content-grid">
        <!-- Filtros de fecha -->
        <div class="filter-card">
            <div class="filter-header">
                <i class="fas fa-filter"></i>
                <h3>Filtros de Reporte</h3>
            </div>
            <form method="GET" action="/sistema/?route=reportes/inventoryReport" class="filter-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="inicio">Fecha Inicio</label>
                        <input type="date" id="inicio" name="inicio" value="<?php echo $_GET['inicio'] ?? date('Y-m-d', strtotime('-30 days')); ?>">
                    </div>
                    <div class="form-group">
                        <label for="fin">Fecha Fin</label>
                        <input type="date" id="fin" name="fin" value="<?php echo $_GET['fin'] ?? date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="action-btn primary">
                            <i class="fas fa-search"></i>
                            Generar Reporte
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Resultados del reporte -->
        <?php if (!empty($movimientos)): ?>
            <div class="report-results">
                <!-- Estadísticas rápidas -->
                <div class="stats-overview">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value"><?php echo count($movimientos); ?></div>
                            <div class="stat-label">Total Movimientos</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value"><?php echo count(array_filter($movimientos, function($m) { return $m['tipo'] === 'entrada'; })); ?></div>
                            <div class="stat-label">Entradas</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-minus-circle"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value"><?php echo count(array_filter($movimientos, function($m) { return $m['tipo'] === 'salida'; })); ?></div>
                            <div class="stat-label">Salidas</div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de movimientos -->
                <div class="data-table-container">
                    <div class="table-header">
                        <h3>Movimientos de Inventario</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>ID Movimiento</th>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                    <th>Producto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($movimientos as $mov): ?>
                                    <tr>
                                        <td>
                                            <div class="id-badge">#<?php echo $mov['id']; ?></div>
                                        </td>
                                        <td>
                                            <div class="date-info">
                                                <i class="fas fa-calendar"></i>
                                                <?php echo date('d/m/Y H:i', strtotime($mov['fecha'])); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="movement-type <?php echo $mov['tipo']; ?>">
                                                <i class="fas fa-<?php echo $mov['tipo'] === 'entrada' ? 'plus-circle' : 'minus-circle'; ?>"></i>
                                                <?php echo ucfirst($mov['tipo']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="quantity <?php echo $mov['tipo']; ?>">
                                                <?php echo $mov['tipo'] === 'entrada' ? '+' : '-'; ?><?php echo $mov['cantidad']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($mov['nombre_producto'] ?? 'Producto no encontrado'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-warehouse"></i>
                <h3>No hay movimientos en el período seleccionado</h3>
                <p>No se encontraron movimientos de inventario entre las fechas especificadas.</p>
                <a href="/sistema/?route=reportes" class="action-btn primary">
                    <i class="fas fa-arrow-left"></i>
                    Volver a Reportes
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .movement-type {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8em;
        font-weight: 600;
        text-transform: capitalize;
    }

    .movement-type.entrada {
        background: rgba(0, 255, 136, 0.2);
        color: var(--color-accent-green);
    }

    .movement-type.salida {
        background: rgba(255, 71, 87, 0.2);
        color: var(--color-accent-red);
    }

    .quantity {
        font-weight: 600;
        font-size: 1.1em;
    }

    .quantity.entrada {
        color: var(--color-accent-green);
    }

    .quantity.salida {
        color: var(--color-accent-red);
    }
</style>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
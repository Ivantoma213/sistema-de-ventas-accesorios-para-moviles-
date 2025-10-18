<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
// Extraer variables del array de datos
$ventas = $ventas ?? [];
?>

<div class="main-content">
    <div class="page-navigation">
        <a href="/sistema/?route=reportes" class="nav-back-btn">
            <i class="fas fa-arrow-left"></i>
            Volver a Reportes
        </a>
    </div>
    <h1>Reporte de Ventas</h1>
        <!-- Filtros de fecha -->
        <div class="filter-card">
            <div class="filter-header">
                <i class="fas fa-filter"></i>
                <h3>Filtros de Reporte</h3>
            </div>
            <form method="GET" action="/sistema/?route=reportes/salesReport" class="filter-form">
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
        <?php if (isset($ventas)): ?>
            <div class="report-results">
                <!-- Estadísticas principales -->
                <div class="stats-overview">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">S/ <?php echo number_format($ventas['total_ventas'] ?? 0, 2); ?></div>
                            <div class="stat-label">Total de Ventas</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value"><?php echo $ventas['total_transacciones'] ?? 0; ?></div>
                            <div class="stat-label">Total Transacciones</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">S/ <?php echo number_format(($ventas['total_ventas'] ?? 0) / max($ventas['total_transacciones'] ?? 1, 1), 2); ?></div>
                            <div class="stat-label">Promedio por Venta</div>
                    </div>
                </div>

                <!-- Gráfico de ventas (placeholder) -->
                <div class="chart-container">
                    <div class="chart-header">
                        <i class="fas fa-chart-bar"></i>
                        <h3>Tendencia de Ventas</h3>
                    </div>
                    <div class="chart-placeholder">
                        <i class="fas fa-chart-line"></i>
                        <p>Gráfico de ventas por período</p>
                        <small>Funcionalidad próximamente</small>
                    </div>
                </div>

                <!-- Tabla de detalle de ventas -->
                <?php if (isset($ventas['detalle']) && !empty($ventas['detalle'])): ?>
                    <div class="data-table-container">
                        <div class="table-header">
                            <h3>Detalle de Ventas</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>ID Venta</th>
                                        <th>Usuario</th>
                                        <th>Total</th>
                                        <th>Método Pago</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ventas['detalle'] as $venta): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y H:i', strtotime($venta['fecha'])); ?></td>
                                            <td><div class="id-badge">#<?php echo $venta['id']; ?></div></td>
                                            <td><?php echo $venta['usuario']; ?></td>
                                            <td><span class="amount">S/ <?php echo number_format($venta['total'], 2); ?></span></td>
                                            <td><?php echo $venta['tipo_pago']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-chart-line"></i>
                <h3>Selecciona un período</h3>
                <p>Elige las fechas de inicio y fin para generar el reporte de ventas.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
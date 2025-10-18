<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
// Extraer variables del array de datos
$ganancias = $ganancias ?? [];
?>

<div class="main-content">
    <div class="page-navigation">
        <a href="/sistema/?route=reportes" class="nav-back-btn">
            <i class="fas fa-arrow-left"></i>
            Volver a Reportes
        </a>
    </div>
    <h1>Reporte de Ganancias</h1>
        <!-- Filtros de fecha -->
        <div class="filter-card">
            <div class="filter-header">
                <i class="fas fa-filter"></i>
                <h3>Filtros de Reporte</h3>
            </div>
            <form method="GET" action="/sistema/?route=reportes/profitReport" class="filter-form">
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
        <?php if (!empty($ganancias)): ?>
            <div class="report-results">
                <!-- Estadísticas principales -->
                <div class="stats-overview">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">S/ <?php echo number_format($ganancias['ganancias'] ?? 0, 2); ?></div>
                            <div class="stat-label">Ganancias Totales</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value"><?php echo number_format(($ganancias['ganancias'] ?? 0) / max(1, (strtotime($_GET['fin'] ?? date('Y-m-d')) - strtotime($_GET['inicio'] ?? date('Y-m-d', strtotime('-30 days')))) / (60*60*24)), 2); ?></div>
                            <div class="stat-label">Ganancia Diaria Promedio</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">
                                <?php
                                $dias = max(1, (strtotime($_GET['fin'] ?? date('Y-m-d')) - strtotime($_GET['inicio'] ?? date('Y-m-d', strtotime('-30 days')))) / (60*60*24));
                                $porcentaje = (($ganancias['ganancias'] ?? 0) / ($dias * 100)) * 100;
                                echo number_format($porcentaje, 1) . '%';
                                ?>
                            </div>
                            <div class="stat-label">Rentabilidad</div>
                    </div>
                </div>

                <!-- Gráfico de ganancias (placeholder) -->
                <div class="chart-container">
                    <div class="chart-header">
                        <i class="fas fa-chart-bar"></i>
                        <h3>Evolución de Ganancias</h3>
                    </div>
                    <div class="chart-placeholder">
                        <i class="fas fa-chart-line"></i>
                        <p>Gráfico de ganancias por período</p>
                        <small>Funcionalidad próximamente</small>
                    </div>
                </div>

                <!-- Información detallada -->
                <div class="info-section">
                    <div class="info-card">
                        <i class="fas fa-info-circle"></i>
                        <div class="info-content">
                            <h3>Análisis de Ganancias</h3>
                            <div class="profit-breakdown">
                                <div class="breakdown-item">
                                    <span class="label">Período analizado:</span>
                                    <span class="value">
                                        <?php
                                        $inicio = date('d/m/Y', strtotime($_GET['inicio'] ?? date('Y-m-d', strtotime('-30 days'))));
                                        $fin = date('d/m/Y', strtotime($_GET['fin'] ?? date('Y-m-d')));
                                        echo $inicio . ' - ' . $fin;
                                        ?>
                                    </span>
                                </div>
                                <div class="breakdown-item">
                                    <span class="label">Días en el período:</span>
                                    <span class="value">
                                        <?php
                                        $dias = max(1, (strtotime($_GET['fin'] ?? date('Y-m-d')) - strtotime($_GET['inicio'] ?? date('Y-m-d', strtotime('-30 days')))) / (60*60*24));
                                        echo round($dias) . ' días';
                                        ?>
                                    </span>
                                </div>
                                <div class="breakdown-item">
                                    <span class="label">Ganancia total:</span>
                                    <span class="value profit-positive">S/ <?php echo number_format($ganancias['ganancias'] ?? 0, 2); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-dollar-sign"></i>
                <h3>No hay datos de ganancias</h3>
                <p>No se encontraron datos de ganancias para el período seleccionado.</p>
                <a href="/sistema/?route=reportes" class="action-btn primary">
                    <i class="fas fa-arrow-left"></i>
                    Volver a Reportes
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
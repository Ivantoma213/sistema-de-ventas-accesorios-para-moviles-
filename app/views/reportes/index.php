<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="main-content">
    <h1>Centro de Reportes</h1>

    <div class="stats-overview">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo date('d/m/Y'); ?></div>
                <div class="stat-label">Fecha Actual</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo date('H:i'); ?></div>
                <div class="stat-label">Hora Actual</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo $_SESSION['user']['nombre'] ?? 'Usuario'; ?></div>
                <div class="stat-label">Usuario Activo</div>
            </div>
        </div>
    </div>

    <div class="reports-grid">
        <h2>Reportes Disponibles</h2>
        <div class="reports-container">
            <div class="report-card">
                <a href="/sistema/?route=reportes/salesReport">
                    <div class="report-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="report-content">
                        <h3>Reporte de Ventas</h3>
                        <p>Análisis detallado de ventas por período</p>
                        <div class="report-features">
                            <span class="feature-tag">Ventas</span>
                            <span class="feature-tag">Ingresos</span>
                            <span class="feature-tag">Tendencias</span>
                        </div>
                    </div>
                    <div class="report-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
            </div>

            <div class="report-card">
                <a href="/sistema/?route=reportes/inventoryReport">
                    <div class="report-icon">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <div class="report-content">
                        <h3>Reporte de Inventario</h3>
                        <p>Movimientos y estado del inventario</p>
                        <div class="report-features">
                            <span class="feature-tag">Stock</span>
                            <span class="feature-tag">Movimientos</span>
                            <span class="feature-tag">Alertas</span>
                        </div>
                    </div>
                    <div class="report-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
            </div>

            <div class="report-card">
                <a href="/sistema/?route=reportes/profitReport">
                    <div class="report-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="report-content">
                        <h3>Reporte de Ganancias</h3>
                        <p>Análisis de rentabilidad y márgenes</p>
                        <div class="report-features">
                            <span class="feature-tag">Ganancias</span>
                            <span class="feature-tag">Costos</span>
                            <span class="feature-tag">Márgenes</span>
                        </div>
                    </div>
                    <div class="report-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
            </div>

            <div class="report-card coming-soon">
                <div class="report-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="report-content">
                    <h3>Reporte de Clientes</h3>
                    <p>Información y análisis de clientes</p>
                    <div class="report-features">
                        <span class="feature-tag">Próximamente</span>
                    </div>
                </div>
                <div class="report-arrow">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-card">
            <i class="fas fa-info-circle"></i>
            <div class="info-content">
                <h3>Información de Reportes</h3>
                <p>Los reportes se generan en tiempo real con datos actualizados del sistema. Puedes filtrar por fechas y exportar los resultados según necesites.</p>
                <ul>
                    <li><strong>Reporte de Ventas:</strong> Incluye totales, tendencias y desglose por productos</li>
                    <li><strong>Reporte de Inventario:</strong> Muestra movimientos, stock actual y alertas</li>
                    <li><strong>Reporte de Ganancias:</strong> Análisis financiero detallado</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
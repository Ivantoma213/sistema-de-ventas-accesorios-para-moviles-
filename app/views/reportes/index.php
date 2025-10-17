<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-chart-bar"></i>
            <h1>Centro de Reportes</h1>
        </div>
        <div class="page-actions">
            <a href="/sistema/?route=dashboard" class="action-btn secondary">
                <i class="fas fa-arrow-left"></i>
                Volver al Dashboard
            </a>
        </div>
    </div>

    <div class="content-grid">
        <!-- Estadísticas rápidas -->
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

        <!-- Tipos de reportes disponibles -->
        <div class="reports-grid">
            <h2>Reportes Disponibles</h2>
            <div class="reports-container">
                <a href="/sistema/?route=reportes/salesReport" class="report-card">
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

                <a href="/sistema/?route=reportes/inventoryReport" class="report-card">
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

                <a href="/sistema/?route=reportes/profitReport" class="report-card">
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

        <!-- Información adicional -->
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
</div>

<style>
    .reports-grid h2 {
        font-size: 1.8em;
        font-weight: 600;
        margin-bottom: 25px;
        color: var(--color-text-primary);
        text-align: center;
    }

    .reports-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .report-card {
        background: var(--color-bg-card);
        border-radius: 16px;
        padding: 25px;
        text-decoration: none;
        color: var(--color-text-primary);
        transition: var(--transition-normal);
        border: 1px solid var(--color-border);
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        overflow: hidden;
    }

    .report-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(0, 212, 255, 0.1), transparent);
        transition: left 0.5s ease;
    }

    .report-card:hover::before {
        left: 100%;
    }

    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px var(--color-shadow-hover);
        border-color: var(--color-accent-blue);
    }

    .report-card.coming-soon {
        opacity: 0.7;
        pointer-events: none;
    }

    .report-card.coming-soon:hover {
        transform: none;
        box-shadow: 0 8px 25px var(--color-shadow);
        border-color: var(--color-border);
    }

    .report-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--color-accent-blue), var(--color-accent-purple));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5em;
        color: white;
        flex-shrink: 0;
    }

    .report-card.coming-soon .report-icon {
        background: linear-gradient(135deg, var(--color-text-muted), var(--color-border));
    }

    .report-content {
        flex: 1;
    }

    .report-content h3 {
        font-size: 1.3em;
        font-weight: 600;
        margin: 0 0 8px 0;
        color: var(--color-text-primary);
    }

    .report-content p {
        margin: 0 0 15px 0;
        color: var(--color-text-secondary);
        font-size: 0.95em;
    }

    .report-features {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .feature-tag {
        background: var(--color-accent-blue);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75em;
        font-weight: 600;
        text-transform: uppercase;
    }

    .report-card.coming-soon .feature-tag {
        background: var(--color-text-muted);
    }

    .report-arrow {
        color: var(--color-accent-blue);
        font-size: 1.2em;
        transition: var(--transition-fast);
    }

    .report-card:hover .report-arrow {
        transform: translateX(5px);
    }

    .report-card.coming-soon .report-arrow {
        color: var(--color-text-muted);
    }

    .info-section {
        margin-top: 30px;
    }

    .info-card {
        background: var(--color-bg-card);
        border-radius: 16px;
        padding: 25px;
        display: flex;
        align-items: flex-start;
        gap: 20px;
        border: 1px solid var(--color-border);
    }

    .info-card i {
        font-size: 2em;
        color: var(--color-accent-blue);
        flex-shrink: 0;
        margin-top: 5px;
    }

    .info-content h3 {
        font-size: 1.4em;
        font-weight: 600;
        margin: 0 0 15px 0;
        color: var(--color-text-primary);
    }

    .info-content p {
        margin: 0 0 15px 0;
        color: var(--color-text-secondary);
        line-height: 1.6;
    }

    .info-content ul {
        margin: 0;
        padding-left: 20px;
    }

    .info-content li {
        margin-bottom: 8px;
        color: var(--color-text-secondary);
        line-height: 1.5;
    }

    .info-content strong {
        color: var(--color-text-primary);
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Dashboard Principal</h1>
        <p>Bienvenido al sistema de inventario</p>
    </div>

    <div class="metrics-grid">
        <!-- Card Ventas del Día -->
        <div class="metric-card">
            <div class="metric-card-inner">
                <div class="metric-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="metric-content">
                    <div class="metric-value">S/ <?php echo number_format($data['ventasDia'], 2); ?></div>
                    <div class="metric-label">Ventas del Día</div>
                </div>
            </div>
        </div>

        <!-- Card Meta de Ventas -->
        <div class="metric-card">
            <div class="metric-card-inner">
                <div class="metric-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <div class="metric-content">
                    <div class="metric-value"><?php echo number_format($data['porcentajeMeta'], 1); ?>%</div>
                    <div class="metric-label">Meta de Ventas</div>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: <?php echo min($data['porcentajeMeta'], 100); ?>%"></div>
                    </div>
                    <div class="metric-subtext">S/ <?php echo number_format($data['metaVentas'], 2); ?></div>
                </div>
            </div>
        </div>

        <!-- Card Alertas ROP -->
        <div class="metric-card alerts-card">
            <div class="metric-card-inner">
                <div class="metric-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="metric-content">
                    <div class="metric-value"><?php echo count($data['alertasROP']); ?></div>
                    <div class="metric-label">Alertas ROP</div>
                    <div class="alerts-list">
                        <ul id="alertas-list">
                            <?php foreach ($data['alertasROP'] as $alerta): ?>
                                <li><?php echo htmlspecialchars($alerta['nombre']); ?> - Stock: <?php echo $alerta['stock']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="quick-actions">
        <h2>Módulos Disponibles</h2>
        <div class="actions-grid">
            <?php if ($data['rol'] === 'admin'): ?>
                <a href="/sistema/?route=reportes" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="action-content">
                        <h3>Reportes</h3>
                        <p>Ver reportes y estadísticas</p>
                    </div>
                </a>
                <a href="/sistema/?route=usuario" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="action-content">
                        <h3>Usuarios</h3>
                        <p>Gestionar usuarios del sistema</p>
                    </div>
                </a>
                <a href="/sistema/?route=inventario" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="action-content">
                        <h3>Inventario</h3>
                        <p>Administrar productos y stock</p>
                    </div>
                </a>
                <a href="/sistema/?route=ventas/pos" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <div class="action-content">
                        <h3>Punto de Venta</h3>
                        <p>Realizar ventas con POS moderno</p>
                    </div>
                </a>
            <?php elseif ($data['rol'] === 'vendedor'): ?>
                <a href="/sistema/?route=ventas/pos" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <div class="action-content">
                        <h3>Punto de Venta</h3>
                        <p>Realizar ventas</p>
                    </div>
                </a>
                <a href="/sistema/?route=ventas/lista" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="action-content">
                        <h3>Lista de Ventas</h3>
                        <p>Ver historial de ventas</p>
                    </div>
                </a>
            <?php elseif ($data['rol'] === 'inventario'): ?>
                <a href="/sistema/?route=inventario" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="action-content">
                        <h3>Lista de Productos</h3>
                        <p>Gestionar inventario</p>
                    </div>
                </a>
                <a href="/sistema/?route=inventario/alertas" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="action-content">
                        <h3>Alertas</h3>
                        <p>Ver alertas de stock</p>
                    </div>
                </a>
            <?php else: ?>
                <div class="no-access">
                    <i class="fas fa-lock"></i>
                    <p>No hay módulos disponibles para su rol.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Polling de alertas ROP cada 30 segundos
    function updateAlertas() {
        fetch('/sistema/?route=dashboard/getAlertasAjax')
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('alertas-list');
                const countElement = document.querySelector('.alerts-card .metric-value');

                list.innerHTML = '';
                data.forEach(alerta => {
                    const li = document.createElement('li');
                    li.textContent = `${alerta.nombre} - Stock: ${alerta.stock}`;
                    list.appendChild(li);
                });

                // Update the count
                if (countElement) {
                    countElement.textContent = data.length;
                }
            })
            .catch(error => console.error('Error al actualizar alertas:', error));
    }

    setInterval(updateAlertas, 30000); // 30 segundos
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
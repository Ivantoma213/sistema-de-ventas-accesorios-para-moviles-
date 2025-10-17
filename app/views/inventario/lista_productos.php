<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-boxes"></i>
            <h1>Inventario de Productos</h1>
        </div>
        <div class="page-actions">
            <a href="/sistema/?route=inventario/alertas" class="action-btn secondary">
                <i class="fas fa-exclamation-triangle"></i>
                Alertas
            </a>
            <a href="/sistema/?route=inventario/entradas" class="action-btn secondary">
                <i class="fas fa-truck"></i>
                Entradas
            </a>
            <a href="/sistema/?route=inventario/create" class="action-btn primary">
                <i class="fas fa-plus"></i>
                Nuevo Producto
            </a>
        </div>
    </div>

    <div class="content-grid">
        <!-- Estadísticas rápidas -->
        <div class="stats-overview">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo count($productos); ?></div>
                    <div class="stat-label">Total Productos</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo array_sum(array_column($productos, 'stock')); ?></div>
                    <div class="stat-label">Total Stock</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo count(array_filter($productos, function($p) { return $p['stock'] <= $p['stock_minimo']; })); ?></div>
                    <div class="stat-label">Productos con Stock Bajo</div>
                </div>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="data-table-container">
            <div class="table-header">
                <h3>Productos Registrados</h3>
                <div class="table-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar productos..." id="searchInput">
                </div>
            </div>

            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td>
                                    <div class="id-badge">#<?php echo $producto['id']; ?></div>
                                </td>
                                <td>
                                    <div class="product-info">
                                        <div class="product-name"><?php echo htmlspecialchars($producto['nombre']); ?></div>
                                        <div class="product-desc"><?php echo htmlspecialchars($producto['descripcion']); ?></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="price">
                                        <span class="currency">S/</span>
                                        <span class="value"><?php echo number_format($producto['precio'], 2); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="stock-info">
                                        <span class="stock-value <?php echo $producto['stock'] <= $producto['stock_minimo'] ? 'low-stock' : ''; ?>">
                                            <?php echo $producto['stock']; ?> <?php echo $producto['unidad_medida']; ?>
                                        </span>
                                        <?php if ($producto['stock'] <= $producto['stock_minimo']): ?>
                                            <i class="fas fa-exclamation-triangle warning-icon" title="Stock bajo"></i>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($producto['categoria']); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $producto['estado'] === 'activo' ? 'active' : 'inactive'; ?>">
                                        <?php echo htmlspecialchars($producto['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="/sistema/?route=inventario/edit/<?php echo $producto['id']; ?>" class="btn-action edit" title="Editar producto">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/sistema/?route=inventario/delete/<?php echo $producto['id']; ?>" class="btn-action delete" title="Eliminar producto" onclick="return confirm('¿Está seguro de eliminar este producto?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (empty($productos)): ?>
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h3>No hay productos registrados</h3>
                    <p>Comienza agregando tu primer producto al inventario.</p>
                    <a href="/sistema/?route=inventario/create" class="action-btn primary">
                        <i class="fas fa-plus"></i>
                        Agregar primer producto
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Función de búsqueda en tiempo real
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.modern-table tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-cash-register"></i>
            <h1>Lista de Ventas</h1>
        </div>
        <div class="page-actions">
            <a href="/sistema/?route=ventas/pos" class="action-btn primary">
                <i class="fas fa-plus"></i>
                Nueva Venta
            </a>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            Venta procesada exitosamente.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            Venta actualizada exitosamente.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            Venta eliminada exitosamente. El stock ha sido restaurado.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="content-grid">
        <!-- Estadísticas rápidas -->
        <div class="stats-overview">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo count($ventas); ?></div>
                    <div class="stat-label">Total Ventas</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">S/ <?php echo number_format(array_sum(array_column($ventas, 'total')), 2); ?></div>
                    <div class="stat-label">Total Ingresos</div>
                </div>
            </div>
        </div>

        <!-- Tabla de ventas -->
        <div class="data-table-container">
            <div class="table-header">
                <h3>Ventas Registradas</h3>
                <div class="table-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar ventas..." id="searchInput">
                </div>
            </div>

            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>ID Venta</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Usuario</th>
                            <th>Caja</th>
                            <th>Tipo Pago</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventas as $venta): ?>
                            <tr>
                                <td>
                                    <div class="id-badge">#<?php echo $venta['id']; ?></div>
                                </td>
                                <td>
                                    <div class="date-info">
                                        <i class="fas fa-calendar"></i>
                                        <?php echo date('d/m/Y H:i', strtotime($venta['fecha'])); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="amount success">
                                        <span class="currency">S/</span>
                                        <span class="value"><?php echo number_format($venta['total'], 2); ?></span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($venta['usuario_nombre'] ?? 'Usuario #' . $venta['id_usuario']); ?></td>
                                <td>
                                    <div class="caja-info">
                                        <i class="fas fa-cash-register"></i>
                                        <?php echo htmlspecialchars($venta['caja_nombre'] ?? 'Caja #' . $venta['id_caja']); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="pago-info">
                                        <i class="fas fa-credit-card"></i>
                                        <?php echo htmlspecialchars($venta['tipo_pago_nombre'] ?? 'Pago #' . $venta['id_tipo_pago']); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="/sistema/?route=ventas/detalle/<?php echo $venta['id']; ?>" class="btn-action view" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/sistema/?route=ventas/edit/<?php echo $venta['id']; ?>" class="btn-action edit" title="Editar venta">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/sistema/?route=ventas/delete/<?php echo $venta['id']; ?>" class="btn-action delete" title="Eliminar venta" onclick="return confirm('¿Estás seguro de que deseas eliminar esta venta? Esta acción no se puede deshacer.')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (empty($ventas)): ?>
                <div class="empty-state">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>No hay ventas registradas</h3>
                    <p>Aún no se han realizado ventas en el sistema.</p>
                    <a href="/sistema/?route=ventas/pos" class="action-btn primary">
                        <i class="fas fa-plus"></i>
                        Realizar primera venta
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

    // Función para confirmar eliminación
    function confirmDelete(ventaId) {
        if (confirm('¿Estás seguro de que deseas eliminar esta venta? Esta acción no se puede deshacer.')) {
            // Crear formulario para POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/sistema/?route=ventas/delete/' + ventaId;

            // Agregar token CSRF si existe
            const csrfToken = document.querySelector('input[name="csrf_token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = 'csrf_token';
                csrfInput.value = csrfToken.value;
                form.appendChild(csrfInput);
            }

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
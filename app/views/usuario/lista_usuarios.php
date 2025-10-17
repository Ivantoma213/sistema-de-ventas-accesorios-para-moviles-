<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-users"></i>
            <h1>Gestión de Usuarios</h1>
        </div>
        <div class="page-actions">
            <a href="/sistema/?route=usuario/create" class="action-btn primary">
                <i class="fas fa-user-plus"></i>
                Nuevo Usuario
            </a>
        </div>
    </div>

    <div class="content-grid">
        <!-- Estadísticas rápidas -->
        <div class="stats-overview">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo count($usuarios); ?></div>
                    <div class="stat-label">Total Usuarios</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo count(array_filter($usuarios, function($u) { return $u['rol'] === 'admin'; })); ?></div>
                    <div class="stat-label">Administradores</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo count(array_filter($usuarios, function($u) { return $u['estado'] === 'activo'; })); ?></div>
                    <div class="stat-label">Usuarios Activos</div>
                </div>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="data-table-container">
            <div class="table-header">
                <h3>Usuarios Registrados</h3>
                <div class="table-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar usuarios..." id="searchInput">
                </div>
            </div>

            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td>
                                    <div class="id-badge">#<?php echo $usuario['id']; ?></div>
                                </td>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                        <div class="user-details">
                                            <div class="user-name"><?php echo htmlspecialchars($usuario['nombre']); ?></div>
                                            <div class="user-email"><?php echo htmlspecialchars($usuario['email']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td>
                                    <span class="role-badge <?php echo $usuario['rol']; ?>">
                                        <i class="fas fa-<?php echo $usuario['rol'] === 'admin' ? 'user-shield' : ($usuario['rol'] === 'vendedor' ? 'cash-register' : 'warehouse'); ?>"></i>
                                        <?php echo htmlspecialchars($usuario['rol']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $usuario['estado'] === 'activo' ? 'active' : 'inactive'; ?>">
                                        <?php echo htmlspecialchars($usuario['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="date-info">
                                        <i class="fas fa-calendar-plus"></i>
                                        <?php echo date('d/m/Y', strtotime($usuario['created_at'])); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="/sistema/?route=usuario/edit/<?php echo $usuario['id']; ?>" class="btn-action edit" title="Editar usuario">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/sistema/?route=usuario/delete/<?php echo $usuario['id']; ?>" class="btn-action delete" title="Eliminar usuario" onclick="return confirm('¿Está seguro de eliminar este usuario?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (empty($usuarios)): ?>
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h3>No hay usuarios registrados</h3>
                    <p>Comienza creando el primer usuario del sistema.</p>
                    <a href="/sistema/?route=usuario/create" class="action-btn primary">
                        <i class="fas fa-user-plus"></i>
                        Crear primer usuario
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
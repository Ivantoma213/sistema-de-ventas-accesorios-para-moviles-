<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-<?php echo isset($usuario) ? 'edit' : 'user-plus'; ?>"></i>
            <h1><?php echo isset($usuario) ? 'Editar Usuario' : 'Crear Nuevo Usuario'; ?></h1>
        </div>
        <div class="page-actions">
            <a href="/sistema/?route=usuario" class="action-btn secondary">
                <i class="fas fa-arrow-left"></i>
                Volver a Usuarios
            </a>
        </div>
    </div>

    <div class="content-grid">
        <div class="user-form">
            <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
                <div class="error-messages">
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <form action="<?php echo isset($usuario) ? '/sistema/?route=usuario/update/' . $usuario['id'] : '/sistema/?route=usuario/store'; ?>" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="nombre">
                            <i class="fas fa-tag"></i>
                            Nombre del Usuario
                        </label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo $usuario['nombre'] ?? ''; ?>" placeholder="Ingrese el nombre del usuario" required>
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i>
                            Correo Electrónico
                        </label>
                        <input type="email" id="email" name="email" value="<?php echo $usuario['email'] ?? ''; ?>" placeholder="usuario@email.com" required>
                    </div>

                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i>
                            <?php echo isset($usuario) ? 'Nueva Contraseña (opcional)' : 'Contraseña'; ?>
                        </label>
                        <input type="password" id="password" name="password" placeholder="••••••••" <?php echo !isset($usuario) ? 'required' : ''; ?>>
                    </div>

                    <div class="form-group">
                        <label for="id_rol">
                            <i class="fas fa-user-tag"></i>
                            Rol de Usuario
                        </label>
                        <select id="id_rol" name="id_rol" required>
                            <option value="">Seleccione un rol</option>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?php echo $rol['id']; ?>" <?php echo (isset($usuario) && $usuario['id_rol'] == $rol['id']) ? 'selected' : ''; ?>><?php echo $rol['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id_estado">
                            <i class="fas fa-toggle-on"></i>
                            Estado de Usuario
                        </label>
                        <select id="id_estado" name="id_estado" required>
                            <option value="">Seleccione estado</option>
                            <?php foreach ($estados as $estado): ?>
                                <option value="<?php echo $estado['id']; ?>" <?php echo (isset($usuario) && $usuario['id_estado'] == $estado['id']) ? 'selected' : ''; ?>><?php echo $estado['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="action-btn primary">
                        <i class="fas fa-save"></i>
                        <?php echo isset($usuario) ? 'Actualizar Usuario' : 'Crear Usuario'; ?>
                    </button>
                    <a href="/sistema/?route=usuario" class="action-btn secondary">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
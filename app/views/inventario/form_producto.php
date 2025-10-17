<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-<?php echo isset($producto) ? 'edit' : 'plus-circle'; ?>"></i>
            <h1><?php echo isset($producto) ? 'Editar Producto' : 'Crear Nuevo Producto'; ?></h1>
        </div>
        <div class="page-actions">
            <a href="/sistema/?route=inventario" class="action-btn secondary">
                <i class="fas fa-arrow-left"></i>
                Volver a Inventario
            </a>
        </div>
    </div>

    <div class="content-grid">
        <div class="product-form">
            <form action="<?php echo isset($producto) ? '/sistema/?route=inventario/update/' . $producto['id'] : '/sistema/?route=inventario/store'; ?>" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="nombre">
                            <i class="fas fa-tag"></i>
                            Nombre del Producto
                        </label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo $producto['nombre'] ?? ''; ?>" placeholder="Ingrese el nombre del producto" required>
                    </div>

                    <div class="form-group">
                        <label for="precio">
                            <i class="fas fa-dollar-sign"></i>
                            Precio (S/.)
                        </label>
                        <input type="number" step="0.01" id="precio" name="precio" value="<?php echo $producto['precio'] ?? ''; ?>" placeholder="0.00" required>
                    </div>

                    <div class="form-group">
                        <label for="stock">
                            <i class="fas fa-boxes"></i>
                            Stock Actual
                        </label>
                        <input type="number" id="stock" name="stock" value="<?php echo $producto['stock'] ?? ''; ?>" placeholder="0" required>
                    </div>

                    <div class="form-group">
                        <label for="stock_minimo">
                            <i class="fas fa-exclamation-triangle"></i>
                            Stock Mínimo
                        </label>
                        <input type="number" id="stock_minimo" name="stock_minimo" value="<?php echo $producto['stock_minimo'] ?? ''; ?>" placeholder="0" required>
                    </div>

                    <div class="form-group">
                        <label for="id_categoria">
                            <i class="fas fa-folder"></i>
                            Categoría
                        </label>
                        <select id="id_categoria" name="id_categoria" required>
                            <option value="">Seleccione una categoría</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo (isset($producto) && $producto['id_categoria'] == $cat['id']) ? 'selected' : ''; ?>><?php echo $cat['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id_unidad_medida">
                            <i class="fas fa-balance-scale"></i>
                            Unidad de Medida
                        </label>
                        <select id="id_unidad_medida" name="id_unidad_medida" required>
                            <option value="">Seleccione unidad</option>
                            <?php foreach ($unidades as $uni): ?>
                                <option value="<?php echo $uni['id']; ?>" <?php echo (isset($producto) && $producto['id_unidad_medida'] == $uni['id']) ? 'selected' : ''; ?>><?php echo $uni['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id_estado">
                            <i class="fas fa-toggle-on"></i>
                            Estado
                        </label>
                        <select id="id_estado" name="id_estado" required>
                            <option value="">Seleccione estado</option>
                            <?php foreach ($estados as $est): ?>
                                <option value="<?php echo $est['id']; ?>" <?php echo (isset($producto) && $producto['id_estado'] == $est['id']) ? 'selected' : ''; ?>><?php echo $est['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="descripcion">
                            <i class="fas fa-align-left"></i>
                            Descripción
                        </label>
                        <textarea id="descripcion" name="descripcion" placeholder="Ingrese una descripción del producto (opcional)" rows="4"><?php echo $producto['descripcion'] ?? ''; ?></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="action-btn primary">
                        <i class="fas fa-save"></i>
                        <?php echo isset($producto) ? 'Actualizar Producto' : 'Crear Producto'; ?>
                    </button>
                    <a href="/sistema/?route=inventario" class="action-btn secondary">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
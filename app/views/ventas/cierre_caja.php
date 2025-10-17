<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h2>Cierre de Caja</h2>
    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;">Cierre procesado exitosamente.</p>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>
    <form action="/sistema/?route=ventas/closeCash" method="post">
        <label for="id_caja">Caja:</label>
        <select name="id_caja" required>
            <?php foreach ($cajas as $caja): ?>
                <option value="<?php echo $caja['id']; ?>"><?php echo $caja['nombre']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>

        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
        <br>

        <label for="total_esperado">Total Esperado:</label>
        <input type="number" step="0.01" name="total_esperado" required>
        <br>

        <button type="submit">Cerrar Caja</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h2>Alertas de Inventario</h2>
    <a href="/inventario" class="btn">Volver a Productos</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Stock Actual</th>
                <th>ROP Calculado</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alertas as $alerta): ?>
                <tr>
                    <td><?php echo $alerta['id']; ?></td>
                    <td><?php echo $alerta['nombre']; ?></td>
                    <td><?php echo $alerta['stock']; ?></td>
                    <td><?php echo number_format($alerta['rop'], 2); ?></td>
                    <td style="color: red;">Bajo stock</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
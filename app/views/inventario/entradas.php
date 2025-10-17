<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h2>Entradas de Inventario (Compras)</h2>
    <a href="/inventario" class="btn">Volver a Productos</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($compras as $compra): ?>
                <tr>
                    <td><?php echo $compra['id']; ?></td>
                    <td><?php echo $compra['fecha']; ?></td>
                    <td><?php echo $compra['proveedor']; ?></td>
                    <td><?php echo $compra['total']; ?></td>
                    <td>
                        <a href="#" class="btn">Ver Detalles</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
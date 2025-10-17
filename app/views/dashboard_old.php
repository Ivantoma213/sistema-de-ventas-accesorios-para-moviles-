<?php include __DIR__ . '/layouts/header.php'; ?>

<h2>Dashboard de Inteligencia de Negocio</h2>

<div>
    <h3>Total Ventas (últimos 30 días): <?php echo $ventas['total_ventas'] ?? 0; ?></h3>
    <h3>Ganancias (últimos 30 días): <?php echo $ganancias['ganancias'] ?? 0; ?></h3>
</div>

<div>
    <h3>Productos Más Vendidos</h3>
    <canvas id="productosChart" width="400" height="200"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const productosData = <?php echo json_encode($productos); ?>;
    const ctx = document.getElementById('productosChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: productosData.map(p => p.nombre),
            datasets: [{
                label: 'Cantidad Vendida',
                data: productosData.map(p => p.cantidad_vendida),
                backgroundColor: 'rgba(17, 39, 39, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
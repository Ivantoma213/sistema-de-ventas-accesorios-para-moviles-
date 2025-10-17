<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h2>Módulo de Ventas</h2>
    <p>Selecciona una opción:</p>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Punto de Venta</h5>
                    <p class="card-text">Realizar ventas en el sistema POS</p>
                    <a href="/sistema/ventas/pos" class="btn btn-primary">Ir al POS</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Lista de Ventas</h5>
                    <p class="card-text">Ver historial de ventas realizadas</p>
                    <a href="/sistema/ventas/lista" class="btn btn-info">Ver Ventas</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Cierre de Caja</h5>
                    <p class="card-text">Realizar cierre de caja diario</p>
                    <a href="/sistema/ventas/closeCash" class="btn btn-warning">Cerrar Caja</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
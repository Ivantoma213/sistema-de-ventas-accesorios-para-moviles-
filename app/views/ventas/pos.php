<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="/sistema/css/ventas.css?v=1.9">

<div class="pos-container <?php
    $rol = $_SESSION['user']['rol'] ?? '';
    if ($rol === 'vendedor') {
        echo 'vendedor-mode';
    } elseif ($rol === 'admin') {
        echo 'admin-mode';
    }
?>">
    <div class="pos-header">
        <div class="pos-title-section">
            <div class="pos-title">
                <i class="fas fa-cash-register"></i>
                <div>
                    <h1>Punto de Venta</h1>
                    <p class="pos-subtitle">Sistema de ventas profesional</p>
                </div>
            </div>
            <div class="pos-stats">
                <div class="stat-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="cart-count">0</span>
                    <small>items</small>
                </div>
                <div class="stat-item">
                    <i class="fas fa-dollar-sign"></i>
                    <span id="cart-total">S/ 0.00</span>
                    <small>total</small>
                </div>
            </div>
        </div>
        <div class="pos-info">
            <div class="user-info">
                <i class="fas fa-user-tie"></i>
                <div>
                    <span class="user-name"><?php echo $_SESSION['user']['nombre'] ?? 'Usuario'; ?></span>
                    <small class="user-role"><?php echo $_SESSION['user']['rol'] ?? 'Rol'; ?></small>
                </div>
            </div>
            <div class="date-info">
                <i class="fas fa-calendar-alt"></i>
                <div>
                    <span class="current-date"><?php echo date('d/m/Y'); ?></span>
                    <small class="current-time"><?php echo date('H:i:s'); ?></small>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="pos-content">
        <!-- Panel de Productos -->
        <div class="products-panel">
            <div class="panel-header">
                <h3><i class="fas fa-boxes"></i> Productos Disponibles</h3>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="productSearch" placeholder="Buscar producto...">
                </div>
            </div>

            <div class="products-grid" id="productsGrid">
                <?php foreach ($productos as $producto): ?>
                    <div class="product-card" data-id="<?php echo $producto['id']; ?>" data-name="<?php echo htmlspecialchars($producto['nombre']); ?>" data-price="<?php echo $producto['precio']; ?>" data-stock="<?php echo $producto['stock']; ?>">
                        <div class="product-image">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="product-info">
                            <h4><?php echo htmlspecialchars($producto['nombre']); ?></h4>
                            <div class="product-price">S/ <?php echo number_format($producto['precio'], 2); ?></div>
                            <div class="product-stock <?php echo $producto['stock'] <= $producto['stock_minimo'] ? 'low-stock' : ''; ?>">
                                Stock: <?php echo $producto['stock']; ?>
                                <?php if ($producto['stock'] <= $producto['stock_minimo']): ?>
                                    <i class="fas fa-exclamation-triangle"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="product-actions">
                            <div class="quantity-controls">
                                <button type="button" class="qty-btn minus" onclick="cambiarCantidad(<?php echo $producto['id']; ?>, -1)">-</button>
                                <input type="number" class="qty-input" id="qty_<?php echo $producto['id']; ?>" value="0" min="0" max="<?php echo $producto['stock']; ?>">
                                <button type="button" class="qty-btn plus" onclick="cambiarCantidad(<?php echo $producto['id']; ?>, 1)">+</button>
                            </div>
                            <button type="button" class="add-to-cart-btn" onclick="agregarProducto(<?php echo $producto['id']; ?>, '<?php echo addslashes($producto['nombre']); ?>', <?php echo $producto['precio']; ?>)">
                                <i class="fas fa-cart-plus"></i>
                                Agregar
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Panel del Carrito -->
        <div class="cart-panel">
            <div class="panel-header">
                <h3>Carrito de Compras</h3>
                <div class="cart-actions">
                    <button type="button" class="btn-icon" onclick="limpiarCarrito()" title="Limpiar carrito">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button type="button" class="btn-icon" onclick="guardarCarrito()" title="Guardar carrito">
                        <i class="fas fa-save"></i>
                    </button>
                </div>
            </div>

            <div class="cart-items" id="cartItems">
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>El carrito está vacío</p>
                    <small>Selecciona productos para comenzar</small>
                </div>
            </div>

            <!-- Resumen de Pago -->
            <div class="payment-summary">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span id="subtotal">S/ 0.00</span>
                </div>
                <div class="summary-row">
                    <label for="descuento" style="display: flex; align-items: center; gap: 8px; margin: 0;">
                        <i class="fas fa-percent"></i>
                        Descuento (%):
                    </label>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <input type="number" id="descuento" min="0" max="100" step="0.01" value="0" style="width: 60px; padding: 4px; border: 1px solid #ddd; border-radius: 4px;" onchange="calcularTotales()">
                        <span>%</span>
                    </div>
                </div>
                <div class="summary-row">
                    <span>Descuento aplicado:</span>
                    <span id="descuentoAplicado">S/ 0.00</span>
                </div>
                <div class="summary-row total-row">
                    <span><strong>Total:</strong></span>
                    <span id="total"><strong>S/ 0.00</strong></span>
                </div>
            </div>

            <!-- Método de Pago -->
            <div class="payment-method">
                <h4><i class="fas fa-credit-card"></i> Método de Pago</h4>
                <div class="payment-options">
                    <label class="payment-option">
                        <input type="radio" name="id_tipo_pago" value="1" checked>
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Efectivo</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="id_tipo_pago" value="2">
                        <i class="fas fa-credit-card"></i>
                        <span>Tarjeta</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="id_tipo_pago" value="3">
                        <i class="fas fa-university"></i>
                        <span>Transferencia</span>
                    </label>
                </div>

                <!-- Calculadora de Cambio (solo para efectivo) -->
                <div class="change-calculator" id="changeCalculator" style="display: block;">
                    <h4><i class="fas fa-calculator"></i> Calculadora de Cambio</h4>
                    <div class="calculator-inputs">
                        <div class="input-group">
                            <label for="receivedAmount">Monto Recibido:</label>
                            <div class="currency-input">
                                <span class="currency-symbol">S/</span>
                                <input type="number" id="receivedAmount" step="0.10" placeholder="0.00" oninput="calcularCambio()">
                            </div>
                        </div>
                        <div class="change-result">
                            <div class="change-amount">
                                <span>Cambio:</span>
                                <span id="changeAmount" class="change-value">S/ 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selección de Caja -->
            <div class="cash-register-selection">
                <label for="id_caja">Caja:</label>
                <select name="id_caja" id="id_caja" required>
                    <?php foreach ($cajas as $caja): ?>
                        <option value="<?php echo $caja['id']; ?>"><?php echo $caja['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <form action="/sistema/?route=ventas/processSale" method="post" id="ventaForm" onsubmit="return validarVenta()">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="total" id="totalInput" value="0">
                <input type="hidden" name="descuento" id="descuentoInput" value="0">
                <input type="hidden" name="productos" id="productosInput" value="[]">

                <div class="checkout-actions">
                    <button type="button" class="btn-secondary" onclick="limpiarCarrito()">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn-primary" id="checkoutBtn" disabled>
                        <i class="fas fa-check"></i>
                        Procesar Venta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let carrito = [];
let total = 0;

function cambiarCantidad(productId, delta) {
    const input = document.getElementById('qty_' + productId);
    let nuevaCantidad = parseInt(input.value) + delta;
    const maxStock = parseInt(input.getAttribute('max'));

    if (nuevaCantidad < 0) nuevaCantidad = 0;
    if (nuevaCantidad > maxStock) nuevaCantidad = maxStock;

    input.value = nuevaCantidad;
}

function agregarProducto(id, nombre, precio) {
    const cantidad = parseInt(document.getElementById('qty_' + id).value);
    if (cantidad > 0) {
        const existente = carrito.find(p => p.id === id);
        if (existente) {
            existente.cantidad += cantidad;
        } else {
            carrito.push({id, nombre, precio, cantidad});
        }
        // Reset quantity input
        document.getElementById('qty_' + id).value = 0;
        actualizarCarrito();
        mostrarNotificacion(`${cantidad} ${nombre} agregado(s) al carrito`, 'success');
    }
}

function removerProducto(id) {
    carrito = carrito.filter(p => p.id !== id);
    actualizarCarrito();
}

function cambiarCantidadCarrito(id, nuevaCantidad) {
    if (nuevaCantidad <= 0) {
        removerProducto(id);
        return;
    }

    const producto = carrito.find(p => p.id === id);
    if (producto) {
        producto.cantidad = nuevaCantidad;
        actualizarCarrito();
    }
}

function actualizarCarrito() {
    const cartItems = document.getElementById('cartItems');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const cartCount = document.getElementById('cart-count');
    const cartTotal = document.getElementById('cart-total');

    if (carrito.length === 0) {
        cartItems.innerHTML = `
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <p>El carrito está vacío</p>
                <small>Selecciona productos para comenzar</small>
            </div>
        `;
        checkoutBtn.disabled = true;
        cartCount.textContent = '0';
        cartTotal.textContent = 'S/ 0.00';
    } else {
        let html = '';
        let totalItems = 0;
        carrito.forEach(producto => {
            const subtotal = producto.precio * producto.cantidad;
            totalItems += producto.cantidad;
            html += `
                <div class="cart-item">
                    <div class="item-info">
                        <h4>${producto.nombre}</h4>
                        <div class="item-details">
                            <span class="item-price">S/ ${producto.precio.toFixed(2)} c/u</span>
                            <span class="item-qty">x${producto.cantidad}</span>
                        </div>
                    </div>
                    <div class="item-actions">
                        <div class="quantity-controls">
                            <button type="button" class="qty-btn minus" onclick="cambiarCantidadCarrito(${producto.id}, ${producto.cantidad - 1})">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" class="qty-input" value="${producto.cantidad}" min="1" onchange="cambiarCantidadCarrito(${producto.id}, parseInt(this.value))">
                            <button type="button" class="qty-btn plus" onclick="cambiarCantidadCarrito(${producto.id}, ${producto.cantidad + 1})">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="item-subtotal">
                            <span class="subtotal-amount">S/ ${subtotal.toFixed(2)}</span>
                            <button type="button" class="remove-item" onclick="removerProducto(${producto.id})" title="Remover producto">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        cartItems.innerHTML = html;
        checkoutBtn.disabled = false;
        cartCount.textContent = totalItems;
        cartTotal.textContent = 'S/ ' + (total + (total * 0.18)).toFixed(2);
    }

    calcularTotales();
    calcularCambio();
}

function calcularTotales() {
    total = 0;
    carrito.forEach(producto => {
        total += producto.precio * producto.cantidad;
    });

    const subtotal = total;
    const descuentoPorcentaje = parseFloat(document.getElementById('descuento').value) || 0;
    const descuentoAplicado = subtotal * (descuentoPorcentaje / 100);
    const totalConDescuento = subtotal - descuentoAplicado;

    document.getElementById('subtotal').textContent = 'S/ ' + subtotal.toFixed(2);
    document.getElementById('descuentoAplicado').textContent = 'S/ ' + descuentoAplicado.toFixed(2);
    document.getElementById('total').innerHTML = '<strong>S/ ' + totalConDescuento.toFixed(2) + '</strong>';
    document.getElementById('totalInput').value = totalConDescuento.toFixed(2);
    document.getElementById('descuentoInput').value = descuentoPorcentaje;
    document.getElementById('productosInput').value = JSON.stringify(carrito);
}

function setQuickAmount(amount) {
    const receivedInput = document.getElementById('receivedAmount');
    const descuentoPorcentaje = parseFloat(document.getElementById('descuento').value) || 0;
    const descuentoAplicado = total * (descuentoPorcentaje / 100);
    const totalAmount = total - descuentoAplicado; // Con descuento aplicado
    receivedInput.value = Math.max(totalAmount, amount).toFixed(2);
    calcularCambio();
}

function calcularCambio() {
    const receivedAmount = parseFloat(document.getElementById('receivedAmount').value) || 0;
    const descuentoPorcentaje = parseFloat(document.getElementById('descuento').value) || 0;
    const descuentoAplicado = total * (descuentoPorcentaje / 100);
    const totalAmount = total - descuentoAplicado; // Con descuento aplicado
    const change = receivedAmount - totalAmount;

    const changeElement = document.getElementById('changeAmount');

    if (changeElement) {
        if (change > 0) {
            changeElement.textContent = 'S/ ' + change.toFixed(2);
            changeElement.className = 'change-value positive';
        } else if (change === 0) {
            changeElement.textContent = 'S/ 0.00 (Exacto)';
            changeElement.className = 'change-value exact';
        } else {
            changeElement.textContent = 'S/ ' + Math.abs(change).toFixed(2) + ' (Falta)';
            changeElement.className = 'change-value negative';
        }
    }
}

function calcularDesgloseCambio(cambio) {
    const denominaciones = [200, 100, 50, 20, 10, 5, 2, 1, 0.50, 0.20, 0.10];
    let restante = cambio;
    let desglose = '';

    denominaciones.forEach(denominacion => {
        if (restante >= denominacion) {
            const cantidad = Math.floor(restante / denominacion);
            if (cantidad > 0) {
                const tipo = denominacion >= 1 ? 'billete' : 'moneda';
                const simbolo = denominacion >= 1 ? 'S/' : '¢';
                const valor = denominacion >= 1 ? denominacion : denominacion * 10;
                desglose += `<div class="breakdown-item">${cantidad} ${tipo}(s) de ${simbolo} ${valor}</div>`;
                restante = (restante % denominacion).toFixed(2);
            }
        }
    });

    return desglose;
}

function limpiarCarrito() {
    carrito = [];
    actualizarCarrito();
    mostrarNotificacion('Carrito limpiado', 'info');
}

function validarVenta() {
    if (carrito.length === 0) {
        mostrarNotificacion('El carrito está vacío', 'error');
        return false;
    }

    const idCaja = document.getElementById('id_caja').value;
    if (!idCaja) {
        mostrarNotificacion('Seleccione una caja', 'error');
        return false;
    }

    return true;
}

function mostrarNotificacion(mensaje, tipo = 'info') {
    // Crear notificación temporal
    const notification = document.createElement('div');
    notification.className = `notification notification-${tipo}`;
    notification.innerHTML = `
        <i class="fas fa-${tipo === 'success' ? 'check' : tipo === 'error' ? 'times' : 'info'}"></i>
        ${mensaje}
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Búsqueda de productos
document.getElementById('productSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const products = document.querySelectorAll('.product-card');

    products.forEach(product => {
        const productName = product.dataset.name.toLowerCase();
        if (productName.includes(searchTerm)) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
});

// Cambiar método de pago
document.querySelectorAll('input[name="id_tipo_pago"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const changeCalculator = document.getElementById('changeCalculator');
        if (this.value === '1') { // Efectivo
            changeCalculator.style.display = 'block';
        } else {
            changeCalculator.style.display = 'none';
        }
    });
});

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    actualizarCarrito();
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
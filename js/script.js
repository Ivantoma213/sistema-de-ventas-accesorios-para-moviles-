// Función para confirmaciones
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Función para AJAX polling
function startPolling(url, interval, callback) {
    setInterval(function() {
        fetch(url)
            .then(response => response.json())
            .then(data => callback(data))
            .catch(error => console.error('Error en polling:', error));
    }, interval);
}

// Función para mostrar mensajes de alerta
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    document.body.appendChild(alertDiv);
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

// Función para validar formularios básicos
function validateForm(formId) {
    const form = document.getElementById(formId);
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = 'red';
            isValid = false;
        } else {
            input.style.borderColor = '#ccc';
        }
    });
    return isValid;
}

// Función para enviar formularios vía AJAX
function submitFormAjax(formId, url, callback) {
    const form = document.getElementById(formId);
    const formData = new FormData(form);
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => callback(data))
    .catch(error => console.error('Error al enviar formulario:', error));
}
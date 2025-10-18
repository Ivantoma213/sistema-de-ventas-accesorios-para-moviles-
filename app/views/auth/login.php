<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/general.css?v=1.0">
    <link rel="stylesheet" href="/css/login.css?v=1.0">
</head>
<body class="login-page">
    <div class="floating-shapes">
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
    </div>

    <div class="login-container">
        <div class="login-wrapper">
            <div class="login-card">
                <div class="login-header">
                    <div class="logo-section">
                        <div class="logo-container">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <h1>Sistema de Inventario</h1>
                    </div>
                    <p class="login-subtitle">Bienvenido de vuelta</p>
                </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="/sistema/?route=auth/login" method="post" class="login-form">
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i>
                        Correo Electrónico
                    </label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" placeholder="tu@email.com" required>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i>
                        Contraseña
                    </label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                        <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" name="remember" id="remember">
                        <span class="checkmark"></span>
                        Recordarme
                    </label>
                    <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="login-btn">
                    <span class="btn-text">Iniciar Sesión</span>
                    <i class="fas fa-arrow-right btn-icon"></i>
                </button>
            </form>

                <div class="login-footer">
                    <p>¿No tienes cuenta? <a href="https://wa.me/51954894611" target="_blank" class="signup-link">Contacta al administrador</a></p>
                </div>
            </div>
        </div>
    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const password = document.getElementById('password');
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }

        // Enhanced focus effects
        document.querySelectorAll('.input-wrapper input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });

        // Form validation with modern feedback
        const loginForm = document.querySelector('.login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value.trim();

                // Remove existing alerts
                const existingAlert = document.querySelector('.alert');
                if (existingAlert) {
                    existingAlert.remove();
                }

                if (!email || !password) {
                    e.preventDefault();

                    // Create error alert
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-error';
                    alertDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Por favor completa todos los campos';

                    const loginHeader = document.querySelector('.login-header');
                    loginHeader.parentNode.insertBefore(alertDiv, loginHeader.nextSibling);

                    // Shake animation
                    alertDiv.style.animation = 'shake 0.5s ease-in-out';
                    return false;
                }

                // Show loading state
                const btn = document.querySelector('.login-btn');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="btn-text">Iniciando sesión...</span><i class="fas fa-spinner fa-spin btn-icon"></i>';
                btn.disabled = true;

                // Re-enable button after 3 seconds (in case of error)
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 3000);
            });
        }

        // Add smooth scrolling and enhanced UX
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading animation to body
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.5s ease';

            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);

            // Enhanced input interactions
            document.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.classList.add('has-content');
                    } else {
                        this.classList.remove('has-content');
                    }
                });
            });
        });
    </script>
</body>
</html>
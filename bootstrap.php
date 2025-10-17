<?php
// Archivo de inicialización de la aplicación

// Cargar configuración
require_once __DIR__ . '/config/database.php';

// Autoloading manual (antes de composer)
spl_autoload_register(function ($class) {
    // Directorios donde buscar clases
    $directories = [
        __DIR__ . '/app/controllers/',
        __DIR__ . '/app/models/',
        __DIR__ . '/app/services/',
        __DIR__ . '/config/',
        __DIR__ . '/'
    ];

    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configurar zona horaria
date_default_timezone_set('America/Lima');

// Configurar manejo de errores
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Función helper para logging
function log_message($message, $level = 'info') {
    $logFile = __DIR__ . '/logs/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] [$level] $message" . PHP_EOL;

    if (!file_exists(dirname($logFile))) {
        mkdir(dirname($logFile), 0755, true);
    }

    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

// Función helper para redirección
function redirect($url) {
    header("Location: $url");
    exit;
}

// Función helper para sanitizar input
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Función helper para validar email
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Función helper para generar token CSRF
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Función helper para verificar token CSRF
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
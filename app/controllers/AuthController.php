<?php
require_once __DIR__ . '/Controller.php';

class AuthController extends Controller {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = $this->model('UsuarioModel');
    }

    public function showLoginForm() {
        if (isset($_SESSION['user_id'])) {
            header('Location: /sistema/?route=dashboard');
            exit;
        }
        $this->view('auth/login');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);

            log_message("Login attempt for email: $email");

            $user = $this->usuarioModel->findUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                log_message("Login success for $email");
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user'] = $user; // Guardar datos completos
                $_SESSION['rol'] = $user['rol']; // Guardar rol directamente
                header('Location: /sistema/?route=dashboard');
                exit;
            } else {
                log_message("Login failed for $email");
                $data['error'] = 'Credenciales inválidas o usuario inactivo';
                $this->view('auth/login', $data);
            }
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /sistema/?route=auth/login');
        exit;
    }
}
?>
<?php

class Controller {
    protected function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistema/?route=auth/login');
            exit;
        }
    }

    protected function checkCsrfToken() {
        $token = $_POST['csrf_token'] ?? '';
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        if (empty($token) || !hash_equals($sessionToken, $token)) {
            error_log("CSRF Token mismatch - Received: '$token', Expected: '$sessionToken'");
            http_response_code(403);
            die('Token CSRF inválido');
        }
    }

    protected function generateCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            error_log("Generated new CSRF token: " . $_SESSION['csrf_token']);
        }
        return $_SESSION['csrf_token'];
    }

    protected function view($view, $data = []) {
        $data['csrf_token'] = $this->generateCsrfToken();
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }

    protected function model($model) {
        require_once __DIR__ . '/../models/' . $model . '.php';
        return new $model();
    }

    protected function service($service) {
        require_once __DIR__ . '/../services/' . $service . '.php';
        return new $service();
    }
}
?>
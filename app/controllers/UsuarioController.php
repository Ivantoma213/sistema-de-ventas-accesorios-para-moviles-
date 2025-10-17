<?php
require_once __DIR__ . '/Controller.php';

class UsuarioController extends Controller {
    private $usuarioModel;

    public function __construct() {
        $this->checkAuth();
        // Restricción: solo admin
        if (!isset($_SESSION['user']['rol']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /sistema/?route=dashboard');
            exit;
        }
        $this->usuarioModel = $this->model('UsuarioModel');
    }

    public function index() {
        $usuarios = $this->usuarioModel->findAll();
        $this->view('usuario/lista_usuarios', ['usuarios' => $usuarios]);
    }

    public function create() {
        $roles = $this->usuarioModel->getRoles();
        $estados = $this->usuarioModel->getEstadosUsuario();
        $this->view('usuario/form_usuario', [
            'roles' => $roles,
            'estados' => $estados
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // $this->checkCsrfToken();

            $data = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'id_rol' => $_POST['id_rol'] ?? '',
                'id_estado' => $_POST['id_estado'] ?? ''
            ];

            $errors = $this->validateData($data, false);

            if (empty($errors)) {
                $this->usuarioModel->create($data);
                header('Location: /sistema/?route=usuario');
                exit;
            } else {
                // Handle errors, perhaps redirect back with errors
                $_SESSION['errors'] = $errors;
                header('Location: /sistema/?route=usuario/create');
                exit;
            }
        }
    }

    public function edit($id) {
        $usuario = $this->usuarioModel->findById($id);
        $roles = $this->usuarioModel->getRoles();
        $estados = $this->usuarioModel->getEstadosUsuario();
        $this->view('usuario/form_usuario', [
            'usuario' => $usuario,
            'roles' => $roles,
            'estados' => $estados
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // $this->checkCsrfToken();
            $data = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'id_rol' => $_POST['id_rol'] ?? '',
                'id_estado' => $_POST['id_estado'] ?? ''
            ];

            $errors = $this->validateData($data, true, $id);

            if (empty($errors)) {
                $this->usuarioModel->update($id, $data);
                header('Location: /sistema/?route=usuario');
                exit;
            } else {
                $_SESSION['errors'] = $errors;
                header('Location: /sistema/?route=usuario/edit/' . $id);
                exit;
            }
        }
    }

    public function delete($id) {
        $this->usuarioModel->delete($id);
        header('Location: /sistema/?route=usuario');
        exit;
    }

    private function validateData($data, $isUpdate = false, $id = null) {
        $errors = [];

        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es requerido.';
        }

        if (empty($data['email'])) {
            $errors[] = 'El email es requerido.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El email no es válido.';
        } else {
            // Check if email is unique
            $existingUser = $this->usuarioModel->findUserByEmail($data['email']);
            if ($existingUser) {
                if (!$isUpdate || $existingUser['id'] != $id) {
                    $errors[] = 'El email ya está en uso.';
                }
            }
        }

        if (!$isUpdate || !empty($data['password'])) {
            if (empty($data['password'])) {
                $errors[] = 'La contraseña es requerida.';
            } elseif (strlen($data['password']) < 6) {
                $errors[] = 'La contraseña debe tener al menos 6 caracteres.';
            }
        }

        if (empty($data['id_rol'])) {
            $errors[] = 'El rol es requerido.';
        }

        if (empty($data['id_estado'])) {
            $errors[] = 'El estado es requerido.';
        }

        return $errors;
    }
}
?>
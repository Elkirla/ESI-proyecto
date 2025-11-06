<?php
class AuthControl {

    public function loginView() {
        require_once __DIR__ . '/../Vistas/login.php';
    }

    public function registroView() {
        require_once __DIR__ . '/../Vistas/registro.php';
    }

    public function Backoffice() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== "administrador") {
            $this->Mostrar404();
            return;
        }
        require_once __DIR__ . '/../Vistas/backoffice.php'; 
    }

    public function Mostrar404() {
        http_response_code(404);
        require_once __DIR__ . '/../Vistas/404.php';
        exit;
    }

    public function registrar() {
        require_once __DIR__ . '/../Entidades/Usuario.php'; 
        require_once __DIR__ . '/../Modelos/UsuarioModelo.php';

        header('Content-Type: application/json');

        $validator = new validator();
        $modelo = new UsuarioModelo();

        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $email    = trim($_POST['email'] ?? '');
        $ci       = trim($_POST['ci'] ?? '');

        $errores = [];

        $passErrors = $validator->Contraseña($password);
        if (!empty($passErrors)) {
            $errores['password'] = $passErrors;
        }

        if (!$validator->Email($email)) {
            $errores['email'][] = "Email inválido";
        }

        if ($password !== $confirm) {
            $errores['confirm'][] = "Las contraseñas no coinciden";
        }

        if ($modelo->ExisteEmail($email)) {
            $errores['email'][] = "El correo ya está registrado";
        }

        if (!empty($ci) && !$validator->CedulaUruguaya($ci)) {
            $errores['ci'][] = "Cédula de identidad inválida";
        }

        if (!empty($ci) && $modelo->ExisteCI($ci)) {
            $errores['ci'][] = "La CI ya está registrada";
        }

        if (!empty($errores)) { 
            echo json_encode(['success' => false, 'errors' => $errores]);
            exit;
        }

        try {
            $usuario = new Usuario();
            $usuario->setRol(1);
            $usuario->setNombre($_POST['nombre'] ?? '');
            $usuario->setApellido($_POST['apellido'] ?? '');
            $usuario->setTelefono($_POST['telefono'] ?? '');
            $usuario->setCi($ci);
            $usuario->setEmail($email);
            $usuario->setPassword(password_hash($password, PASSWORD_BCRYPT));
            $usuario->setEstado("pendiente");
            $usuario->setFechaRegistro(date('Y-m-d H:i:s'));

            $modelo->CrearUsuario($usuario);

            echo json_encode(['success' => true]);
            exit;

        } catch (Exception $e) {
            error_log("Error en registrar: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'errors' => ["general" => "Error interno. Intente más tarde."]
            ]);
            exit;
        }
    }

    public function login() {
        header('Content-Type: application/json');

        try {
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            if (!$email || !$password) {
                echo json_encode(['success' => false, 'error' => 'Faltan datos']);
                return;
            }

            require_once __DIR__ . '/../Modelos/UsuarioModelo.php';
            $modelo = new UsuarioModelo();
            $usuario = $modelo->VerificarLogin($email, $password);

            if ($usuario === 'inactivo') {
                echo json_encode([
                    'success' => false,
                    'error' => 'Usuario no autorizado. Contacte al backoffice.'
                ]);
                return;
            }

            if ($usuario) {
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['rol'] = $usuario['rol']; 

                require_once __DIR__ . '/PagosControl.php';
                $pagos = new PagosControl();

                $tienePago = $pagos->usuarioTienePagoAprobado();

                echo json_encode([
                    'success' => true,
                    'rol' => $usuario['rol'],
                    'tienePago' => $tienePago
                ]);
                return;
            }

            echo json_encode([
                'success' => false,
                'error' => 'Credenciales inválidas'
            ]);

        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Error interno del servidor']);
        }
    }

    public function PagoInicialView() {
        require_once __DIR__ . '/../Vistas/PagoInicial.php';
    }

    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
}

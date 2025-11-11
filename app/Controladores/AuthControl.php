<?php
class AuthControl {

    /** ✅ Verificar si es admin */
    private function EsAdmin(): bool {
        return isset($_SESSION['rol']) && $_SESSION['rol'] === "administrador";
    }

    /** ✅ Mostrar 404 limpio */
    private function Mostrar404() {
        http_response_code(404);
        include __DIR__ . "/../Vistas/404.php";
        exit;
    }

    /** ✅ Helpers para validar acceso */
    private function requireAdmin() {
        if (!$this->EsAdmin()) {
            $this->Mostrar404();
        }
    }

    public function loginView() {
        include __DIR__ . "/../Vistas/login.php";
    }

    public function AdminstrarPagosView() {
        $this->requireAdmin();
        include __DIR__ . "/../Vistas/AdministrarPagos.php";
    }

    public function registroView() {
        include __DIR__ . "/../Vistas/registro.php";
    }

    public function Backoffice() {
        $this->requireAdmin();
        include __DIR__ . "/../Vistas/backoffice.php";
    }

    public function PagoInicialView(){
        include __DIR__ . "/../Vistas/PagoInicial.php";
    }

    /** ✅ Registro con validaciones estructuradas */
    public function registrar() {
        require_once __DIR__ . '/../Entidades/usuario.php';
        require_once __DIR__ . '/../Modelos/UsuarioModelo.php';

        header('Content-Type: application/json');
        $validator = new validator();
        $modelo = new UsuarioModelo();

        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $email    = trim($_POST['email'] ?? '');
        $ci       = trim($_POST['ci'] ?? '');
        $errores  = [];

        $passErrors = $validator->Contraseña($password);
        if (!empty($passErrors)) $errores['password'] = $passErrors;

        if (!$validator->Email($email)) $errores['email'][] = "Email inválido";
        if ($password !== $confirm) $errores['confirm'][] = "Las contraseñas no coinciden";
        if ($modelo->ExisteEmail($email)) $errores['email'][] = "El correo ya está registrado";

        if (!empty($ci)) {
            if (!$validator->CedulaUruguaya($ci)) $errores['ci'][] = "Cédula de identidad inválida";
            if ($modelo->ExisteCI($ci)) $errores['ci'][] = "La CI ya está registrada";
        }

        if (!empty($errores)) {
            echo json_encode(['success' => false, 'errors' => $errores]);
            return;
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

        } catch (Exception $e) {
            error_log("Error en registrar: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'errors' => ["general" => "Error interno. Intente más tarde."]
            ]);
        }
    }

    /** ✅ Login seguro y claro */
    public function login() {
        header('Content-Type: application/json');

        try {
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            if (!$email || !$password) {
                echo json_encode(['success' => false, 'error' => 'Faltan datos']);
                return;
            }

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

                require_once __DIR__ . '/../Controladores/PagosControl.php';
                $pagos = new PagosControl();
                $tienePago = $pagos->usuarioTienePagoAprobado();

                echo json_encode([
                    'success' => true,
                    'rol' => $usuario['rol'],
                    'tienePago' => $tienePago
                ]);
                return;
            }

            echo json_encode(['success' => false, 'error' => 'Credenciales inválidas']);

        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Error interno del servidor']);
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
}

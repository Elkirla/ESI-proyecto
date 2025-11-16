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
 
public function registrar() {
    require_once __DIR__ . '/../Entidades/usuario.php';
    require_once __DIR__ . '/../Modelos/UsuarioModelo.php';

    header('Content-Type: application/json');

    $esAdmin = ($_SESSION['rol'] ?? '') === 'administrador';

    $validator = new validator();
    $modelo = new UsuarioModelo();

    // === Campos ===
    $nombre   = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $ci       = trim($_POST['ci'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $esAdmin ? $password : ($_POST['confirm_password'] ?? '');

    $errores = [];

    // ----------------
    // VALIDACIONES
    // ----------------
    $nombreValido   = preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre);
    $apellidoValido = preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $apellido);

    if (!$nombreValido && !$apellidoValido) {
        $errores['nombre_apellido'][] = "Nombre y apellido inválido";
    } elseif (!$nombreValido) {
        $errores['nombre'][] = "Nombre inválido";
    } elseif (!$apellidoValido) {
        $errores['apellido'][] = "Apellido inválido";
    }

    // Email
    if (!$validator->Email($email)) {
        $errores['email'][] = "Email inválido";
    } elseif ($modelo->ExisteEmail($email)) {
        $errores['email'][] = "El correo ya está registrado";
    }

    // Teléfono (+)
    if (!preg_match('/^\+\d{6,15}$/', $telefono)) {
        $errores['telefono'][] = "Número de teléfono inválido. Debe incluir el código del país.";
    }

    // CI
    if (!empty($ci) && $modelo->ExisteCI($ci)) {
        $errores['ci'][] = "La CI ya está registrada";
    }
    if (!$validator->CedulaUruguaya($ci)) {
        $errores['ci'][] = "La CI no es valida";
    }

    // Contraseña
    if ($password !== $confirm) {
        $errores['confirm'][] = "Las contraseñas no coinciden";
    } elseif (!$esAdmin) {
        $passErrors = $validator->Contraseña($password);
        if (!empty($passErrors)) {
            $errores['password'] = $passErrors;
        }
    }

    if (!empty($errores)) {
        echo json_encode(['success' => false, 'errors' => $errores]);
        return;
    }

    // ----------------
    // CREAR USUARIO
    // ----------------
    try {
        $usuario = new Usuario();
        $usuario->setRol(1);
        $usuario->setNombre($nombre);
        $usuario->setApellido($apellido);
        $usuario->setTelefono($telefono);
        $usuario->setCi($ci);
        $usuario->setEmail($email);
        $usuario->setPassword(password_hash($password, PASSWORD_BCRYPT));

        // Si es admin → activo; si es usuario normal → pendiente
        $usuario->setEstado($esAdmin ? "activo" : "pendiente");
        $usuario->setFechaRegistro(date('Y-m-d H:i:s'));

        // Crear usuario
        $usuario_id = $modelo->CrearUsuario($usuario);

        // Si es creado por admin → asignar unidad automáticamente
        if ($esAdmin) {
            require_once __DIR__ . '/../Controladores/UnidadControl.php';
            error_log("SE EMPIEZA A ASIGNAR UNIDAD");
            $unidadControl = new UnidadControl();
            $unidadControl->AsignarUnidadAUsuario($usuario_id); 
        }

        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        logerror_("Error en registrar: " . $e->getMessage());
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

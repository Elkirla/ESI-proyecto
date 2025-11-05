<?php
class AuthControl {
    public function loginView() {
        include __DIR__ . "/../Vistas/login.php";
    }

    public function registroView() {
        include __DIR__ . "/../Vistas/registro.php";
    }
    
public function registrar() {
    require_once __DIR__ . '/../Entidades/usuario.php'; 
    require_once __DIR__ . '/../Modelos/UsuarioModelo.php';

    header('Content-Type: application/json');

    $validator = new validator();
    $modelo = new UsuarioModelo();

    // Datos de entrada
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $email    = trim($_POST['email'] ?? '');
    $ci       = trim($_POST['ci'] ?? '');

    $errores = [];

    //Validación de contraseña
    $passErrors = $validator->Contraseña($password);
    if (!empty($passErrors)) {
        $errores['password'] = $passErrors;
    }

    //Validación email
    if (!$validator->Email($email)) {
        $errores['email'][] = "Email inválido";
    }

    //Confirmar password
    if ($password !== $confirm) {
        $errores['confirm'][] = "Las contraseñas no coinciden";
    }

    //Verificar existencia de email y CI
    if ($modelo->ExisteEmail($email)) {
        $errores['email'][] = "El correo ya está registrado";
    }

    // Verificar CI válida (solo si fue ingresada)
    if (!empty($ci) && !$validator->CedulaUruguaya($ci)) {
        $errores['ci'][] = "Cédula de identidad inválida";
    }
    
    if (!empty($ci) && $modelo->ExisteCI($ci)) {
        $errores['ci'][] = "La CI ya está registrada";
    }

    //Si hay errores → se devuelven
    if (!empty($errores)) { 
        echo json_encode(['success' => false, 'errors' => $errores]);
        exit;
    }

    //Crear usuario si todo está ok
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

            // Guardar sesión primero para poder consultar pagos
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $usuario['id'];

            require_once __DIR__ . '/../Controladores/PagosControl.php';
            $pagos = new PagosControl;

            $tienePago = $pagos->usuarioTienePagoAprobado();

            echo json_encode([
                'success' => true,
                'rol' => $usuario['rol'],
                'tienePago' => $tienePago
            ]);
            return;
        }

        // Credenciales inválidas
        echo json_encode([
            'success' => false,
            'error' => 'Credenciales inválidas'
        ]);

    } catch (Exception $e) {
        error_log("Error en login: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Error interno del servidor']);
    }
}

    public function PagoInicialView(){
        include __DIR__ . "/../Vistas/PagoInicial.php";
    }


    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
}
<?php
class AuthControl {
    public function loginView() {
        include __DIR__ . "/../Vistas/login.php";
    }

    public function registroView() {
        include __DIR__ . "/../Vistas/registro.php";
    }
    
public function registrar(){
    header('Content-Type: application/json');

    $validator = new validator();

    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $email    = $_POST['email'] ?? ''; 

    $errores = [];

    // Validación de contraseña
    $passErrors = $validator->Contraseña($password);
    if(!empty($passErrors)) {
        $errores['password'] = $passErrors;
    }

    // Validación de email
    if(!$validator->Email($email)) {
        $errores['email'][] = "Email inválido";
    }

    // Validar confirmación
    if($password !== $confirm) {
        $errores['confirm'][] = "Las contraseñas no coinciden";
    }

    if(!empty($errores)) { 
        echo json_encode(['success' => false, 'errors' => $errores]);
        exit;
    }

    //Si todo va bien creamos la entidad usuario
 try{
    $fecha_registro = date('Y-m-d H:i:s');
    $usuario = new usuario( 
        1,
        $_POST['nombre'] ?? '',
        $_POST['apellido'] ?? '',
        $_POST["telefono"] ?? "",
        $_POST["ci"] ?? "",
        $email,
        password_hash($password, PASSWORD_BCRYPT),
        "pendiente",
        $fecha_registro
    );

    $modelo = new UsuarioModelo();
    $modelo->CrearUsuario($usuario); 
    echo json_encode(['success' => true]);
    exit; 
}catch(Exception $e) {
    error_log("Error en registrar: " . $e->getMessage());
    $errores['confirm'][] = "Error interno del servidor. Por favor, intente nuevamente más tarde.";
    echo json_encode(['success' => false, 'errors' => $errores]);
    }
}
    public function login() {
        // Iniciar sesión al principio
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Establecer header JSON
        header('Content-Type: application/json');

        try {
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            if (!$email || !$password) {
                echo json_encode(['success' => false, 'error' => 'Faltan datos']);
                exit;
            }

            $modelo = new UsuarioModelo();
            $usuario = $modelo->VerificarLogin($email, $password);

            if ($usuario === 'inactivo') {
                echo json_encode([
                    'success' => false,
                    'error' => 'Usuario no autorizado. Contacte al backoffice.'
                ]);
            } elseif ($usuario) {
                // Regenerar sesión
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['rol'] = $usuario['rol'];

                echo json_encode([
                    'success' => true,
                    'rol' => $usuario['rol']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Credenciales inválidas'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Error interno del servidor']);
        }
    }
}
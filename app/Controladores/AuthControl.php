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
 
    $fecha_registro = date('Y-m-d H:i:s');
    $usuario = new usuario( 
        "usuario",
        $_POST['nombre'] ?? '',
        $_POST['apellido'] ?? '',
        $email,
        password_hash($password, PASSWORD_BCRYPT),
        "pendiente",
        $fecha_registro
    );

    $modelo = new UsuarioModelo();
    $modelo->CrearUsuario($usuario); 
    echo json_encode(['success' => true]);
    exit; 
}
}

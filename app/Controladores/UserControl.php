<?php
class UserControl {
    public function __construct() {
        header('Content-Type: application/json; charset=utf-8');
        require_once __DIR__ . '/../Controladores/ListadoControl.php';
        require_once __DIR__ . '/../Modelos/UsuarioModelo.php';
        require_once __DIR__ . '/../Entidades/usuario.php';
    }

public function cargarDatosUsuario() {
    $listado = new ListadoControl();
    $idusuario = $_SESSION["usuario_id"] ?? null;
    if (!$idusuario) {
        echo json_encode(["error" => "Usuario no autenticado"]);
        return;
    }
    $listado->listadoComun(
    "usuarios",
    ["*"],
    ["id" => $idusuario], 
    null,
    1
);}

public function ActualizarDatosUsuario() { 
    $idUsuario = $_SESSION["usuario_id"] ?? null; 
    $this->ModificarDatos($idUsuario, true);
}

public function ModificarDatos($idUsuario, $personal) {
    header('Content-Type: application/json');

    require_once __DIR__ . '/../Config/validator.php';
    require_once __DIR__ . '/../Modelos/UsuarioModelo.php';
    require_once __DIR__ . '/../Entidades/usuario.php';

    $usuario = new Usuario();
    $usuario->setId($idUsuario);
    $usuario->setNombre($_POST["nombre"] ?? "");
    $usuario->setApellido($_POST["apellido"] ?? "");
    $usuario->setTelefono($_POST["telefono"] ?? ""); 
    $usuario->setCi($_POST["ci"] ?? "");
 
    $validator = new Validator();
    $validator->validarUsuarioCambios($usuario, $personal);
    $errores = $validator->getErrores();
 
    if (!empty($errores)) { 
        echo json_encode([
            "success" => false,    
            "errores" => $errores  
        ]);
        return;
    }
    

    // Actualizar datos
    $modeloUsuario = new UsuarioModelo();
    $resultado = $modeloUsuario->actualizarDatos($usuario);

    if ($resultado) {
        echo json_encode([
            "success" => "Datos actualizados correctamente"
        ]);
    } else {
        echo json_encode([
            "error" => "Error al actualizar los datos"
        ]);
    }
}



}
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

public function ActualizarDatosUsuario(){
$usuario = new usuario();
$usuario->setId($_SESSION["usuario_id"] ?? null);
$usuario->setNombre($_POST["nombre"] ?? null);
$usuario->setApellido($_POST["apellido"] ?? null);
$usuario->setTelefono($_POST["telefono"] ?? null);
$usuario->setCi($_POST["ci"] ?? null);
$modeloUsuario = new UsuarioModelo();
$resultado = $modeloUsuario->actualizarDatos($usuario);
if ($resultado) {
    echo json_encode(["success" => "Datos actualizados correctamente"]);  
} else {
    echo json_encode(["error" => "Error al actualizar los datos"]);
}
}
}
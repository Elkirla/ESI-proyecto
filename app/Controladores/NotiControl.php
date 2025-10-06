<?php
class NotiControl{

    public function __construct() {
    header('Content-Type: application/json; charset=utf-8');
    require_once __DIR__ . '/../Controladores/ListadoControl.php';
    require_once __DIR__ . '/../Modelos/NotiModelo.php';
    session_start();
    }

    public function ObtenerNotificaciones(){
    $listado = new ListadoControl();
    $idusuario = $_SESSION["usuario_id"] ?? null;
    if (!$idusuario) {
        echo json_encode([
            "success" => false,
            "error" => "Usuario no autenticado"]);
        return;
    }
    $listado->listadoComun(
        "notificaciones",
        ["id", "mensaje", "leido", "fecha"],
        ["usuario_id" => $idusuario],
        "fecha DESC"
    );
}

    public function NotisNoLeidas(){
    $listado = new ListadoControl();
    $idusuario = $_SESSION["usuario_id"] ?? null;

    $listado->listadoComun(
        "notificaciones",
        ["COUNT(*) AS no_leidas"],
        ["usuario_id" => $idusuario, "leido" => 0]
    );
}

public function MarcarTodasLeidas() {
    $modelo = new NotiModelo();
    $usuario_id = $_SESSION["usuario_id"] ?? null;

    if (!$usuario_id) {
        echo json_encode([
            "success" => false,
            "error" => "Usuario no autenticado"]);
        return;
    }

    if ($modelo->NotiLeidasUsuario($usuario_id)) {
        echo json_encode([
            "success" => true]);
    } else {
        echo json_encode([
            "success" => false, 
            "error" => "No se pudo actualizar"]);
    }
    exit;
}

}
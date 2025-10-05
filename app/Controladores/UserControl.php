<?php
class UserControl {
    public function __construct() {
        header('Content-Type: application/json; charset=utf-8');
        require_once __DIR__ . '/../Controladores/ListadoControl.php';
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
    ["nombre", "apellido", "telefono", "email", "ci"],
    ["id" => $idusuario], 
    null,
    1
);
}

public function cargarUsuariosPendientes() {
    $listado = new ListadoControl();
    $listado->listadoAdmin(
        "usuarios",
        ["id", "nombre", "apellido"],
        ["estado" => "pendiente"]
    );
}

public function ObtenerUsuarioPorId() {
         $listado = new ListadoControl();
        $listado->listadoAdmin(
            "usuarios",
            ["nombre", "apellido", "telefono", "email", "ci"],
            ["id" => $id],
            null,
            1
        );
}
}

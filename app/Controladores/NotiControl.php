<?php
class NotiControl {
    private $listado;
    private $idusuario;

    public function __construct() {
        header('Content-Type: application/json; charset=utf-8');
        require_once __DIR__ . '/../Controladores/ListadoControl.php';
        require_once __DIR__ . '/../Modelos/NotiModelo.php';
        $this->listado = new ListadoControl();
        $this->idusuario = $_SESSION["usuario_id"] ?? null;
    }

    public function ObtenerNotificaciones() {
        $this->listado->listadoComun(
            "Notificaciones",
            ["id", "mensaje", "leido", "fecha"],
            ["usuario_id" => $this->idusuario],
        );
    }

    public function NotisNoLeidas() {
        $this->listado->listadoComun(
            "Notificaciones",
            ["COUNT(*) AS no_leidas"],
            ["usuario_id" => $this->idusuario, "leido" => 0]
        );
    }

    public function MarcarTodasLeidas() {
        $modelo = new NotiModelo();

        if (!$this->idusuario) {
            echo json_encode(["success" => false, "error" => "Usuario no autenticado"]);
            return;
        }

        if ($modelo->NotiLeidasUsuario($this->idusuario)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "No se pudo actualizar"]);
        }
    }
}

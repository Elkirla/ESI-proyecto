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

public function NotisNoLeidas() {
    try {
        // Capturar la salida del método listadoComun (que imprime JSON)
        ob_start();
        $this->listado->listadoComun(
            "Notificaciones",
            ["id", "mensaje", "leido", "fecha"],
            ["usuario_id" => $this->idusuario, "leido" => 0]
        );
        $output = ob_get_clean();

        // Convertir a array
        $notificaciones = json_decode($output, true);
        if (!is_array($notificaciones)) {
            $notificaciones = [];
        }

        $cantidad = count($notificaciones);
 
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(["no_leidas" => $cantidad]);
 
        $this->MarcarTodasLeidas();

    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(["error" => "No encontrado"]);
        error_log($e->getMessage());
    }
}

    public function MarcarTodasLeidas() {
        $modelo = new NotiModelo(); 
        if ($modelo->NotiLeidasUsuario($this->idusuario)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "No se pudo actualizar"]);
        }
    }

public function CrearNoti($mensaje, $usuario) {

    // Intentar crear la notificación
    $modelo = new NotiModelo();
    $resultado = $modelo->InsertarNoti($usuario, $mensaje);

    // Si se insertó correctamente, devolver un JSON con éxito
    if ($resultado) {
        header('Content-Type: application/json');
        echo json_encode([
            "success" => true,
            "usuario" => $usuario,
            "mensaje" => $mensaje,
            "info"    => "Notificación creada correctamente"
        ]);
        return true;
    }

    // Si falló, devolver JSON con error
    header('Content-Type: application/json');
    echo json_encode([
        "success" => false,
        "info"    => "Error al crear la notificación"
    ]);

    return false;
}
public function ObtenerNotificaciones(){
    $this->listado->listadoComun(
        "Notificaciones",
        ["id", "mensaje", "leido", "fecha"],
        ["usuario_id" => $this->idusuario]
    );

}
}

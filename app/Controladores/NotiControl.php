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
    // Iniciar el buffer de salida
    ob_start();

    // Ejecutar la consulta
    $this->listado->listadoComun(
        "Notificaciones",
        ["id", "mensaje", "leido", "fecha"],
        ["usuario_id" => $this->idusuario, "leido" => 0]
    );

    // Obtener el output generado por listadoComun
    $output = ob_get_clean();

    // Convertir output a array asociativo
    $notificaciones = json_decode($output, true);

    // Si no es un JSON válido, asumimos 0
    if (!is_array($notificaciones)) {
        $notificaciones = [];
    }

    // Contar no leídas
    $cantidad = count($notificaciones);

    // Devolver respuesta JSON
    header('Content-Type: application/json');
    echo json_encode(["no_leidas" => $cantidad]);
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

public function CrearNoti($mensaje, $usuario) {
    // Validaciones básicas
    if (empty($usuario) || empty($mensaje)) {
        return false;
    }

    // Intentar crear la notificación
    $resultado = $this->InsertarNoti($usuario, $mensaje);

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

<?php
class HorasControl {
    public function IngresarHoras() {
        header('Content-Type: application/json');

    require_once __DIR__ . '/../Entidades/hora.php'; 
        try {
            session_start();
            $modelo = new HorasModelo();
            $usuario_id = $_SESSION['usuario_id'] ?? null;

            $fecha = $_POST['fecha'] ?? null;
            $horas = $_POST['horas'] ?? null;

            if (!$usuario_id || !$fecha || !$horas) {
                echo json_encode(['success' => false, 'error' => 'Faltan datos']);
                exit;
            }elseif ($modelo->tieneHorasRegistradas($usuario_id, $fecha)) {
                echo json_encode(['success' => false, 'error' => 'Ya has registrado horas hoy']);
                exit;
            }

            $hora = new Hora($usuario_id, $fecha, $horas);

            $ok = $modelo->registrarHoras($hora);

            if ($ok) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'No se pudo registrar en la BD']);
            }
            exit;

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

public function verHorasUsuario() {
    require_once __DIR__ . '/../Modelos/ReporteModelo.php';
    session_start();
    $modelo = new ReporteModelo();
    $usuario_id = $_SESSION['usuario_id'] ?? null;

    header('Content-Type: application/json');

    try {
        if (!$usuario_id) {
            echo json_encode(["error" => "Usuario no autenticado"]);
            return;
        }

        $arreglo = $modelo->listadoUniversalSimple(
       "horas_trabajadas",
       ["fecha", "horas"],
       ["usuario_id" => $usuario_id],
       ["fecha", "DESC"]
);

        echo json_encode($arreglo);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
public function verHorasAdmin() {
    require_once __DIR__ . '/../Modelos/ReporteModelo.php';
    session_start();
    $modelo = new ReporteModelo();
    $usuario_id = $_SESSION['usuario_id'] ?? null;

    header('Content-Type: application/json');
    if ($_SESSION['rol'] == 'administrador'){
    try {
        $arreglo = $modelo->listadoUniversalSimple(
       "horas_trabajadas",
       ["usuario_id", "fecha", "horas"],
       [],
       ["fecha", "DESC"]
);

        echo json_encode($arreglo);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}else {
        http_response_code(404);
        include __DIR__ . '/../Vistas/404.php'; 
    }
}

}

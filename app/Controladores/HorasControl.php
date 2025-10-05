<?php
class HorasControl {
    public function __construct() {
        require_once __DIR__ . '/../Modelos/HorasModelo.php';
        require_once __DIR__ . '/../Controladores/ListadoControl.php';
        header('Content-Type: application/json; charset=utf-8');
    }
    public function IngresarHoras() {
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

    public function IngresarJustificativo(){
    require_once __DIR__ . '/../Entidades/justificativo.php'; 
    require_once __DIR__ . '/../Config/uploads.php';
    try {
        session_start();
        $modelo = new HorasModelo();

        $usuario_id = $_SESSION['usuario_id'] ?? null;
        $fecha = $_POST['fecha'] ?? null;
        $motivo = $_POST['motivo'] ?? null;

        if (!$usuario_id || !$fecha || !$motivo) {
            echo json_encode(['success' => false, 'error' => 'Faltan datos']);
            return;
        }

        // Subir archivo usando la clase Uploads
        $uploader = new Uploads('/var/www/html/public/uploads/'); 
        $archivo_url = $uploader->subirArchivo('archivo'); // <- "archivo" es el name del input

        $justificativo = new justificativo($usuario_id, $fecha, $motivo, $archivo_url);

        $ok = $modelo->registrarJustificativo($justificativo);

        if ($ok) {
            echo json_encode(['success' => true]);
        } else {
            // Eliminar archivo subido si falla la BD
            if ($archivo_url) {
                unlink($_SERVER['DOCUMENT_ROOT'] . $archivo_url);
            }
            echo json_encode(['success' => false, 'error' => 'No se pudo registrar en la BD']);
        }
        exit;

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}
public function listarJustificativos() {
    $listado = new ListadoControl();
    $usuario_id = $_SESSION['usuario_id'] ?? null;

    $listado->listadoComun(
        "justificativos",
        ["fecha", "motivo", "archivo_url", "estado"],
        ["usuario_id" => $usuario_id],                    
        ["fecha", "DESC"]
    );
}

public function listarJustificativosAdmin() {
    $listado = new ListadoControl();
    $listado->listadoAdmin(
        "justificativos",
        ["usuario_id", "fecha", "motivo", "archivo_url", "estado"],
        [],                    
        ["fecha", "DESC"]
    );
}

public function verHorasUsuario() {
    $listado = new ListadoControl();
    $listado->listadoComun(
        "horas_trabajadas",
        ["fecha", "horas"],
        [],                    
        ["fecha", "DESC"]
    );
}

public function verHorasAdmin() {
    $listado = new ListadoControl();
    $listado->listadoAdmin(
        "horas_trabajadas",
        ["usuario_id", "fecha", "horas"],
        [],
        ["fecha", "DESC"]
    );
}
}

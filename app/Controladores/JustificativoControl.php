<?php
class JustificativoControl{

public function __construct() {
    header('Content-Type: application/json; charset=utf-8');
    require_once __DIR__ . '/../Modelos/JustificativoModelo.php';
    require_once __DIR__ . '/../Controladores/ListadoControl.php';
    require_once __DIR__ . '/../Config/uploads.php';
    require_once __DIR__ . '/../Entidades/justificativo.php';
}

    //Ingresar Justificativo

    public function IngresarJustificativo(){
    try {
        $modelo = new JustificativoModelo();

        $usuario_id = $_SESSION['usuario_id'] ?? null;
        $fecha = $_POST['fecha'] ?? null;
        $fecha_final = $_POST['fecha_final'] ?? null;
        $motivo = $_POST['motivo'] ?? null;

        if (!$usuario_id || !$fecha || !$motivo) {
            echo json_encode(['success' => false, 'error' => 'Faltan datos']);
            return;
        }

        $uploader = new Uploads('/var/www/html/public/uploads/'); 
        $archivo_url = $uploader->subirArchivo('archivo'); // <- "archivo" es el name del input
 
        if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'error' => 'Archivo no válido']);
        return;
        }
    
        $justificativo = new justificativo($usuario_id, $fecha, $fecha_final, $motivo, $archivo_url);

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
}
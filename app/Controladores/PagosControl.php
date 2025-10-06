<?php
class PagosControl {

    public function __construct() {
        require_once __DIR__ . '/../Entidades/pago.php'; 
        require_once __DIR__ . '/../Modelos/PagoModelo.php';
        require_once __DIR__ . '/../Controladores/ListadoControl.php';
        header('Content-Type: application/json; charset=utf-8');
    }

public function IngresarPago() {
    require_once __DIR__ . '/../Config/uploads.php';
    session_start();

    try {
        $modelo = new PagoModelo();

        // Validar sesión
        $usuario_id = $_SESSION['usuario_id'] ?? null;
        if (!$usuario_id) {
            throw new Exception("Su sesión ha expirado. Por favor, inicie sesión nuevamente.");
        }

        // Validar mes
        $mes = $_POST['mes'] ?? null;
        if (!$mes) {
            throw new Exception("El campo 'mes' es obligatorio");
        }

        // Validar monto
        $monto = $_POST['monto'] ?? null;
        if (!$monto || !is_numeric($monto) || $monto <= 0) {
            throw new Exception("El campo 'monto' es obligatorio y debe ser mayor a 0.");
        }

        // Subir archivo usando la clase Uploads
        $uploader = new Uploads('/var/www/html/public/uploads/'); 
        $archivo_url = $uploader->subirArchivo('archivo'); // <- "archivo" es el name del input

        // Determinar estado según fecha límite
        $fecha = date('Y-m-d');
        $diaActual = intval(date('d'));
        $diaLimite = $modelo->getFechaLimitePago();
        $estado = 'pendiente';
        $entrega = ($diaActual <= $diaLimite) ? 'en_hora' : 'atrasado';

        // Registrar en BD
        $pago = new Pago($usuario_id, $mes, $monto, $fecha, $archivo_url, $estado, $entrega);
        $ok = $modelo->registrarPago($pago);

        if (!$ok) {
            // Eliminar archivo subido si falla la BD
            unlink($_SERVER['DOCUMENT_ROOT'] . $archivo_url);
            throw new Exception("Error al registrar el pago. Intente más tarde.");
        }

        // Éxito
        echo json_encode([
            'success' => true,
            'message' => 'Pago registrado exitosamente. Estará pendiente de verificación.'
        ]);

    } catch (Exception $e) {
        error_log("[PAGOS_ERROR] User: " . ($_SESSION['usuario_id'] ?? 'unknown') . " - " . $e->getMessage());
        
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error'   => $e->getMessage()
        ]);
    }
    exit;
}


    private function getUploadErrorMessage($errorCode) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'El archivo es demasiado grande.',
            UPLOAD_ERR_FORM_SIZE => 'El archivo excede el tamaño permitido.',
            UPLOAD_ERR_PARTIAL => 'El archivo no se subió completamente.',
            UPLOAD_ERR_NO_FILE => 'No se seleccionó ningún archivo.',
            UPLOAD_ERR_NO_TMP_DIR => 'Error temporal del servidor.',
            UPLOAD_ERR_CANT_WRITE => 'Error al guardar el archivo.',
            UPLOAD_ERR_EXTENSION => 'Extensión de archivo no permitida.'
        ];
        
        return $errors[$errorCode] ?? 'Error desconocido al subir archivo.';
    }

    public function obtenerFechaLimite(){
    try{
        $modelo = new PagoModelo();
        $fechaLimite = $modelo->getFechaLimitePago(); 
        if($fechaLimite){
            echo json_encode([
                'success' => true,
                'fecha_limite' => $fechaLimite
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error'   => 'No se encontró la fecha límite.'
            ]);
        }
    }catch (Exception $e){
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error'   => 'Error interno del servidor. Intente más tarde.'
        ]);
    }}

public function verPagosUsuario() {
    $listado = new ListadoControl();
    $usuario_id = $_SESSION['usuario_id'] ?? null; 

    $listado->listadoComun(
        "pagos_mensuales",
        ["mes", "monto", "fecha", "estado", "entrega"],
        ["usuario_id" => $usuario_id],   
        ["fecha", "DESC"]
    );
}

public function verPagosAdmin() {
    $listado = new ListadoControl();
    $listado->listadoAdmin(
        "pagos_mensuales",
        ["id", "usuario_id", "mes", "monto", "fecha", "archivo_url", "estado", "entrega"],
        [],
        ["fecha", "DESC"]
    );
}

public function listarPagosDeudas() {
    $listado = new ListadoControl();
    $listado->listadoAdmin(
        "Pagos_Deudas",
        ["usuario_id", "correo", "mes", "monto"],
        [],
        ["fecha", "DESC"]
    );
}
}
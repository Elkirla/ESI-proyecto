<?php
class PagosControl {

    public function __construct() {
        require_once __DIR__ . '/../Entidades/pago.php'; 
        require_once __DIR__ . '/../Modelos/PagoModelo.php';
    }

    public function IngresarPago() {
        session_start();
        header('Content-Type: application/json');

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

            // Validar archivo
            if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] === UPLOAD_ERR_NO_FILE) {
                throw new Exception("Por favor, seleccione un archivo para adjuntar.");
            }

            if ($_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
                $errorMessage = $this->getUploadErrorMessage($_FILES['archivo']['error']);
                throw new Exception("Error al subir archivo: " . $errorMessage);
            }

            // Validar tipo de archivo (opcional pero recomendado)
            $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            $fileType = mime_content_type($_FILES['archivo']['tmp_name']);
            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("Tipo de archivo no permitido. Use JPEG, PNG o PDF.");
            }

            // Validar tamaño (ejemplo: máximo 5MB)
            $maxSize = 5 * 1024 * 1024;
            if ($_FILES['archivo']['size'] > $maxSize) {
                throw new Exception("El archivo es demasiado grande. Tamaño máximo: 5MB.");
            }

            $fecha = date('Y-m-d');
            $diaActual = intval(date('d'));
            $diaLimite = $modelo->getFechaLimitePago();
            $estado = 'pendiente';
            $uploadsDir = '/var/www/html/public/uploads/';

            // Asegurar directorio
            if (!is_dir($uploadsDir) && !mkdir($uploadsDir, 0755, true)) {
                throw new Exception("Error interno del servidor. Intente más tarde.");
            }

            if (!is_writable($uploadsDir)) {
                throw new Exception("Error interno del servidor. Intente más tarde.");
            }

            // Generar nombre seguro
            $nombreArchivo = uniqid() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['archivo']['name']));
            $destino = $uploadsDir . $nombreArchivo;

            $entrega = ($diaActual <= $diaLimite) ? 'en_hora' : 'atrasado';

            if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $destino)) {
                throw new Exception("Error al guardar el archivo. Intente nuevamente.");
            }

            $archivo_url = "/uploads/" . $nombreArchivo;

            // Registrar en BD
            $pago = new pago($usuario_id, $mes, $monto, $fecha, $archivo_url, $estado, $entrega);

            $ok = $modelo->registrarPago($pago);

            if (!$ok) {
                // Eliminar archivo subido si falla la BD
                unlink($destino);
                throw new Exception("Error al registrar el pago. Intente más tarde.");
            }

            // Éxito
            echo json_encode([
                'success' => true,
                'message' => 'Pago registrado exitosamente. Estará pendiente de verificación.'
            ]);

        } catch (Exception $e) {
            // Log interno para administradores (sin detalles al usuario)
            error_log("[PAGOS_ERROR] User: " . ($_SESSION['usuario_id'] ?? 'unknown') . " - " . $e->getMessage());
            
            // Mensaje amigable al usuario
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error'   => $e->getMessage() // Mensajes específicos pero seguros
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
    header ('Content-Type: application/json');
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

    public function aprobarPago(){
        session_start();
        header('Content-Type: application/json');

        try {
            // Verificar rol de administrador
            if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
                throw new Exception("Acceso denegado. Solo administradores pueden realizar esta acción.");
            }

            $pago_id = $_POST['pago_id'] ?? null;
            if (!$pago_id || !is_numeric($pago_id)) {
                throw new Exception("ID de pago inválido.");
            }

            $modelo = new PagoModelo();
            $ok = $modelo->aprobarPago($pago_id);

            if (!$ok) {
                throw new Exception("Error al aprobar el pago. Intente más tarde.");
            }

            echo json_encode([
                'success' => true,
                'message' => 'Pago aprobado exitosamente.'
            ]);

        } catch (Exception $e) {
            error_log("[PAGOS_APROBAR_ERROR] Admin: " . ($_SESSION['usuario_id'] ?? 'unknown') . " - " . $e->getMessage());
            
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error'   => $e->getMessage()
            ]);
        }
        exit;
    }
    public function rechazarPago(){
    session_start();
    header('Content-Type: application/json');
    try {
        
        $pago_id = $_POST['pago_id'] ?? null;

        // Verificar rol de administrador
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            throw new Exception("Acceso denegado. Solo administradores pueden realizar esta acción.");
        }

        if (!$pago_id || !is_numeric($pago_id)) {
            throw new Exception("ID de pago inválido.");
        }

        $modelo = new PagoModelo();
        $modelo -> rechazarPago($pago_id);

        echo json_encode([
            'success' => true,
            'message' => 'Pago rechazado con exito.'
        ]);

    } catch (Exception $e) {
    error_log("[PAGOS_APROBAR_ERROR] Admin: " . ($_SESSION['usuario_id'] ?? 'unknown') . 
          " - Pago: " . ($pago_id ?? 'none') . " - " . $e->getMessage());

        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error'   => $e->getMessage()
        ]);
    }}
}
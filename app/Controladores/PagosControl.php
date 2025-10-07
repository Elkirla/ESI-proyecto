<?php
class PagosControl {
    private $listado;

    public function __construct() {
        require_once __DIR__ . '/../Entidades/pago.php'; 
        require_once __DIR__ . '/../Modelos/PagoModelo.php';
        require_once __DIR__ . '/../Controladores/ListadoControl.php';
        require_once __DIR__ . '/../Config/uploads.php';
        header('Content-Type: application/json; charset=utf-8');

        $this->listado = new ListadoControl();
    }

    /* ============================================================
       REGISTRAR NUEVO PAGO
    ============================================================ */
    public function IngresarPago() {
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
                throw new Exception("El campo 'mes' es obligatorio.");
            }

            // Validar monto
            $monto = $_POST['monto'] ?? null;
            if (!$monto || !is_numeric($monto) || $monto <= 0) {
                throw new Exception("El campo 'monto' es obligatorio y debe ser mayor a 0.");
            }

            // Subir archivo
            $uploader = new Uploads('/var/www/html/public/uploads/'); 
            $archivo_url = $uploader->subirArchivo('archivo');

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
                unlink($_SERVER['DOCUMENT_ROOT'] . $archivo_url);
                throw new Exception("Error al registrar el pago. Intente más tarde.");
            }

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

  public function IngresarPagoCompensatorio() {
    try {
        $usuario_id = $_SESSION['usuario_id'] ?? null;
        $monto = $_POST['monto'] ?? null;
        if (!$monto || !is_numeric($monto) || $monto <= 0) 
            throw new Exception("El campo 'monto' es obligatorio y debe ser mayor a 0.");

        $uploader = new Uploads('/var/www/html/public/uploads/'); 
        $archivo_url = $uploader->subirArchivo('archivo');
        if (!$archivo_url) throw new Exception("Error al subir el archivo.");

        $fecha = date('Y-m-d');
        $estado = 'pendiente';

        $pago = new Pago($usuario_id, null, $monto, $fecha, $archivo_url, $estado, null);

        $modelo = new PagoModelo();
        $ok = $modelo->registrarPagoCompensatorio($pago);

        if (!$ok) {
            if(file_exists($_SERVER['DOCUMENT_ROOT'] . $archivo_url)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . $archivo_url);
            }
            throw new Exception("Error al registrar el pago. Intente más tarde.");
        }

        echo json_encode(['success' => true, 'message' => 'Pago compensatorio registrado exitosamente.']);
    } catch (Exception $e) {
        error_log("[PAGOS_ERROR] User: " . ($_SESSION['usuario_id'] ?? 'unknown') . " - " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}
    /* ============================================================
       OBTENER FECHA LÍMITE DE PAGO
    ============================================================ */

    public function obtenerFechaLimite() {
        $this->listado->listadoComun(
        "configuracion",
        ["valor"],
        ["clave" => "fecha_limite_pago"]
      ); 
    }

    /* ============================================================
       OBTENER PAGOS DEL USUARIO LOGUEADO
    ============================================================ */
    public function verPagosUsuario() {
        $usuario_id = $_SESSION['usuario_id'] ?? null; 

        $this->listado->listadoComun(
            "pagos_mensuales",
            ["mes", "monto", "fecha", "estado", "entrega"],
            ["usuario_id" => $usuario_id],
            ["fecha", "DESC"]
        );
    }

    /* ============================================================
       OBTENER TODOS LOS PAGOS (ADMIN)
    ============================================================ */
    public function verPagosAdmin() { 
        $this->listado->listadoAdmin(
            "pagos_mensuales",
            ["id", "usuario_id", "mes", "monto", "fecha", "archivo_url", "estado", "entrega"],
            [],
            ["fecha", "DESC"]
        );
    }

    /* ============================================================
       LISTAR PAGOS CON DEUDAS (ADMIN)
    ============================================================ */
    public function listarPagosDeudas() { 
        $this->listado->listadoAdmin(
            "Pagos_Deudas",
            ["usuario_id", "correo", "mes", "monto"],
            [],
            ["fecha", "DESC"]
        );
    }

    /* ============================================================
       OBTENER MENSUALIDAD CONFIGURADA
    ============================================================ */
    public function obtenerMensualidad() {  
        $this->listado->listadoComun(
            "configuracion",
            ["valor"],
            ["clave" => "mensualidad"]
        );
    }

    /* ============================================================
       TRADUCIR CÓDIGO DE ERROR DE SUBIDA
    ============================================================ */
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
}

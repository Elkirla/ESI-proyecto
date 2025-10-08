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
            if (!$archivo_url) {
                throw new Exception("Debe adjuntar un comprobante de pago.");
            }

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
                $this->eliminarArchivo($archivo_url);
                throw new Exception("Error al registrar el pago. Intente más tarde.");
            }

            echo json_encode([
                'success' => true,
                'message' => 'Pago registrado exitosamente. Estará pendiente de verificación.'
            ]);

        } catch (Exception $e) {
            error_log("[PAGOS_ERROR] User: " . ($_SESSION['usuario_id'] ?? 'unknown') . " - " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /* ============================================================
       REGISTRAR PAGO COMPENSATORIO
    ============================================================ */
    public function IngresarPagoCompensatorio() {
        try {
            $usuario_id = $_SESSION['usuario_id'] ?? null;
            if (!$usuario_id) throw new Exception("Usuario no identificado.");

            $monto = $_POST['monto'] ?? null;
            if (!$monto || !is_numeric($monto) || $monto <= 0) {
                throw new Exception("El campo 'monto' es obligatorio y debe ser mayor a 0.");
            }

            $uploader = new Uploads('/var/www/html/public/uploads/'); 
            $archivo_url = $uploader->subirArchivo('archivo');
            if (!$archivo_url) throw new Exception("Debe adjuntar un comprobante de pago.");

            $fecha = date('Y-m-d');
            $estado = 'pendiente';

            $pago = new Pago($usuario_id, null, $monto, $fecha, $archivo_url, $estado, null);

            $modelo = new PagoModelo();
            $ok = $modelo->registrarPagoCompensatorio($pago);

            if (!$ok) {
                $this->eliminarArchivo($archivo_url);
                throw new Exception("Error al registrar el pago. Intente más tarde.");
            }

            echo json_encode(['success' => true, 'message' => 'Pago compensatorio registrado exitosamente.']);

        } catch (Exception $e) {
            error_log("[PAGOS_ERROR] User: " . ($_SESSION['usuario_id'] ?? 'unknown') . " - " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
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
       OBTENER PAGOS DEL USUARIO
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
       OBTENER PAGOS COMPENSATORIOS
    ============================================================ */
    public function verPagosCompensatorios() {
        $usuario_id = $_SESSION['usuario_id'] ?? null; 
        $this->listado->listadoComun(
            "pagos_compensatorios",
            ["monto", "fecha", "estado", "archivo_url"],
            ["usuario_id" => $usuario_id],
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
       OBTENER DEUDAS USUARIO
    ============================================================ */
    public function verPagosDeuda() {
        $usuario_id = $_SESSION['usuario_id'] ?? null; 
        $this->listado->listadoComun(
            "pagos_deudas",
            ["fecha", "correo", "meses", "monto"],
            ["usuario_id" => $usuario_id],
            ["fecha", "DESC"]
        );
    }

    /* ============================================================
       CALCULAR PAGO DE DEUDAS
    ============================================================ */
public function CalcularPagoDeudas($usuario_id) {
    try {
        if (!$usuario_id) {
            return $this->responderJson("error", "Sesión no válida.");
        }

        // Obtener información del usuario
        ob_start();
        $this->listado->listadoComun(
            "usuarios",
            ["*"],                      
            ["id" => $usuario_id],       
            null,                        
            1                            
        );
        $jsonUsuario = ob_get_clean();
        $usuario = json_decode($jsonUsuario, true);

        if (!$usuario || !isset($usuario[0])) {
            return $this->responderJson("error", "No se encontró información del usuario.");
        }

        $fecha_inicio = $usuario[0]['fecha_registro'] ?? null;
        $correo = $usuario[0]['email'] ?? null;

        if (!$fecha_inicio) {
            return $this->responderJson("error", "No se encontró la fecha de registro del usuario.");
        }

        // Obtener mensualidad de la tabla configuracion
        $mensualidad = $this->obtenerMensualidadValor();

        // Obtener pagos aprobados para este usuario
        $pagos_aprobados = $this->obtenerPagosAprobados($usuario_id);

        // Obtener último cálculo de deuda (si existe)
        $ultimo_calculo = $this->obtenerUltimoCalculoDeuda($usuario_id);

        $fecha_desde = new DateTime($fecha_inicio);
        $fecha_actual = new DateTime();

        // Si hay un cálculo previo, empezar desde el mes siguiente al último cálculo
        if ($ultimo_calculo && isset($ultimo_calculo['fecha'])) {
            $fecha_desde = new DateTime($ultimo_calculo['fecha']);
            $fecha_desde->modify('+1 month');
        }

        // Si la fecha desde es mayor que la actual, no hay meses para verificar
        if ($fecha_desde > $fecha_actual) {
            $meses_adeudados = 0;
            $monto_total = 0;
        } else {
            // Calcular meses con deuda considerando pagos aprobados
            $deuda_meses = [];
            $periodo = new DatePeriod(
                $fecha_desde,
                new DateInterval('P1M'),
                $fecha_actual
            );

            foreach ($periodo as $fecha) {
                $mes_actual = $fecha->format('Y-m');
                
                // Verificar si hay pago aprobado para este mes
                $tiene_pago = $this->tienePagoAprobadoParaMes($pagos_aprobados, $mes_actual);
                
                if (!$tiene_pago) {
                    $deuda_meses[] = $mes_actual;
                }
            }

            $meses_adeudados = count($deuda_meses);
            $monto_total = $meses_adeudados * $mensualidad;
        }

        // Guardar deuda
        $modelo = new PagoModelo();
        $datos = [
            'fecha'      => $fecha_actual->format('Y-m-d'),
            'usuario_id' => $usuario_id,
            'correo'     => $correo,
            'meses'      => $meses_adeudados,
            'monto'      => $monto_total
        ];

        if (!$modelo->IngresarPagoDeuda($datos)) {
            return $this->responderJson("error", "No se pudo registrar la deuda en la base de datos.");
        }

        return $this->responderJson("ok", "Cálculo de deuda actualizado correctamente. Meses adeudados: " . $meses_adeudados . ", Monto: " . $monto_total);

    } catch (Exception $e) {
        error_log("[CALCULAR_DEUDA_ERROR] " . $e->getMessage());
        return $this->responderJson("error", "Error al calcular deuda: " . $e->getMessage());
    }
}

    /* ============================================================
       MÉTODOS AUXILIARES PRIVADOS
    ============================================================ */
    private function responderJson($status, $message) {
        echo json_encode(['status' => $status, 'message' => $message]);
    }
    private function eliminarArchivo($ruta) {
        $rutaCompleta = $_SERVER['DOCUMENT_ROOT'] . $ruta;
        if (file_exists($rutaCompleta)) {
            unlink($rutaCompleta);
        }
    }

private function obtenerMensualidadValor() {
    ob_start();
    $this->obtenerMensualidad();
    $output = ob_get_clean();
    $data = json_decode($output, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error al decodificar JSON de mensualidad: " . json_last_error_msg());
    }

    if (!is_array($data) || empty($data) || !isset($data[0]['valor'])) {
        throw new Exception("No se pudo obtener el valor de la mensualidad");
    }

    return floatval($data[0]['valor']);
}

private function obtenerPagosAprobados($usuario_id) {
    ob_start();
    $this->listado->listadoComun(
        "pagos_mensuales",
        ["fecha", "estado", "mes"],
        ["usuario_id" => $usuario_id, "estado" => "aprobado"]
    );
    $output = ob_get_clean();
    $data = json_decode($output, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("[DEBUG] Error JSON en obtenerPagosAprobados: " . json_last_error_msg());
        return [];
    }

    return is_array($data) ? $data : [];
}

private function obtenerUltimoCalculoDeuda($usuario_id) {
    ob_start();
    $this->listado->listadoComun(
        "pagos_deudas",
        ["fecha", "correo"],
        ["usuario_id" => $usuario_id],
        ["fecha", "DESC"],
        1
    );
    $output = ob_get_clean();
    $data = json_decode($output, true);

    if (is_array($data) && !empty($data) && isset($data[0])) {
        return $data[0];
    }
    
    return null;
}
    private function obtenerUsuarioInfo($usuario_id) {
        ob_start();
        $this->listado->listadoComun(
            "usuarios",
            ["fecha_inicio", "email"],
            ["id" => $usuario_id],
            null,
            1
        );
        $output = ob_get_clean();
        $data = json_decode($output, true);

        if (empty($data)) throw new Exception("Error al obtener información del usuario");
        return $data[0];
    }

private function tienePagoAprobadoParaMes($pagos_aprobados, $mes_buscado) {
    if (empty($pagos_aprobados)) {
        return false;
    }

    foreach ($pagos_aprobados as $pago) {
        // Verificar por fecha del pago (formato Y-m)
        if (isset($pago['fecha'])) {
            $fecha_pago = new DateTime($pago['fecha']);
            $mes_pago = $fecha_pago->format('Y-m');
            
            if ($mes_pago === $mes_buscado) {
                return true;
            }
        }
        
        // También verificar por el campo 'mes' explícito si existe
        if (isset($pago['mes']) && $pago['mes'] === $mes_buscado) {
            return true;
        }
    }
    
    return false;
}
    public function ActualizarDeudaPago() {
        $usuario_id = $_SESSION['usuario_id'] ?? null; 
        $this->CalcularPagoDeudas($usuario_id);
    }
}

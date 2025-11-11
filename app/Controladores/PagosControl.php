<?php
class PagosControl {
    private $listado;

    public function __construct() {
        require_once __DIR__ . '/../Entidades/pago.php'; 
        require_once __DIR__ . '/../Modelos/PagoModelo.php';
        require_once __DIR__ . '/../Controladores/ListadoControl.php';

        header('Content-Type: application/json; charset=utf-8');

        $this->listado = new ListadoControl();
    }

    /* ============================================================
    REGISTRAR NUEVO PAGO
    ============================================================ */
public function IngresarPago() {
    require_once __DIR__ . '/../Config/uploads.php';
    try {
        error_log("Inicio IngresarPago");

        $modelo = new PagoModelo();
        $usuario_id = $_SESSION['usuario_id'] ?? null; 

        $mes = date('m');
        error_log("Mes actual: $mes");

        /*
        if ($modelo->existePagoPendienteOAprobado($usuario_id, $mes)) {
            echo json_encode(['success' => true, 'message' => 'Ya has ingresado un pago este mes.']);
            return;
        }
        */

        $uploader = new Uploads('/var/www/html/public/uploads/');
        $archivo_url = $uploader->subirArchivo('archivo');
        error_log("Archivo URL: $archivo_url");

        if (!$archivo_url) throw new Exception("Debe adjuntar un comprobante de pago.");

        $monto = $this->obtenerMensualidadValor();
        error_log("Monto obtenido: $monto");

        $diaLimite = $this->getFechaLimitePago();
        $fecha = date('Y-m-d');
        $diaActual = intval(date('d'));
        $estado = 'pendiente';
        $entrega = ($diaActual <= $diaLimite) ? 'en_hora' : 'atrasado';

        $pago = new Pago($usuario_id, $mes, $monto, $fecha, $archivo_url, $estado, $entrega);
        $modelo->registrarPago($pago);

        echo json_encode(['success' => true, 'message' => 'Pago registrado exitosamente.']);
    } catch (Exception $e) {
        error_log("[ERROR_PAGO] " . $e->getMessage());
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
    OBTENER PAGOS DEL USUARIO
    ============================================================ */
    public function verMesesDeudaPagos() {
        $usuario_id = $_SESSION['usuario_id'] ?? null; 
        $this->listado->listadoComun(
            "Deudas_Mensuales",
            ["mes", "monto"],
            ["usuario_id" => $usuario_id],
            ["fecha_inicio", "ASC"]
        );
    }

    /* ============================================================
    VERIFICAR SI TIENE ALGÚN PAGO APROBADO
    ============================================================ */
    public function usuarioTienePagoAprobado() {
        try {
            $usuario_id = $_SESSION['usuario_id'] ?? null; 
    
            $pagos_aprobados = $this->obtenerPagosAprobados($usuario_id);
    
            return !empty($pagos_aprobados);
        } catch (Exception $e) {
            error_log("[ERROR_PAGO_APROBADO] " . $e->getMessage());
            return false;
        }
    }

/* ============================================================
OBTENER ESTADO DE LOS PAGOS DEL USUARIO
============================================================ */
public function verEstadoPagos($usuarioID = null) {
    try {

        if (!$usuarioID) { 
            $usuario_id = $_SESSION['usuario_id'] ?? null;  
        } else{
            $usuario_id= $usuarioID;
        }

        if (!$usuario_id) {
            throw new Exception("No hay usuario definido para consultar el estado de pagos.");
        }

        $pagos_aprobados = $this->obtenerPagosAprobados($usuario_id);
        $mes_actual = date('Y-m');
        $al_dia = $this->tienePagoAprobadoParaMes($pagos_aprobados, $mes_actual);

        echo json_encode([
            'success' => true,
            'estado' => $al_dia ? 'Al día' : 'atrasado',
        ]);

    } catch (Exception $e) {
        error_log("[ERROR_ESTADO_PAGO] " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

public function obtenerEstadoPagoUsuario($usuario_id) {
    // Obtener todos los pagos aprobados del usuario
    $pagos_aprobados = $this->obtenerPagosAprobados($usuario_id);

    // Mes actual
    $mes_actual = date('Y-m');

    // true = pagado, false = atrasado
    return $this->tienePagoAprobadoParaMes($pagos_aprobados, $mes_actual)
        ? 'Al día'
        : 'Atrasado';
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
            "Pagos_Deudas",
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
        $usuario_info = $this->obtenerUsuarioInfo($usuario_id);
        $fecha_inicio = $usuario_info['fecha_registro'] ?? null;
        $correo = $usuario_info['email'] ?? null;

        if (!$fecha_inicio) {
            return $this->responderJson("error", "No se encontró la fecha de registro del usuario.");
        }

        // Obtener mensualidad
        $mensualidad = $this->obtenerMensualidadValor();

        // Obtener pagos aprobados
        $pagos_aprobados = $this->obtenerPagosAprobados($usuario_id);

        $fecha_desde = new DateTime($fecha_inicio);
        $fecha_actual = new DateTime();

        // Calcular meses con deuda
        list($deudas_mensuales, $meses_totales_deuda, $primer_mes_pendiente) = $this->calcularDeudasMensuales(
            $fecha_desde,
            $fecha_actual,
            $mensualidad,
            $pagos_aprobados,
            $usuario_id,
            $correo
        );

        // Guardar deudas detalladas
        $modelo = new PagoModelo();
        $guardado = $modelo->guardarDeudasMensualesCompletas(
            $usuario_id,
            $deudas_mensuales,
            $meses_totales_deuda,
            $mensualidad * $meses_totales_deuda,
            $primer_mes_pendiente
        );

        if (!$guardado) {
            return $this->responderJson("error", "No se pudo registrar la deuda en la base de datos.");
        }

        return $this->responderJson("ok", "Cálculo de deuda actualizado correctamente. Meses adeudados: " . $meses_totales_deuda . ", Monto: " . ($mensualidad * $meses_totales_deuda));

    } catch (Exception $e) {
        error_log("[CALCULAR_DEUDA_ERROR] " . $e->getMessage());
        return $this->responderJson("error", "Error al calcular deuda: " . $e->getMessage());
    }
}

/* ============================================================
MÉTODOS AUXILIARES NUEVOS PARA CÁLCULO MENSUAL
============================================================*/

private function getFechaLimitePago() {
    ob_start();
    $this-> listado->listadoComun(
        "configuracion",
        ["valor"],
        ["clave" => "fecha_limite_pago"]
    );
    $output = ob_get_clean();
    $data = json_decode($output, true);
    if (empty($data) || !isset($data[0]['valor'])) {
        return 5; // Valor por defecto si no se encuentra
    }
    return intval($data[0]['valor']);
}
private function calcularDeudasMensuales($fecha_desde, $fecha_actual, $mensualidad, $pagos_aprobados, $usuario_id, $correo) {
    $deudas_mensuales = [];
    $meses_totales_deuda = 0;
    $primer_mes_pendiente = null;

    // Asegurarse de empezar desde el primer día del mes
    $fecha_desde->modify('first day of this month');
    $fecha_actual->modify('last day of this month');

    $mes_actual = clone $fecha_desde;

    while ($mes_actual <= $fecha_actual) {
        $mes_nombre = $this->obtenerNombreMes($mes_actual);
        $fecha_fin_mes = clone $mes_actual;
        $fecha_fin_mes->modify('last day of this month');

        // Verificar si hay pago aprobado para este mes
        $mes_formato = $mes_actual->format('Y-m');
        $tiene_pago = $this->tienePagoAprobadoParaMes($pagos_aprobados, $mes_formato);
        
        $adeudado = !$tiene_pago;
        
        if ($adeudado) {
            $meses_totales_deuda++;
            if ($primer_mes_pendiente === null) {
                $primer_mes_pendiente = $mes_actual->format('Y-m-d');
            }
        }

        $deudas_mensuales[] = [
            'usuario_id' => $usuario_id,
            'correo' => $correo,
            'mes' => $mes_nombre, // Ej: "Enero 2024"
            'fecha_inicio' => $mes_actual->format('Y-m-d'),
            'fecha_fin' => $fecha_fin_mes->format('Y-m-d'),
            'monto' => $adeudado ? $mensualidad : 0,
            'adeudado' => $adeudado ? 1 : 0,
            'tiene_pago' => $tiene_pago ? 1 : 0
        ];

        // Avanzar al siguiente mes
        $mes_actual->modify('+1 month');
    }

    return [$deudas_mensuales, $meses_totales_deuda, $primer_mes_pendiente];
}

private function obtenerNombreMes(DateTime $fecha) {
    $meses_espanol = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    
    $mes_numero = (int)$fecha->format('n');
    $anio = $fecha->format('Y');
    
    return $meses_espanol[$mes_numero] . ' ' . $anio;
}

private function obtenerUsuarioInfo($usuario_id) {
    ob_start();
    $this->listado->listadoComun(
        "usuarios",
        ["fecha_registro", "email"],
        ["id" => $usuario_id],
        null,
        1
    );
    $output = ob_get_clean();
    $data = json_decode($output, true);

    if (empty($data) || !isset($data[0])) {
        throw new Exception("Error al obtener información del usuario");
    }
    return $data[0];
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
private function tienePagoAprobadoParaMes($pagos_aprobados, $mes_buscado) {
    if (empty($pagos_aprobados)) {
        return false;
    }

    foreach ($pagos_aprobados as $pago) {

        // ✅ Comparar por la fecha  
        if (!empty($pago['fecha'])) {
            $fecha_pago = new DateTime($pago['fecha']);
            if ($fecha_pago->format('Y-m') === $mes_buscado) {
                return true;
            }
        }
        if (!empty($pago['mes'])) {
            $mes_texto = trim($pago['mes']);

            $meses_es = [
                'Enero','Febrero','Marzo','Abril','Mayo','Junio',
                'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
            ];

            foreach ($meses_es as $k => $nombre) {
                if (stripos($mes_texto, $nombre) !== false) {
                    $anio = preg_replace('/[^0-9]/', '', $mes_texto);
                    $mes_num = str_pad($k + 1, 2, '0', STR_PAD_LEFT);
                    $mes_convertido = "$anio-$mes_num";

                    if ($mes_convertido === $mes_buscado) {
                        return true;
                    }
                }
            }
        }
    }

    return false;
}


private function responderJson($status, $message) {
    echo json_encode(['status' => $status, 'message' => $message]);
}
    public function ActualizarDeudaPago() {
        $usuario_id = $_SESSION['usuario_id'] ?? null; 
        $this->CalcularPagoDeudas($usuario_id);
    }
}

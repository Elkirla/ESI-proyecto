<?php
class HorasControl {
    private $modelo;
    private $listado;
    private $usuario_id;

    public function __construct() {
        require_once __DIR__ . '/../Modelos/HorasModelo.php';
        require_once __DIR__ . '/../Controladores/ListadoControl.php';
        header('Content-Type: application/json; charset=utf-8');
        $this->listado = new ListadoControl();
        $this->modelo= new HorasModelo();  
        $this->usuario_id = $_SESSION['usuario_id'] ?? null;
    }

    public function IngresarHoras() {
        require_once __DIR__ . '/../Entidades/hora.php'; 
        try {
            $modelo = new HorasModelo(); 
            $horas = $_POST['horas'] ?? null;  
            $fecha = date('Y-m-d'); 
            
            if (!$this->usuario_id|| !$horas) {
                echo json_encode(['success' => false, 'error' => 'Faltan datos']);
                return;
            } elseif ($modelo->tieneHorasRegistradas($this->usuario_id, $fecha)) {
                echo json_encode(['success' => false, 'error' => 'Ya has registrado horas hoy']);
                return;
            }
            if ($horas>= $this->obtenerHorasSemanales()){
                echo json_encode(['success' => false, 'error' => 'Horas mayor al limite']);
                return;     
            }

            $hora = new Hora($this->usuario_id, $fecha, $horas);
            $ok = $modelo->registrarHoras($hora);

            echo json_encode([
                'success' => $ok,
                'error' => $ok ? null : 'No se pudo registrar en la BD'
            ]);

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function verHorasUsuario() { 
        $this->listado->listadoComun(
            "horas_trabajadas",
            ["fecha", "horas"],
            ["usuario_id" => $this->usuario_id],
            ["fecha", "DESC"]
        );
    }

    public function verTodasDeudasSemanasUsuario(){
        $this->listado->listadoComun(
            "Semana_deudas",
            ["fecha_inicio", "fecha_fin", "horas_faltantes", "horas_justificadas", "horas_compensadas"],
            ["usuario_id" => $this->usuario_id],
            ["fecha_inicio","DESC"]
        );
    }

    public function verHorasSemanales() {
        $this->listado->listadoComun(
            "configuracion",
            ["valor"],
            ["clave" => "horas_semanales"]
        );
    }
    public function verValorSemanal(){
        $this->listado->listadoComun(
            "configuracion",
            ["valor"],
            ["clave" => "valor_semanal"]
        );
    }
 
public function obtenerHorasFaltantesSemana($usuario_id){
    try {
        $semana = $this->obtenerSemanaActual();
        $fecha_inicio = $semana['inicio'];
        $fecha_fin = $semana['fin'];

        error_log("Buscando horas faltantes para usuario $usuario_id entre $fecha_inicio y $fecha_fin");

        $horas = $this->modelo->obtenerHorasFaltantesSemana($usuario_id, $fecha_inicio, $fecha_fin);

        error_log("Horas faltantes obtenidas del modelo: " . $horas);

        return $horas;

    } catch (Exception $e) {
        error_log("Error al obtener horas faltantes: " . $e->getMessage());
        return 0;
    }
}  

private function obtenerHorasFaltantesSemanaAlternativo($usuario_id, $fecha_inicio_semana) {
    try {
        require_once __DIR__ . '/../Controladores/ListadoControl.php';
        $listadoControl = new ListadoControl();

        // Buscar cualquier deuda del usuario en la semana actual
        ob_start();
        $listadoControl->listadoComun(
            "Semana_deudas",
            ["horas_faltantes", "fecha_inicio", "fecha_fin"],
            [
                "usuario_id" => $usuario_id
            ],
            ["fecha_inicio DESC"], // Ordenar por la más reciente
            1
        );
        $output = ob_get_clean();
        $data = json_decode($output, true);

        if (is_array($data) && count($data) > 0) {
            $deuda = $data[0];
            error_log("Deuda más reciente encontrada: " . print_r($deuda, true));
            
            // Verificar si la deuda más reciente es de esta semana
            $fechaDeuda = new DateTime($deuda['fecha_inicio']);
            $fechaInicioSemana = new DateTime($fecha_inicio_semana);
            
            if ($fechaDeuda->format('Y-W') === $fechaInicioSemana->format('Y-W')) {
                return (float) $deuda['horas_faltantes'];
            } else {
                throw new Exception("La deuda más reciente no es de esta semana");
            }
        } else {
            throw new Exception("No se encontraron deudas para el usuario");
        }

    } catch (Exception $e) {
        error_log("Error en método alternativo: " . $e->getMessage());
        throw new Exception("No se encontraron deudas para la semana actual");
    }
}

public function obtenerHorasTrabajadasSemana() {
    try {
        $usuario_id = $this->usuario_id;
        if (!$usuario_id) {
            echo json_encode(['success' => false, 'error' => 'Usuario no identificado']);
            return;
        }

        // Obtener la semana actual
        $semana_actual = $this->obtenerSemanaActual();
        $inicio_semana = new DateTime($semana_actual['inicio']);
        $fin_semana = new DateTime($semana_actual['fin']);

        // Obtener todas las horas trabajadas del usuario
        $horas_trabajadas = $this->obtenerHorasTrabajadas($usuario_id);

        // Calcular las horas trabajadas en la semana actual
        $horas_semana_actual = $this->calcularHorasEnSemana($horas_trabajadas, $inicio_semana, $fin_semana);

        echo json_encode([
            'success' => true,
            'horas' => $horas_semana_actual,
            'rango_semana' => [
                'inicio' => $semana_actual['inicio'],
                'fin' => $semana_actual['fin']
            ]
        ]);

    } catch (Exception $e) {
        error_log("Error en obtenerHorasTrabajadasSemana: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'horas_trabajadas_semana' => 0,
            'error' => $e->getMessage()
        ]);
    }
}
public function tienePagoCompensatorioSemana($usuario_id) {
    $semana = $this->obtenerSemanaActual();
    $inicio = $semana['inicio'];
    $fin = $semana['fin'];

    ob_start();
    $this->listado->listadoComun(
        "pagos_compensatorios",
        ["id", "fecha", "fecha_inicio", "fecha_fin", "estado"],
        ["usuario_id" => $usuario_id],
        ["fecha", "DESC"]
    );
    $output = ob_get_clean();
    $data = json_decode($output, true);

    if (!is_array($data) || empty($data)) {
        return false;
    }

    foreach ($data as $pago) {
        $f_ini = $pago['fecha_inicio'] ?? $pago['fecha'];
        $f_fin = $pago['fecha_fin'] ?? $f_ini;
        if (($f_ini <= $fin) && ($f_fin >= $inicio)) {
            return true;
        }
    }
    return false;
}

public function verHorasDeudaSemanal() {
    try {
        $usuario_id = $this->usuario_id;
        if (!$usuario_id) {
            echo json_encode(['success' => false, 'error' => 'Usuario no identificado']);
            return;
        }

        // Obtener la semana actual
        $semana_actual = $this->obtenerSemanaActual();
        $inicio_semana = $semana_actual['inicio'];

        // Obtener las horas faltantes para la semana actual
        $horas_faltantes = $this->obtenerHorasFaltantesSemana($usuario_id);

        echo json_encode([
            'success' => true,
            'horas_faltantes' => $horas_faltantes,
            'rango_semana' => [
                'inicio' => $semana_actual['inicio'],
                'fin' => $semana_actual['fin']
            ]
        ]);

    } catch (Exception $e) {
        error_log("Error en verHorasDeudaSemanal: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'horas_faltantes' => 0,
            'error' => $e->getMessage()
        ]);
    }
}
public function calcularSaldoCompensatorio($horas) {
    $monto =0; 
    $horas_totales=$this->obtenerHorasSemanales(); 
    $valor_semanal=$this->ValorSemanal();
    // Clasica regla de tres si que si
    $monto= ($horas * $valor_semanal) / $horas_totales;
    return $monto;
}
public function SaldoCompensatorioUsuario(){
    $horas=$this->obtenerHorasFaltantesSemana($this->usuario_id);
    $monto=$this->calcularSaldoCompensatorio($horas);
    echo json_encode([
        'success' => true,
        'horas' => $horas,
        'monto' => $monto
    ]);
}

 
public function CalcularHorasDeuda($id_usuario) {
    try {
        error_log("[CALCULAR_DEUDA_INICIO] Iniciando cálculo de deuda para usuario: $id_usuario");
        
        $horas_semanales = $this->obtenerHorasSemanales();
        error_log("[CALCULAR_DEUDA] Horas semanales configuradas: $horas_semanales");

        // Obtener datos CRUDOS primero
        $horas_trabajadas_array = $this->obtenerHorasTrabajadas($id_usuario);
        $justificativos = $this->obtenerJustificativosAprobados($id_usuario);
        $pagos_compensatorios = $this->obtenerPagosCompensatoriosAprobados($id_usuario);

        error_log("[CALCULAR_DEUDA_RESUMEN] Usuario $id_usuario - " .
                 "Horas trabajadas: " . count($horas_trabajadas_array) . " registros, " .
                 "Justificativos: " . count($justificativos) . ", " .
                 "Pagos compensatorios: " . count($pagos_compensatorios));

        // Mostrar detalles de los pagos compensatorios encontrados
        foreach ($pagos_compensatorios as $index => $pago) {
            error_log("[DETALLE_PAGO_$index] ID: {$pago['id']}, Fecha: {$pago['fecha']}, Horas: {$pago['horas']}, " .
                     "Fecha inicio: " . ($pago['fecha_inicio'] ?? 'NULL') . ", " .
                     "Fecha fin: " . ($pago['fecha_fin'] ?? 'NULL'));
        }

        $fecha_desde = new DateTime($this->obtenerFechaPrimerRegistro($id_usuario));
        $fecha_actual = new DateTime();

        list($deudas_semanales, $horas_totales_deuda, $primera_semana_pendiente) = $this->calcularDeudasSemanales(
            $fecha_desde,
            $fecha_actual,
            $horas_semanales,
            $horas_trabajadas_array,  
            $justificativos,
            $pagos_compensatorios,
            $id_usuario
        );
        
        error_log("[PRE_MODEL_DEBUG] Usuario: $id_usuario, Semanas a procesar: " . count($deudas_semanales));
        if (empty($deudas_semanales)) {
            throw new Exception("No se calcularon deudas semanales para el usuario $id_usuario");
        }
        
        $guardado_exitoso = $this->modelo->guardarDeudasHorasCompletas(
            $id_usuario,
            $deudas_semanales,
            $horas_totales_deuda,
            $primera_semana_pendiente
        );
        
        if (!$guardado_exitoso) {
            throw new Exception("El modelo falló al guardar las deudas. Revisar logs del Modelo para errores SQL.");
        }
        
        return [
            'success' => true,
            'usuario_id' => $id_usuario,
            'deuda_acumulada' => $horas_totales_deuda,
            'semanas_procesadas' => count($deudas_semanales),
            'pagos_compensatorios_encontrados' => count($pagos_compensatorios),
            'mensaje' => 'Deuda semanal actualizada correctamente'
        ];

    } catch (Exception $e) {
        error_log("[CALCULAR_DEUDA_ERROR] Error al calcular/guardar: " . $e->getMessage());
        
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

/* ============================================================
MÉTODOS AUXILIARES PRIVADOS CORREGIDOS
===========================================================*/

public function obtenerSemanaActual() {
    $hoy = new DateTime();
    
    // Siempre calcular desde el lunes de esta semana
    $inicio_semana = clone $hoy;
    $inicio_semana->modify('monday this week');
    
    $fin_semana = clone $inicio_semana;
    $fin_semana->modify('+6 days'); // Domingo
    
    error_log("Semana calculada: " . $inicio_semana->format('Y-m-d') . " a " . $fin_semana->format('Y-m-d'));
    
    return [
        'inicio' => $inicio_semana->format('Y-m-d'),
        'fin' => $fin_semana->format('Y-m-d')
    ];
}

private function obtenerHorasSemanales() {
    ob_start();
    $this->listado->listadoComun(
        "configuracion",
        ["valor"],
        ["clave" => "horas_semanales"],
        null,
        1
    );
    $output = ob_get_clean();
    $data = json_decode($output, true);

    if (!is_array($data) || empty($data[0]['valor'])) {
        throw new Exception("No se pudo obtener el valor de horas semanales");
    }

    return floatval($data[0]['valor']);
}

private function obtenerHorasTrabajadas($usuario_id) {
    try {
        error_log("[DEBUG_HORAS_TRABAJADAS] Iniciando obtención de horas para usuario: $usuario_id");
        
        // Limpiar cualquier buffer de salida previo
        while (ob_get_level()) ob_end_clean();
        
        ob_start();
        $this->listado->listadoComun(
            "horas_trabajadas",
            ["fecha", "horas"],
            ["usuario_id" => $usuario_id],
            ["fecha", "ASC"]
        );
        $output = ob_get_clean();
        
        $data = json_decode($output, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("[DEBUG_HORAS_TRABAJADAS] Error decodificando JSON: " . json_last_error_msg());
            return [];
        }
        
        error_log("[DEBUG_HORAS_TRABAJADAS] Número de registros de horas encontrados: " . (is_array($data) ? count($data) : 0));
        
        return is_array($data) ? $data : [];
        
    } catch (Exception $e) {
        error_log("[ERROR_HORAS_TRABAJADAS] Excepción: " . $e->getMessage());
        return [];
    }
}

private function obtenerJustificativosAprobados($usuario_id) {
    try {
        error_log("[DEBUG_JUSTIFICATIVOS] Iniciando obtención de justificativos para usuario: $usuario_id");
        
        // Limpiar cualquier buffer de salida previo
        while (ob_get_level()) ob_end_clean();
        
        ob_start();
        $this->listado->listadoComun(
            "justificativos",
            ["id", "fecha", "fecha_final", "motivo", "horas_equivalentes"],
            ["usuario_id" => $usuario_id, "estado" => "aprobado"],
            ["fecha", "ASC"]
        );
        $output = ob_get_clean();
        
        error_log("[DEBUG_JUSTIFICATIVOS] Output crudo: " . $output);
        
        $data = json_decode($output, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("[DEBUG_JUSTIFICATIVOS] Error decodificando JSON: " . json_last_error_msg());
            return [];
        }
        
        error_log("[DEBUG_JUSTIFICATIVOS] Número de justificativos encontrados: " . (is_array($data) ? count($data) : 0));
        
        return is_array($data) ? $data : [];
        
    } catch (Exception $e) {
        error_log("[ERROR_JUSTIFICATIVOS] Excepción: " . $e->getMessage());
        return [];
    }
}

private function obtenerPagosCompensatoriosAprobados($usuario_id) {
    try {
        error_log("[DEBUG_PAGOS_COMPENSATORIOS] Iniciando obtención de pagos para usuario: $usuario_id");
        
        // Limpiar cualquier buffer de salida previo
        while (ob_get_level()) ob_end_clean();
        
        ob_start();
        $this->listado->listadoComun(
            "pagos_compensatorios",
            ["id", "fecha", "fecha_inicio", "fecha_fin", "horas", "monto"],
            ["usuario_id" => $usuario_id, "estado" => "aprobado"],
            ["fecha", "ASC"]
        );
        $output = ob_get_clean();
        
        error_log("[DEBUG_PAGOS_COMPENSATORIOS] Output crudo: " . $output);
        
        $data = json_decode($output, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("[DEBUG_PAGOS_COMPENSATORIOS] Error decodificando JSON: " . json_last_error_msg());
            return [];
        }
        
        error_log("[DEBUG_PAGOS_COMPENSATORIOS] Datos decodificados: " . print_r($data, true));
        error_log("[DEBUG_PAGOS_COMPENSATORIOS] Número de pagos encontrados: " . (is_array($data) ? count($data) : 0));
        
        return is_array($data) ? $data : [];
        
    } catch (Exception $e) {
        error_log("[ERROR_PAGOS_COMPENSATORIOS] Excepción: " . $e->getMessage());
        return [];
    }
}

private function calcularHorasEnSemana($horas_trabajadas, $fecha_inicio, $fecha_fin) {
    $horas_totales = 0;
    $inicio_str = $fecha_inicio->format('Y-m-d');
    $fin_str = $fecha_fin->format('Y-m-d');
    
    foreach ($horas_trabajadas as $h) {
        if ($h['fecha'] >= $inicio_str && $h['fecha'] <= $fin_str) {
            $horas_totales += floatval($h['horas']);
        }
    }
    
    return $horas_totales;
}

private function obtenerFechaPrimerRegistro($usuario_id) {
    $horas = $this->obtenerHorasTrabajadas($usuario_id);
    if (empty($horas)) {
        return date('Y-m-d');
    }

    // Buscar la fecha mínima en el array para evitar dependencias de orden
    $min = null;
    foreach ($horas as $h) {
        if (empty($h['fecha'])) continue;
        if ($min === null || $h['fecha'] < $min) $min = $h['fecha'];
    }
    return $min ?? date('Y-m-d');
}

private function calcularDeudasSemanales($fecha_desde, $fecha_actual, $horas_semanales, $horas_trabajadas, $justificativos, $pagos_compensatorios, $usuario_id) {
    $deudas_semanales = [];
    $horas_totales_deuda = 0;
    $primera_semana_pendiente = null;

    // Asegurar que la iteración empiece desde un lunes
    $fecha_desde->modify('monday this week');
    
    // Incluir la semana actual (hasta el final de esta semana)
    $fin_semana_actual = new DateTime();
    $fin_semana_actual->modify('sunday this week');
    
    error_log("[DEBUG_CALCULO] Calculando deudas desde: " . $fecha_desde->format('Y-m-d') . " hasta: " . $fin_semana_actual->format('Y-m-d'));

    while ($fecha_desde <= $fin_semana_actual) { 
        $fecha_fin_semana = clone $fecha_desde;
        $fecha_fin_semana->modify('sunday this week');

        // Calcular horas de la semana
        $horas_semana = $this->calcularHorasEnSemana($horas_trabajadas, $fecha_desde, $fecha_fin_semana);
        $horas_faltantes_brutas = max(0, $horas_semanales - $horas_semana);

        // Buscar justificativos y pagos compensatorios para esta semana
        $justificativo = $this->obtenerJustificativoParaSemana($justificativos, $fecha_desde, $fecha_fin_semana);
        $pago_compensatorio = $this->obtenerPagoCompensatorioParaSemana($pagos_compensatorios, $fecha_desde, $fecha_fin_semana);

        $horas_justificadas = $justificativo ? $justificativo['horas_equivalentes'] : 0;
        $horas_compensadas = $pago_compensatorio ? $pago_compensatorio['horas'] : 0;

        // Calcular horas faltantes NETAS (restando justificaciones y compensaciones)
        $horas_faltantes_netas = max(0, $horas_faltantes_brutas - $horas_justificadas - $horas_compensadas);

        error_log("[DEBUG_COMPENSACION] Semana {$fecha_desde->format('Y-m-d')}: " .
                 "Horas trabajadas: $horas_semana, " .
                 "Horas faltantes brutas: $horas_faltantes_brutas, " .
                 "Horas justificadas: $horas_justificadas, " .
                 "Horas compensadas: $horas_compensadas, " .
                 "Horas faltantes netas: $horas_faltantes_netas");

        // Solo sumar a la deuda total si hay horas faltantes netas después de compensaciones
        if ($horas_faltantes_netas > 0) {
            $horas_totales_deuda += $horas_faltantes_netas;
            if ($primera_semana_pendiente === null) {
                $primera_semana_pendiente = $fecha_desde->format('Y-m-d');
            }
        }

        $deudas_semanales[] = [
            'fecha_inicio' => $fecha_desde->format('Y-m-d'),
            'fecha_fin' => $fecha_fin_semana->format('Y-m-d'),
            'horas_trabajadas' => $horas_semana,
            'horas_faltantes' => $horas_faltantes_netas, // Guardar las horas faltantes NETAS
            'horas_justificadas' => $horas_justificadas,
            'horas_compensadas' => $horas_compensadas,
            'motivo_justificacion' => $justificativo ? $justificativo['motivo'] : null,
            'pago_compensatorio_id' => $pago_compensatorio ? $pago_compensatorio['id'] : null
        ];

        $fecha_desde->modify('+1 week');
    }

    error_log("[DEBUG_CALCULO_FINAL] Total deudas calculadas: " . count($deudas_semanales) . 
             ", Horas totales deuda (netas): $horas_totales_deuda");

    return [$deudas_semanales, $horas_totales_deuda, $primera_semana_pendiente];
}

private function ValorSemanal() {
    ob_start();
    $this->listado->listadoComun(
        "configuracion",
        ["valor"],
        ["clave" => "valor_semanal"],
        null,
        1
    );
    $output = ob_get_clean();
    $data = json_decode($output, true);

    if (!is_array($data) || empty($data[0]['valor'])) {
        throw new Exception("No se pudo obtener el valor semanal");
    }

    return floatval($data[0]['valor']);
}
public function obtenerHorasSemanaDeTodos($usuarios) {
    $resultado = [];

    // Obtener rango semanal
    $semana = $this->obtenerSemanaActual();
    $inicio = new DateTime($semana['inicio']);
    $fin = new DateTime($semana['fin']);

    foreach ($usuarios as $u) {

        // Como está dentro de HorasControl, se puede llamar a métodos privados!
        $horasUsuario = $this->obtenerHorasTrabajadas($u['id']);
        $horasSemana = $this->calcularHorasEnSemana($horasUsuario, $inicio, $fin);

        $resultado[] = [
            'usuario' => $u['nombre'] . ' ' . $u['apellido'],
            'email' => $u['email'],
            'telefono' => $u['telefono'],
            'horas_trabajadas' => $horasSemana
        ];
    }

    return [
        'semana' => $semana,
        'data' => $resultado
    ];
}

 
private function obtenerJustificativoParaSemana($justificativos, $fecha_inicio, $fecha_fin) {
    $inicio = $fecha_inicio->format('Y-m-d');
    $fin = $fecha_fin->format('Y-m-d');
    foreach ($justificativos as $j) {
        $f_ini = $j['fecha'];
        $f_fin = $j['fecha_final'] ?? $f_ini;
        if ($this->haySuperposicionFechas($inicio, $fin, $f_ini, $f_fin)) return $j;
    }
    return null;
}

private function obtenerPagoCompensatorioParaSemana($pagos, $fecha_inicio, $fecha_fin) {
    $inicio = $fecha_inicio->format('Y-m-d');
    $fin = $fecha_fin->format('Y-m-d');
    
    error_log("[DEBUG_PAGOS] Buscando pago compensatorio para semana: $inicio a $fin");
    error_log("[DEBUG_PAGOS] Pagos disponibles: " . count($pagos));
    
    foreach ($pagos as $p) {
        $f_ini = $p['fecha_inicio'] ?? $p['fecha'];
        $f_fin = $p['fecha_fin'] ?? $f_ini;
        
        error_log("[DEBUG_PAGOS] Evaluando pago ID: {$p['id']}, Rango: $f_ini a $f_fin, Horas: {$p['horas']}");
        
        if ($this->haySuperposicionFechas($inicio, $fin, $f_ini, $f_fin)) {
            error_log("[DEBUG_PAGOS] ✅ Pago ID {$p['id']} aplica para la semana $inicio a $fin");
            return $p;
        } else {
            error_log("[DEBUG_PAGOS] ❌ Pago ID {$p['id']} NO aplica para la semana $inicio a $fin");
        }
    }
    
    error_log("[DEBUG_PAGOS] No se encontraron pagos compensatorios para la semana $inicio a $fin");
    return null;
}


private function haySuperposicionFechas($inicio1, $fin1, $inicio2, $fin2) {
    return ($inicio1 <= $fin2) && ($inicio2 <= $fin1);
}
public function actualizarDeudaHorasUsuario($id_usuario = null) {
    if (!$id_usuario) {
        $this->CalcularHorasDeuda($this->usuario_id);
    } else { 
        $this->CalcularHorasDeuda($id_usuario);
    }
}

public function verDeudasHorasUsuario(){
    $this->listado->listadoComun(
        "Horas_deuda",
        ["horas_deuda_total", "primera_semana_pendiente"],
        ["usuario_id" => $this->usuario_id],
        [],
        1
    );
}
}
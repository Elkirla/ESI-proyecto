<?php
class HorasControl {
    private $listado;
    private $usuario_id;

    public function __construct() {
        require_once __DIR__ . '/../Modelos/HorasModelo.php';
        require_once __DIR__ . '/../Controladores/ListadoControl.php';
        header('Content-Type: application/json; charset=utf-8');
        $this->listado = new ListadoControl();
        $this->usuario_id = $_SESSION['usuario_id'] ?? null;
    }

    public function IngresarHoras() {
        require_once __DIR__ . '/../Entidades/hora.php'; 
        try {
            $modelo = new HorasModelo();
            $fecha = $_POST['fecha'] ?? null;
            $horas = $_POST['horas'] ?? null;

            if (!$this->usuario_id || !$fecha || !$horas) {
                echo json_encode(['success' => false, 'error' => 'Faltan datos']);
                return;
            } elseif ($modelo->tieneHorasRegistradas($this->usuario_id, $fecha)) {
                echo json_encode(['success' => false, 'error' => 'Ya has registrado horas hoy']);
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
public function calcularSaldoCompensatorio() {
    try {
        // Recibir datos
        $horas = $_POST['horas'] ?? null;
        $valorSemanal = $_POST['valor_semanal'] ?? null;
        $horasSemanales = $_POST['horas_semanales'] ?? null;

        // Validaciones básicas
        if ($horas === null || $valorSemanal === null || $horasSemanales === null) {
            echo json_encode(['success' => false, 'error' => 'Faltan datos.']);
            return;
        }

        // Asegurarse que los valores sean numéricos
        if (!is_numeric($horas) || !is_numeric($valorSemanal) || !is_numeric($horasSemanales)) {
            echo json_encode(['success' => false, 'error' => 'Los valores deben ser numéricos.']);
            return;
        }

        // Convertir a float (por seguridad)
        $horas = floatval($horas);
        $valorSemanal = floatval($valorSemanal);
        $horasSemanales = floatval($horasSemanales);

        // Validar rango de horas (no más del total mensual ni negativas)
        $maxHoras = $horasSemanales * 4; // límite razonable de 4 semanas
        if ($horas < 0 || $horas > $maxHoras) {
            echo json_encode(['success' => false, 'error' => "El valor de horas no puede superar $maxHoras."]);
            return;
        }

        // Calcular saldo compensatorio
        $saldo = ($horas / $horasSemanales) * $valorSemanal;

        // Devolver JSON con formato limpio
        echo json_encode([
            'success' => true,
            'saldo' => round($saldo, 2),
            'mensaje' => 'Cálculo exitoso.'
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} 
public function CalcularHorasDeuda($id_usuario) {
    try {
        $horasModelo = new HorasModelo(); 
        $horas_semanales = $this->obtenerHorasSemanales();

        // CORRECCIÓN: Calcular correctamente la semana actual
        $semana_actual = $this->obtenerSemanaActual();
        $inicio_semana = $semana_actual['inicio'];
        $fin_semana = $semana_actual['fin'];

        $horas_trabajadas_array = $this->obtenerHorasTrabajadas($id_usuario);
        
        $horas_trabajadas_semana_actual = $this->calcularHorasEnSemana(
            $horas_trabajadas_array, 
            new DateTime($inicio_semana), 
            new DateTime($fin_semana)
        );

        // Calcular deuda actual (sin valores negativos)
        $horas_faltantes_actual = max(0, $horas_semanales - $horas_trabajadas_semana_actual);
 
        $justificativos = $this->obtenerJustificativosAprobados($id_usuario);
        $pagos_compensatorios = $this->obtenerPagosCompensatoriosAprobados($id_usuario);
 
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
 
        $horasModelo->guardarDeudasHorasCompletas(
            $id_usuario,
            $deudas_semanales,
            $horas_totales_deuda,
            $primera_semana_pendiente
        );
        
        return [
            'success' => true,
            'usuario_id' => $id_usuario,
            'horas_trabajadas' => $horas_trabajadas_semana_actual,
            'horas_faltantes' => $horas_faltantes_actual,
            'deuda_acumulada' => $horas_totales_deuda,
            'mensaje' => 'Deuda semanal actualizada correctamente'
        ];

    } catch (Exception $e) {
        echo json_encode([
            'debug' => 'error',
            'error' => $e->getMessage()
        ]);
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

/* ============================================================
MÉTODOS AUXILIARES PRIVADOS CORREGIDOS
===========================================================*/

private function obtenerSemanaActual() {
    $hoy = new DateTime();
    $dia_semana = (int)$hoy->format('N'); // 1 (lunes) a 7 (domingo)
    
    $inicio_semana = clone $hoy;
    $fin_semana = clone $hoy;
    
    if ($dia_semana == 7) {
        // Domingo: semana del lunes anterior al domingo actual
        $inicio_semana->modify('monday this week');
        // $fin_semana ya es el domingo actual
    } else {
        // Lunes a sábado: semana del lunes actual al domingo siguiente
        $inicio_semana->modify('monday this week');
        $fin_semana->modify('sunday this week');
    }
    
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
    $error_reporting = error_reporting(0);
    
    ob_start();
    $this->listado->listadoComun(
        "horas_trabajadas",
        ["fecha", "horas"],
        ["usuario_id" => $usuario_id],
        ["fecha"]
    );
    $output = ob_get_clean();
    
    error_reporting($error_reporting);
    
    $data = json_decode($output, true);
    return is_array($data) ? $data : [];
}


private function obtenerJustificativosAprobados($usuario_id) {
    ob_start();
    $this->listado->listadoComun(
        "justificativos",
        ["id", "fecha", "fecha_final", "motivo", "horas_equivalentes"],
        ["usuario_id" => $usuario_id, "estado" => "aprobado"],
        ["fecha"]
    );
    $output = ob_get_clean();
    $data = json_decode($output, true);
    return is_array($data) ? $data : [];
}

private function obtenerPagosCompensatoriosAprobados($usuario_id) {
    ob_start();
    $this->listado->listadoComun(
        "pagos_compensatorios",
        ["id", "fecha", "fecha_inicio", "fecha_fin", "horas", "monto"],
        ["usuario_id" => $usuario_id, "estado" => "aprobado"],
        ["fecha"]
    );
    
    $output = ob_get_clean();
    $data = json_decode($output, true);
    return is_array($data) ? $data : [];
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

    // Asegurarse de empezar en el lunes de la semana del primer registro
    $fecha_desde->modify('monday this week');

    while ($fecha_desde <= $fecha_actual) {
        $fecha_fin_semana = clone $fecha_desde;
        $fecha_fin_semana->modify('sunday this week');

        // Si la semana termina después de la fecha actual, la acotamos a fecha_actual
        if ($fecha_fin_semana > $fecha_actual) {
            $fecha_fin_semana = clone $fecha_actual;
        }

        $horas_semana = $this->calcularHorasEnSemana($horas_trabajadas, $fecha_desde, $fecha_fin_semana);
        $justificativo_semana = $this->obtenerJustificativoParaSemana($justificativos, $fecha_desde, $fecha_fin_semana);
        $pago_compensatorio_semana = $this->obtenerPagoCompensatorioParaSemana($pagos_compensatorios, $fecha_desde, $fecha_fin_semana);

        $horas_faltantes = 0;
        $horas_justificadas = 0;
        $horas_compensadas = 0;
        $motivo_justificacion = null;
        $pago_compensatorio_id = null;

        if ($horas_semana < $horas_semanales) {
            $diferencia_horas = $horas_semanales - $horas_semana;

            if ($justificativo_semana) {
                $horas_justificadas = min($diferencia_horas, floatval($justificativo_semana['horas_equivalentes'] ?? $diferencia_horas));
                $motivo_justificacion = $justificativo_semana['motivo'] ?? null;
            }

            if ($pago_compensatorio_semana && ($diferencia_horas - $horas_justificadas) > 0) {
                $horas_compensadas = min(($diferencia_horas - $horas_justificadas), floatval($pago_compensatorio_semana['horas'] ?? 0));
                $pago_compensatorio_id = $pago_compensatorio_semana['id'] ?? null;
            }

            $horas_faltantes = $diferencia_horas - $horas_justificadas - $horas_compensadas;

if ($horas_faltantes > 0) {
    $horas_totales_deuda += $horas_faltantes;
    if ($primera_semana_pendiente === null) {
        $primera_semana_pendiente = $fecha_desde->format('Y-m-d');
    }
} else {
    // Si no hay deuda en la semana, no suma al total
    $horas_faltantes = 0;
}

        }

        $deudas_semanales[] = [
            'fecha_inicio' => $fecha_desde->format('Y-m-d'),
            'fecha_fin' => $fecha_fin_semana->format('Y-m-d'),
            'horas_trabajadas' => $horas_semana,
            'horas_faltantes' => $horas_faltantes,
            'horas_justificadas' => $horas_justificadas,
            'horas_compensadas' => $horas_compensadas,
            'motivo_justificacion' => $motivo_justificacion,
            'pago_compensatorio_id' => $pago_compensatorio_id
        ];

        // Avanzar al siguiente lunes
        $fecha_desde->modify('+1 week');
        // Si la siguiente semana empieza después de fecha_actual, el while terminará
    }

    return [$deudas_semanales, $horas_totales_deuda, $primera_semana_pendiente];
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
    foreach ($pagos as $p) {
        $f_ini = $p['fecha_inicio'] ?? $p['fecha'];
        $f_fin = $p['fecha_fin'] ?? $f_ini;
        if ($this->haySuperposicionFechas($inicio, $fin, $f_ini, $f_fin)) return $p;
    }
    return null;
}

private function haySuperposicionFechas($inicio1, $fin1, $inicio2, $fin2) {
    return ($inicio1 <= $fin2) && ($inicio2 <= $fin1);
}
    public function actualizarDeudaHorasUsuario(){
        $this->CalcularHorasDeuda($this->usuario_id);
}
public function verDeudasHorasUsuario(){
    $this->listado->listadoComun(
        "Horas_deuda",
        ["horas_acumuladas", "primera_semana_pendiente"],
        ["usuario_id" => $this->usuario_id],
        [],
        1
    );
}
}
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

    public function calcularHorasSemanales() {
        //Horas totales trabajadas por el usuario en la semana actual
    }
    public function CalcularHorasDeuda($id_usuario){
    }
}

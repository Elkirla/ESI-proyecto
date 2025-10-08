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
    public function calcularsaldoCompensatorio() {
        try {
        $horas = $_POST['horas'] ?? null;
        $valorsemanal = $_POST['valor_semanal'] ?? null;
        $horassemanales = $_POST['horas_semanales'] ?? null;

        if (!$horas || !$valorsemanal) {
            echo json_encode(['success' => false, 'error' => 'Faltan datos']);
            return;
        }
        // Validar que horas sea un valor coherente, entre 0 y horas semanales x 4 (un mes de trabajo)
        if ($horas < 0 || $horas > ($horassemanales * 4)) {
            echo json_encode(['success' => false, 'error' => 'Valor de horas no válido']);
            return;
        }
        //Que sea un número
        if (!is_numeric($horas) || !is_numeric($valorsemanal)) {
            echo json_encode(['success' => false, 'error' => 'Horas y valor semanal deben ser números']);
            return;
        }
        // Cálculo del saldo compensatorio
        $saldo = ($horas / $horassemanales) * $valorsemanal;
        echo json_encode(['success' => true, 'saldo' => "$"+round($saldo, 2)]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}

<?php
class PagoCompensatorioControl {
    private $modelo;
    private $listado;

    public function __construct() {
        require_once __DIR__ . '/../Modelos/PagoCompensatorioModelo.php';
        require_once __DIR__ . '/../Controladores/ListadoControl.php';
        require_once __DIR__ . '/../Entidades/PagoCompensatorio.php';
        header('Content-Type: application/json; charset=utf-8');
        $this->modelo = new PagoCompensatorioModelo();
        $this->listado = new ListadoControl();
    }

public function IngresarPagoCompensatorio() {
    try {
        // Obtener usuario de sesión
        $usuario_id = $_SESSION['usuario_id'] ?? null;  
        // Obtener y validar datos del POST
        $monto = $_POST['monto'] ?? null;
        $horas = $_POST['horas'] ?? null;
        $archivo_url = $_POST['archivo_url'] ?? null;

        if (!$monto || !$horas) {
            throw new Exception("Datos incompletos: monto y horas son obligatorios.");
        }

        if (!is_numeric($monto) || $monto <= 0) {
            throw new Exception("Monto inválido.");
        }

        if (!is_numeric($horas) || $horas <= 0) {
            throw new Exception("Horas inválidas.");
        }

        // Fecha y semana actual
        $fecha = date('Y-m-d');
        $semana_inicio = date('Y-m-d', strtotime('monday this week'));
        $semana_fin = date('Y-m-d', strtotime('sunday this week'));

        // Verificar si ya existe pago compensatorio esta semana
        $existentes = $this->modelo->obtenerPorUsuarioYSemana($usuario_id, $semana_inicio, $semana_fin);
        if (!empty($existentes)) {
            echo json_encode([
                'success' => false,
                'mensaje' => 'Ya existe un pago compensatorio esta semana.'
            ]);
            return;
        }

        // Crear objeto pago compensatorio
        $pago = new PagoCompensatorio($usuario_id, $monto, $fecha, $horas, null, $archivo_url, 'pendiente');
        $this->modelo->insertar($pago);

        echo json_encode([
            'success' => true,
            'mensaje' => 'Pago compensatorio registrado exitosamente.'
        ]);

    } catch (Exception $e) { 
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

        public function verPagosCompensatorios() {
        $usuario_id = $_SESSION['usuario_id'] ?? null; 
        $this->listado->listadoComun(
            "pagos_compensatorios",
            ["monto", "fecha", "estado", "archivo_url"],
            ["usuario_id" => $usuario_id],
            ["fecha", "DESC"]
        );
    }
}

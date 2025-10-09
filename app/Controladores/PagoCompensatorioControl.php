<?php
class PagoCompensatorioControl {
    private $modelo;
    private $listado;

    public function __construct() {
        require_once __DIR__ . '/../Modelos/PagoCompensatorioModelo.php';
        require_once __DIR__ . '/../Controladores/ListadoControl.php';
        require_once __DIR__ . '/../Entidades/pago_compensatorio.php';
        header('Content-Type: application/json; charset=utf-8');
        $this->modelo = new PagoCompensatorioModelo();
        $this->listado = new ListadoControl();
    }

    public function IngresarPagoCompensatorio() {
        try {
            $usuario_id = $_SESSION['usuario_id'] ?? null;
            if (!$usuario_id) throw new Exception("Sesión expirada.");

            $monto = $_POST['monto'] ?? null;
            $horas = $_POST['horas'] ?? null;
            $archivo_url = $_POST['archivo_url'] ?? null;
            if (!$monto || !$horas) throw new Exception("Datos incompletos.");

            $fecha = date('Y-m-d');
            $semana_inicio = date('Y-m-d', strtotime('monday this week'));
            $semana_fin = date('Y-m-d', strtotime('sunday this week'));

            // 1️⃣ Verificar si ya existe un pago compensatorio esta semana
            $existentes = $this->modelo->obtenerPorUsuarioYSemana($usuario_id, $semana_inicio, $semana_fin);
            if (!empty($existentes)) {
                echo json_encode(['success' => false, 'mensaje' => 'Ya existe un pago compensatorio esta semana.']);
                return;
            }

            // 2️⃣ Crear objeto pago
            $pago = new PagoCompensatorio($usuario_id, null, $monto, $fecha, $horas, $archivo_url, 'pendiente');

            // 3️⃣ Insertar en BD
            $this->modelo->insertar($pago);

            // 4️⃣ Actualizar horas_deuda y semana_deudas
            $this->listado->ActualizarDeudasHoras($usuario_id);

            echo json_encode(['success' => true, 'mensaje' => 'Pago compensatorio registrado exitosamente.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
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

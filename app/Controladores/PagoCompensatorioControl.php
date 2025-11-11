<?php
class PagoCompensatorioControl {
    private $modelo;
    private $listado;
    private $usuario_id;

    public function __construct() {
        require_once __DIR__ . '/../Modelos/PagoCompensatorioModelo.php';
        require_once __DIR__ . '/../Controladores/ListadoControl.php';
        require_once __DIR__ . '/../Entidades/PagoCompensatorio.php';
        header('Content-Type: application/json; charset=utf-8');
        $this->modelo = new PagoCompensatorioModelo();
        $this->listado = new ListadoControl();
        $this->usuario_id = $_SESSION['usuario_id'] ?? null;
    }

    public function IngresarPagoCompensatorio() {
    require_once __DIR__ . '/../Controladores/HorasControl.php';
    require_once __DIR__ . '/../Config/uploads.php';
    
    try { 
        error_log("Inicio IngresarPagoCompensatorio");

        // Obtener las horas que le faltan en la semana
        $horasControl = new HorasControl();
        $horas = $horasControl->obtenerHorasFaltantesSemana($this->usuario_id);
        error_log("Horas faltantes obtenidas: " . $horas);
        
        // Calcular el monto basado en las horas faltantes
        $monto = $horasControl->calcularSaldoCompensatorio($horas);
        error_log("Monto calculado: " . $monto);

        // Subir archivo del comprobante 
        $uploader = new Uploads('/var/www/html/public/uploads/');
        $archivo_url = $uploader->subirArchivo('archivo');
        error_log("Archivo URL: $archivo_url");  
        
        if (!$archivo_url) {
            throw new Exception("Debe adjuntar un comprobante de pago.");
        }
         
        if ($horasControl->tienePagoCompensatorioSemana($this->usuario_id)) {
            echo json_encode([
                'success' => false,
                'mensaje' => 'Ya existe un pago compensatorio esta semana.'
            ]);
            return;
        }

        
        // Fecha y semana actual
        $fecha = date('Y-m-d');
        $semana_inicio = date('Y-m-d', strtotime('monday this week'));
        $semana_fin = date('Y-m-d', strtotime('sunday this week'));

        error_log("Verificando pa
        go existente para usuario: $this->usuario_id, semana: $semana_inicio a $semana_fin");

        // Verificar si ya existe pago compensatorio esta semana
        $existentes = $this->modelo->obtenerPorUsuarioYSemana($this->usuario_id, $semana_inicio, $semana_fin);
        if (!empty($existentes)) {
            echo json_encode([
                'success' => false,
                'mensaje' => 'Ya existe un pago compensatorio esta semana.'
            ]);
            return;
        }

        error_log("Creando objeto pago compensatorio...");

        // Crear objeto pago compensatorio
        $pago = new PagoCompensatorio($this->usuario_id, $monto, $fecha, $horas, null, $archivo_url, 'pendiente');
        $this->modelo->insertar($pago);

        error_log("Pago compensatorio registrado exitosamente");

        echo json_encode([
            'success' => true,
            'mensaje' => 'Pago compensatorio registrado exitosamente.'
        ]);

    } catch (Exception $e) { 
        error_log("[ERROR_PAGO_COMPENSATORIO] " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

        public function verPagosCompensatorios() { 
        $this->listado->listadoComun(
            "pagos_compensatorios",
            ["monto", "fecha", "estado", "archivo_url"],
            ["usuario_id" => $this->usuario_id],
            ["fecha", "DESC"]
        );
    }
}
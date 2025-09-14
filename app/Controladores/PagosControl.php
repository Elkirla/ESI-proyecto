<?php
class PagosControl{
public function IngresarPago() {
    header('Content-Type: application/json');
    try {
        $usuario_id = $_POST['usuario_id'] ?? null;
        $mes = $_POST['mes'] ?? null;
        $fecha = date('Y-m-d');
        $archivo_url = $_POST['archivo_url'] ?? null;
        $estado = 'pendiente';

        $pago = new pago($usuario_id, $mes, $fecha, $archivo_url, $estado);

        $modelo = new PagoModelo();
        $modelo->registrarPago($pago);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}
}
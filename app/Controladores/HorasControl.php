<?php
class HorasControl {
    public function IngresarHoras() {
        header('Content-Type: application/json');
        
         if (!$usuario_id || !$fecha || !$horas) {
         echo json_encode(['success' => false, 'error' => 'Faltan datos']);
         exit;
         }
        
        try {
            $usuario_id = $_POST['usuario_id'] ?? '';
            $fecha = $_POST['fecha'] ?? '';
            $horas = $_POST['horas'] ?? '';

            $hora = new Hora($usuario_id, $fecha, $horas); 

            $modelo = new HorasModelo();
            $modelo->registrarHoras($hora);

            echo json_encode(['success' => true]);
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }
}

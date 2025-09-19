<?php
class HorasControl {
    public function IngresarHoras() {
        require_once __DIR__ . '/../Entidades/hora.php'; 
        header('Content-Type: application/json');

        try {
            session_start();
            $modelo = new HorasModelo();
            $usuario_id = $_SESSION['usuario_id'] ?? null;

            $fecha = $_POST['fecha'] ?? null;
            $horas = $_POST['horas'] ?? null;

            if (!$usuario_id || !$fecha || !$horas) {
                echo json_encode(['success' => false, 'error' => 'Faltan datos']);
                exit;
            }elseif ($modelo->tieneHorasRegistradas($usuario_id, $fecha)) {
                echo json_encode(['success' => false, 'error' => 'Ya has registrado horas hoy']);
                exit;
            }

            $hora = new Hora($usuario_id, $fecha, $horas);

            $ok = $modelo->registrarHoras($hora);

            if ($ok) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'No se pudo registrar en la BD']);
            }
            exit;

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }
}

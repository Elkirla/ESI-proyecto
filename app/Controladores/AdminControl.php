<?php
class AdminControl {
private $listado;
    public function __construct() { 
        header('Content-Type: application/json; charset=utf-8');
        require_once __DIR__ . '/../Controladores/ListadoControl.php';
        $this->listado = new ListadoControl();
        
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
            http_response_code(404);
            include __DIR__ . '/../Vistas/404.php';
            exit;
        }

    }

    // ===================================
    // RESPUESTA JSON CENTRALIZADA
    // ===================================
    private function response($success, $data = []) {
        echo json_encode(array_merge(['success' => $success], $data));
        exit;
    }

    // ===================================
    // DASHBOARD
    // ===================================
    public function dashboardAdmin() {
        include __DIR__ . "/../Vistas/backoffice.php";
    }  

    // ===================================
    // PAGOS
    // ===================================
    public function aprobarPago() {
        require_once __DIR__ . '/../Modelos/PagoModelo.php'; 

        try {
            $pago_id = $_POST['pago_id'] ?? null;

            if (!$pago_id || !is_numeric($pago_id)) {
                throw new Exception("ID de pago inválido.");
            }

            $modelo = new PagoModelo();
            $ok = $modelo->aprobarPago($pago_id);

            if (!$ok) {
                throw new Exception("Error al aprobar el pago. Intente más tarde.");
            }

            $this->response(true, ['message' => 'Pago aprobado exitosamente.']);

        } catch (Exception $e) {
            error_log("[PAGOS_APROBAR_ERROR] Admin: " . ($_SESSION['usuario_id'] ?? 'unknown') . " - " . $e->getMessage());
            http_response_code(500);
            $this->response(false, ['error' => $e->getMessage()]);
        }
    }
    
    public function rechazarPago() {
        require_once __DIR__ . '/../Modelos/PagoModelo.php'; 
        try {
            $pago_id = $_POST['pago_id'] ?? null;

            if (!$pago_id || !is_numeric($pago_id)) {
                throw new Exception("ID de pago inválido.");
            }

            $modelo = new PagoModelo();
            $ok = $modelo->rechazarPago($pago_id);

            if (!$ok) {
                throw new Exception("No se pudo rechazar el pago.");
            }

            $this->response(true, ['message' => 'Pago rechazado con éxito.']);

        } catch (Exception $e) {
            error_log("[PAGOS_RECHAZAR_ERROR] Admin: " . ($_SESSION['usuario_id'] ?? 'unknown') . 
                " - Pago: " . ($pago_id ?? 'none') . " - " . $e->getMessage());
            http_response_code(500);
            $this->response(false, ['error' => $e->getMessage()]);
        }
    }

    public function verPagosAdmin() { 
        $this->listado->listadoComun(
            "pagos_mensuales",
            ["id", "usuario_id", "mes", "monto", "fecha", "archivo_url", "estado", "entrega"],
            [],
            ["fecha", "DESC"]
        );
    }
    public function listarPagosDeudas() { 
        $this->listado->listadoComun(
            "Pagos_Deudas",
            ["usuario_id", "correo", "mes", "monto"],
            [],
            ["fecha", "DESC"]
        );
    }
    // ===================================
    // PAGOS COMPENSATORIOS
    // ===================================

    public function aprobarPagoCompensatorio(){
        require_once __DIR__ . '/../Modelos/PagoModelo.php'; 

        try {
            $pago_id = $_POST['pago_id'] ?? null;

            if (!$pago_id || !is_numeric($pago_id)) {
                throw new Exception("ID de pago Compensatorio inválido.");
            }

            $modelo = new PagoModelo();
            $ok = $modelo->aprobarPagoCompensatorio($pago_id);

            if (!$ok) {
                throw new Exception("Error al aprobar el pago Compensatorio. Intente más tarde.");
            }

            $this->response(true, ['message' => 'Pago compensatorio aprobado exitosamente.']);

        } catch (Exception $e) {
            error_log("[PAGOS_APROBAR_ERROR] Admin: " . ($_SESSION['usuario_id'] ?? 'unknown') . " - " . $e->getMessage());
            http_response_code(500);
            $this->response(false, ['error' => $e->getMessage()]);
        }
    }

    public function rechazarPagoCompensatorio(){
    require_once __DIR__ . '/../Modelos/PagoModelo.php'; 

        try {
            $pago_id = $_POST['pago_id'] ?? null;

            if (!$pago_id || !is_numeric($pago_id)) {
                throw new Exception("ID de pago inválido.");
            }

            $modelo = new PagoModelo();
            $ok = $modelo->rechazarPagoCompensatorio($pago_id);

            if (!$ok) {
                throw new Exception("Error al rechazar el pago compensatorio. Intente más tarde.");
            }

            $this->response(true, ['message' => 'Pago rechazado exitosamente.']);

        } catch (Exception $e) {
            error_log("[COMPENSATORIO_RECHAZAR_ERROR] Admin: " . ($_SESSION['usuario_id'] ?? 'unknown') . " - " . $e->getMessage());
            http_response_code(500);
            $this->response(false, ['error' => $e->getMessage()]);
        }
    }

    public function listarPagosCompensatorios() { 
        $this->listado->listadoComun(
            "pagos_compensatorios",
            ["id", "usuario_id", "monto", "fecha", "archivo_url", "estado"],
            [],
            ["fecha", "DESC"]
        );
    }

    // ===================================
    // JUSTIFICATIVOS
    // ===================================
    public function aceptarJustificativo() {
        require_once __DIR__ . '/../Modelos/JustificativoModelo.php';

        try {
            $id = $_POST['id'] ?? null;
            if (!$id || !is_numeric($id)) {
                throw new Exception("ID de justificativo inválido.");
            }

            $modelo = new JustificativoModelo();
            $ok = $modelo->aceptarJustificativo($id);

            if (!$ok) {
                throw new Exception("No se pudo actualizar en la base de datos.");
            }

            $this->response(true);

        } catch (Exception $e) {
            $this->response(false, ['error' => $e->getMessage()]);
        }
    }

    public function rechazarJustificativo() {
        require_once __DIR__ . '/../Modelos/JustificativoModelo.php';

        try {
            $id = $_POST['id'] ?? null;
            if (!$id || !is_numeric($id)) {
                throw new Exception("ID de justificativo inválido.");
            }

            $modelo = new JustificativoModelo();
            $ok = $modelo->rechazarJustificativo($id);

            if (!$ok) {
                throw new Exception("No se pudo actualizar en la base de datos.");
            }

            $this->response(true);

        } catch (Exception $e) {
            $this->response(false, ['error' => $e->getMessage()]);
        }
    }

    public function listarJustificativosAdmin() {
    $this->listado->listadoComun(
        "justificativos",
        ["usuario_id", "fecha", "motivo", "archivo_url", "estado"],
        [],                    
        ["fecha", "DESC"]
    );
}
    // ===================================
    // NOTIFICACIONES
    // ===================================
    public function CrearNotificacion() {  
        require_once __DIR__ . '/../Modelos/NotiModelo.php';
        try {
            $usuario_id = $_POST['usuario_id'] ?? null;
            $mensaje = $_POST['mensaje'] ?? null;

            if (!$usuario_id || !$mensaje) {
                throw new Exception("Faltan parámetros.");
            }

            $modelo = new NotiModelo();
            if (!$modelo->InsertarNoti($usuario_id, $mensaje)) {
                throw new Exception("Error al insertar la notificación.");
            }

            $this->response(true);

        } catch (Exception $e) {
            $this->response(false, ['error' => $e->getMessage()]);
        }
    }

    // ===================================
    // USUARIOS
    // ===================================
    public function RechazarUsuario() {
        require_once __DIR__ . '/../Modelos/UsuarioModelo.php';
        try {
            $correo = $_POST['mensaje'] ?? null;
            if (!$correo) {
                throw new Exception("Correo no recibido.");
            }

            $modelo = new UsuarioModelo();
            $ok = $modelo->rechazarUsuario($correo);

            if (!$ok) {
                throw new Exception("No se pudo actualizar en la BD.");
            }

            $this->response(true);

        } catch (Exception $e) {
            $this->response(false, ['error' => $e->getMessage()]);
        }
    }

    public function AceptarUsuario() {
        require_once __DIR__ . '/../Modelos/UsuarioModelo.php';
        try {
            $correo = $_POST['mensaje'] ?? null;
            if (!$correo) {
                throw new Exception("Correo no recibido.");
            }

            $modelo = new UsuarioModelo();
            $ok = $modelo->aceptarUsuario($correo);

            if (!$ok) {
                throw new Exception("No se pudo actualizar en la BD.");
            }

            $this->response(true);

        } catch (Exception $e) {
            $this->response(false, ['error' => $e->getMessage()]);
        }
    }
    public function cargarUsuariosPendientes() {
    $this->listado->listadoComun(
        "usuarios",
        ["id", "nombre", "apellido"],
        ["estado" => "pendiente"]
    );
}
    public function ObtenerUsuarioPorId() {
        $id = $_POST['id'] ?? null;
        $this->listado->listadoComun(
            "usuarios",
            ["nombre", "apellido", "telefono", "email", "ci"],
            ["id" => $id],
            null,
            1
        );
}
    // ===================================
    // HORAS
    // ===================================

public function verHorasAdmin() {
    $this->listado->listadoComun(
        "horas_trabajadas",
        ["fecha", "horas"],
        [],                       
        ["fecha", "DESC"]
    );
}
}

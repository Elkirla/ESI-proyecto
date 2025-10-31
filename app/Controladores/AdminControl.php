<?php
class AdminControl {
    private $listado;
    private $notiControl;

    public function __construct() {
        header('Content-Type: application/json; charset=utf-8');
        require_once __DIR__ . '/../Controladores/ListadoControl.php';
        require_once __DIR__ . '/../Controladores/NotiControl.php';
        $this->listado = new ListadoControl();
        $this->notiControl = new NotiControl();

        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
            http_response_code(404);
            include __DIR__ . '/../Vistas/404.php';
            exit;
        }
    }

    private function response($success, $data = []) {
        echo json_encode(array_merge(['success' => $success], $data));
        exit;
    }

    // ===================================
    // PAGOS ✅ con Notificaciones
    // ===================================
    public function aprobarPago() {
        require_once __DIR__ . '/../Modelos/PagoModelo.php';

        try {
            $pago_id = $_POST['pago_id'] ?? null;
            if (!$pago_id) throw new Exception("ID de pago inválido.");

            $modelo = new PagoModelo();
            $pago = $modelo->obtenerPago($pago_id);

            if (!$modelo->aprobarPago($pago_id))
                throw new Exception("No se pudo aprobar.");

            $this->notiControl->CrearNoti(
                "Tu pago fue aprobado ✅",
                $pago['usuario_id']
            );

            $this->response(true, ['message' => 'Pago aprobado.']);
        } catch (Exception $e) {
            $this->response(false, ['error' => $e->getMessage()]);
        }
    }

    public function rechazarPago() {
        require_once __DIR__ . '/../Modelos/PagoModelo.php';

        try {
            $pago_id = $_POST['pago_id'] ?? null;
            if (!$pago_id) throw new Exception("ID inválido.");

            $modelo = new PagoModelo();
            $pago = $modelo->obtenerPago($pago_id);

            $modelo->rechazarPago($pago_id);

            $this->notiControl->CrearNoti(
                "Tu pago fue rechazado ❌",
                $pago['usuario_id']
            );

            $this->response(true, ['message' => 'Rechazado.']);
        } catch (Exception $e) {
            $this->response(false, ['error' => $e->getMessage()]);
        }
    }

    // ===================================
    // PAGOS COMPENSATORIOS ✅
    // ===================================
    public function aprobarPagoCompensatorio() {
        require_once __DIR__ . '/../Modelos/PagoModelo.php';

        try {
            $pago_id = $_POST['pago_id'] ?? null;
            if (!$pago_id) throw new Exception("ID inválido.");

            $modelo = new PagoModelo();
            $pago = $modelo->obtenerPagoCompensatorio($pago_id);

            $modelo->aprobarPagoCompensatorio($pago_id);

            $this->notiControl->CrearNoti(
                "Tu pago compensatorio fue aprobado ✅",
                $pago['usuario_id']
            );

            $this->response(true, ['message' => 'Aprobado.']);
        } catch (Exception $e) {
            $this->response(false, ['error' => $e->getMessage()]);
        }
    }

    public function rechazarPagoCompensatorio() {
        require_once __DIR__ . '/../Modelos/PagoModelo.php';

        try {
            $pago_id = $_POST['pago_id'] ?? null;
            if (!$pago_id) throw new Exception("ID inválido.");

            $modelo = new PagoModelo();
            $pago = $modelo->obtenerPagoCompensatorio($pago_id);

            $modelo->rechazarPagoCompensatorio($pago_id);

            $this->notiControl->CrearNoti(
                "Tu pago compensatorio fue rechazado ❌",
                $pago['usuario_id']
            );

            $this->response(true, ['message' => 'Rechazado.']);
        } catch (Exception $e) {
            $this->response(false, ['error' => $e->getMessage()]);
        }
    }

    // ===================================
    // JUSTIFICATIVOS ✅
    // ===================================
    public function aceptarJustificativo() {
        require_once __DIR__ . '/../Modelos/JustificativoModelo.php';

        try {
            $id = $_POST['id'] ?? null;
            if (!$id) throw new Exception("ID inválido.");

            $modelo = new JustificativoModelo();
            $justi = $modelo->obtenerJustificativo($id);

            $modelo->aceptarJustificativo($id);

            $this->notiControl->CrearNoti(
                "Tu justificativo fue aceptado ✅",
                $justi['usuario_id']
            );

            $this->response(true);
        } catch (Exception $e) {
            $this->response(false, ['error' => $e->getMessage()]);
        }
    }

    public function rechazarJustificativo() {
        require_once __DIR__ . '/../Modelos/JustificativoModelo.php';

        try {
            $id = $_POST['id'] ?? null;
            if (!$id) throw new Exception("ID inválido.");

            $modelo = new JustificativoModelo();
            $justi = $modelo->obtenerJustificativo($id);

            $modelo->rechazarJustificativo($id);

            $this->notiControl->CrearNoti(
                "Tu justificativo fue rechazado ❌",
                $justi['usuario_id']
            );

            $this->response(true);
        } catch (Exception $e) {
            $this->response(false, ['error' => $e->getMessage()]);
        }
    }

    // ===================================
    // USUARIOS ✅
    // ===================================
    public function AceptarUsuario() {
        require_once __DIR__ . '/../Modelos/UsuarioModelo.php';
        require_once __DIR__ . '/../Controladores/UnidadControl.php';

        try {
            $usuario_id = $_POST['usuario_id'] ?? null;
            if (!$usuario_id) throw new Exception("ID no recibido.");

            $modelo = new UsuarioModelo();
            $unidadcontrol = new UnidadControl();

            $unidad_id = $unidadcontrol->CalcularUnidad();
            $modelo->AsignarUnidad($usuario_id, $unidad_id);
            $modelo->aceptarUsuario($usuario_id);

            $this->notiControl->CrearNoti(
                "¡Bienvenido! ✅ Tu cuenta fue aprobada.",
                $usuario_id
            );

            $this->response(true);
        } catch (Exception $e) {
            $this->response(false, ['error' => $e->getMessage()]);
        }
    }

    public function RechazarUsuario() {
        require_once __DIR__ . '/../Modelos/UsuarioModelo.php';

        try {
            $usuario_id = $_POST['usuario_id'] ?? null;
            if (!$usuario_id) throw new Exception("ID inválido.");

            $modelo = new UsuarioModelo();
            $modelo->rechazarUsuarioId($usuario_id);

            $this->notiControl->CrearNoti(
                "Tu registro fue rechazado ❌",
                $usuario_id
            );

            $this->response(true);
        } catch (Exception $e) {
            $this->response(false, ['error' => $e->getMessage()]);
        }
    }
}

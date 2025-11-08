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

        if ($_SESSION['rol'] !== 'administrador') {
            require_once __DIR__ . '/../Controladores/AuthControl.php';
            $auth=new AuthControl;
            $auth->Mostrar404();
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
    // PAGOS
    // ===================================
 
    public function aprobarPago() {
        require_once __DIR__ . '/../Modelos/PagoModelo.php';

        try {
            $pago_id = $_POST['pago_id'] ?? null;

            if (!$pago_id) throw new Exception("ID de pago inválido.");

            $modelo = new PagoModelo();
            $pago = $modelo->aprobarPago($pago_id);
 
            $this->notiControl->CrearNoti(
                "Tu pago fue aprobado",
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
                "Tu pago fue rechazado",
                $pago['usuario_id']
            );

            $this->response(true, ['message' => 'Rechazado.']);
        } catch (Exception $e) {
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

   public function aprobarPagoCompensatorio() {
        require_once __DIR__ . '/../Modelos/PagoModelo.php';

        try {
            $pago_id = $_POST['pago_id'] ?? null;
            if (!$pago_id) throw new Exception("ID inválido.");

            $modelo = new PagoModelo();
            $pago = $modelo->obtenerPagoCompensatorio($pago_id);

            $modelo->aprobarPagoCompensatorio($pago_id);

            $this->notiControl->CrearNoti(
                "Tu pago compensatorio fue aprobado",
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
    public function CrearNotificacion($usuario_id, $mensaje) {  
        require_once __DIR__ . '/../Modelos/NotiModelo.php';
        try { 

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
        // Aceptar usuario_id o id (según lo que envíe JS)
        $usuario_id = $_POST['usuario_id'] ?? $_POST['id'] ?? null;
         
        $modelo = new UsuarioModelo();

        // Actualizar estado en la BD
        $ok = $modelo->rechazarUsuario($usuario_id);

        if (!$ok) {
            throw new Exception("No se pudo actualizar el usuario en la base de datos.");
        }

        $this->response(true, [
            "mensaje" => "Usuario rechazado correctamente.",
            "usuario_id" => $usuario_id,
            "nuevo_estado" => "rechazado"
        ]);

    } catch (Exception $e) {
        $this->response(false, ['error' => $e->getMessage()]);
    }
}


public function AceptarUsuario() { 
    require_once __DIR__ . '/../Modelos/UsuarioModelo.php';
    require_once __DIR__ . '/../Controladores/UnidadControl.php';

    try {
        $usuario_id = $_POST['id'] ?? null;

        if (!$usuario_id) {
            throw new Exception("ID de usuario no recibido.");
        }

        $modelo = new UsuarioModelo();
        $unidadcontrol = new UnidadControl();
 
        // Calcular la unidad con menos ocupación
        $unidad_id = $unidadcontrol->CalcularUnidad();
 
        // Asignar al usuario a la unidad
        $modelo->AsignarUnidad($usuario_id, $unidad_id);
 
        // Cambiar el estado del usuario a "activo"
        $ok = $modelo->aceptarUsuario($usuario_id);

        if (!$ok) {
            throw new Exception("No se pudo actualizar el usuario en la base de datos.");
        }

        // Obtener info de la unidad asignada (para devolver en el JSON)
        $unidad = $unidadcontrol->ObtenerUnidadPorId($unidad_id);

        $this->response(true, [
            "mensaje" => "Usuario aceptado y asignado correctamente.",
            "usuario_id" => $usuario_id,
            "unidad_asignada" => [
                "id" => $unidad["id"],
                "codigo" => $unidad["codigo"]
            ],
            "nuevo_estado" => "activo"
        ]);

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
    // UNIDADES
    // ===================================

    public function CrearUnidad(){

    }

    public function CambiarEstadoUnidad(){

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
public function verDeudasHorasAdmin() {
    $this->listado->listadoComun(
        "Horas_deuda",
        ["usuario_id", "horas_acumuladas", "primera_semana_pendiente"],
        [],
        [],
        null
    );
}
}

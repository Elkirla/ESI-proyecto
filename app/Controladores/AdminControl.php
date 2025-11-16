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
        $pago = $modelo->aprobarPago($pago_id); // ✅ Obtiene usuario_id correctamente

        // Capturar cualquier salida de CrearNoti
        ob_start();
        $this->notiControl->CrearNoti(
            "Tu pago fue aprobado ✅",
            $pago['usuario_id']
        );
        ob_end_clean();

        $this->response(true, ['message' => 'Pago aprobado']);

    } catch (Exception $e) {
        $this->response(false, ['error' => $e->getMessage()]);
    }
}

public function rechazarPago() {
    require_once __DIR__ . '/../Modelos/PagoModelo.php';

    try {
        $pago_id = $_POST['pago_id'] ?? null;
        if (!$pago_id) throw new Exception("ID de pago inválido.");

        $modelo = new PagoModelo();
        $pago = $modelo->rechazarPago($pago_id);

        if (!$pago) throw new Exception("No se pudo rechazar el pago");

        ob_start();
        $this->notiControl->CrearNoti(
            "Tu pago fue rechazado ❌",
            $pago['usuario_id']
        );
        ob_end_clean();

        $this->response(true, ['message' => 'Pago rechazado']);

    } catch (Exception $e) {
        $this->response(false, ['error' => $e->getMessage()]);
    }
}




    public function verPagosAdmin() { 
        $this->listado->listadoComun(
            "pagos_mensuales",
            ["id", "usuario_id", "mes", "monto", "fecha", "archivo_url" , "entrega"],
            ["estado" => "pendiente"],
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
    require_once __DIR__ . '/../Modelos/PagoCompensatorioModelo.php';

    try {
        $pago_id = $_POST['pago_id'] ?? null;
        if (!$pago_id) throw new Exception("ID inválido.");

        $modelo = new PagoCompensatorioModelo();
        $resultado = $modelo->aprobarPagoCompensatorio($pago_id);

        if (!$resultado) throw new Exception("No se pudo aprobar el pago.");
 
        ob_start();
        $this->notiControl->CrearNoti(
            "Tu pago compensatorio fue aprobado ✅",
            $resultado['usuario_id'] // ← Cambio importante aquí
        );
        ob_end_clean();

        $this->response(true, [
            'message' => 'Pago compensatorio aprobado ✅',
            'debug' => [
                'pago_id' => $pago_id,
                'usuario_id' => $resultado['usuario_id']
            ]
        ]);

    } catch (Exception $e) {
        $this->response(false, ['error' => $e->getMessage()]);
    }
}

public function rechazarPagoCompensatorio() {
    require_once __DIR__ . '/../Modelos/PagoCompensatorioModelo.php';

    try {
        $pago_id = $_POST['pago_id'] ?? null;
        if (!$pago_id) throw new Exception("ID inválido.");

        $modelo = new PagoCompensatorioModelo(); 
        $resultado = $modelo->rechazarPagoCompensatorio($pago_id);

        if (!$resultado) throw new Exception("No se pudo rechazar el pago.");

        ob_start();
        $this->notiControl->CrearNoti(
            "Tu pago compensatorio fue rechazado ❌",
            $resultado['usuario_id']  
        );
        ob_end_clean();

        $this->response(true, [
            'message' => 'Pago compensatorio rechazado ❌',
            'debug' => [
                'pago_id' => $pago_id,
                'usuario_id' => $resultado['usuario_id']
            ]
        ]);

    } catch (Exception $e) {
        $this->response(false, ['error' => $e->getMessage()]);
    }
}



    public function listarPagosCompensatorios() { 
        $this->listado->listadoComun(
            "pagos_compensatorios",
            ["id", "usuario_id", "monto", "fecha", "archivo_url"],
            ["estado" => "pendiente"],
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
        ["estado"=>"pendiente"],                    
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
public function EliminarUsuario() {
    header('Content-Type: application/json');

    $id = $_POST["id"] ?? null;

    if (!$id) {
        echo json_encode([
            "success" => false,
            "error" => "ID no recibido"
        ]);
        return;
    }

    require_once __DIR__ . '/../Modelos/UsuarioModelo.php';
    $modelo = new UsuarioModelo();

    $resultado = $modelo->EliminarUsuarioPorId($id);

    if ($resultado) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode([
            "success" => false,
            "error" => "No se pudo eliminar el usuario (¿no existe?)"
        ]);
    }
}

public function usuarioPorID() { 
    $ci = $_POST["ci"] ?? null;
$this->listado->listadoComun(
    "usuarios",
    ["id", "nombre", "apellido", "email", "telefono", "ci", "estado"],
    ["ci" => $ci],
    [],
    null
);
}

public function ModificarDatosUsuarios() {

    require_once __DIR__ . '/../Controladores/UserControl.php';

    if (!isset($_POST["id"])) {
        echo json_encode(["error" => "ID no enviado"]);
        return;
    }

    $usuarioControl = new UserControl();
    $usuarioControl->ModificarDatos($_POST["id"], false);
}


 public function ObtenerUsuariosBackoffice() {
    require_once __DIR__ . '/../Modelos/UsuarioModelo.php';
    require_once __DIR__ . '/../Controladores/UnidadControl.php';

    $modeloUsuario = new UsuarioModelo();  
    $ControlUnidad  = new UnidadControl();

    $usuarios = $modeloUsuario->ObtenerUsuarios();

    $listaFinal = [];

    foreach ($usuarios as $u) {
 
        $unidad = $ControlUnidad->ObtenerUnidadPorId($u['id']);

        // PREPARAR JSON FINAL
        $listaFinal[] = [
            "usuario"  => $u['usuario'], 
            "unidad"   => $unidad ? $unidad['codigo'] : "Sin unidad",
            "correo"   => $u['correo'],
            "telefono" => $u['telefono'],
            "ci"       => $u['ci'],
 
            "id"       => $u['id'],
            "nombre"   => $u['nombre'],
            "apellido" => $u['apellido'],
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($listaFinal);
}


public function RechazarUsuario() {
    require_once __DIR__ . '/../Modelos/UsuarioModelo.php';

    try { 
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

        // 1. Asignar unidad automáticamente
        $unidad_id = $unidadcontrol->AsignarUnidadAUsuario($usuario_id);

        // 2. Marcar usuario como activo
        $ok = $modelo->aceptarUsuario($usuario_id);

        if (!$ok) {
            throw new Exception("No se pudo actualizar el usuario en la base de datos.");
        }

        // 3. Obtener datos de la unidad para retornarlos
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
public function ObtenerPagosDeUsuarios() {
    require_once __DIR__ . '/../Modelos/UsuarioModelo.php';
    require_once __DIR__ . '/../Controladores/PagosControl.php';

    $modeloUsuarios = new UsuarioModelo();
    $controlPagos = new PagosControl();

    $usuarios = $modeloUsuarios->ObtenerTodosUsuarios();
    $resultado = [];

    foreach ($usuarios as $usuario) {
        $estadoPago = $controlPagos->obtenerEstadoPagoUsuario($usuario['id']);

        $resultado[] = [
            'nombre' => $usuario['nombre'],
            'apellido' => $usuario['apellido'],
            'telefono' => $usuario['telefono'],
            'email' => $usuario['email'],
            'estado_pago' => $estadoPago
        ];
    }

    echo json_encode([
        'success' => true,
        'data' => $resultado
    ]);
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
    require_once __DIR__ . '/../Modelos/UsuarioModelo.php';
    require_once __DIR__ . '/../Controladores/HorasControl.php';

    try {
        $modeloUsuarios = new UsuarioModelo();
        $controlHoras = new HorasControl();

        $usuarios = $modeloUsuarios->ObtenerTodosUsuarios();

        $resultado = $controlHoras->obtenerHorasSemanaDeTodos($usuarios);

        echo json_encode([
            'success' => true,
            'semana' => $resultado['semana'],
            'data' => $resultado['data']
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
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
    // ===================================
    // CONFIGURACIÖN
    // ===================================

 public function EditarConfiguracion() {
    require_once __DIR__ . '/../Modelos/UsuarioModelo.php';
    require_once __DIR__ . '/../Config/validator.php';

    $modelo = new UsuarioModelo();
    $validator = new validator();

    $claves = [
        'fecha_limite_pago',
        'mensualidad',
        'horas_semanales',
        'valor_semanal',
        'cuota_semanal'
    ];

    $errores = [];
    $datos = [];

    foreach ($claves as $clave) {

        if (!isset($_POST[$clave])) {
            $errores[$clave] = "Falta el valor para $clave";
            continue;
        }

        $valor = trim($_POST[$clave]);

        switch ($clave) {

            case 'fecha_limite_pago':
                if (!$validator->EsDiaDelMes($valor)) {
                    $errores[$clave] = "Debe ser un día válido entre 1 y 31.";
                }
                break;

            case 'mensualidad':
            case 'horas_semanales':
            case 'valor_semanal':
            case 'cuota_semanal':
                if (!$validator->EsNumeroEnteroPositivo($valor)) {
                    $errores[$clave] = "Debe ser un número entero positivo.";
                }
                break;
        }

        $datos[$clave] = $valor;
    }

    if (!empty($errores)) {
        echo json_encode([
            "success" => false,
            "errores" => $errores
        ]);
        return;
    }

    foreach ($datos as $clave => $valor) {
        $modelo->editarConfig($clave, $valor);
    }

    echo json_encode([
        "success" => true,
        "mensaje" => "Configuraciones actualizadas correctamente"
    ]);
}
public function ListarDatosUsuario() {
    require_once __DIR__ . '/../Modelos/UsuarioModelo.php';
    require_once __DIR__ . '/../Controladores/HorasControl.php';
    require_once __DIR__ . '/../Controladores/PagosControl.php';

    $modelo = new UsuarioModelo();
    $controlHoras = new HorasControl();
    $controlPagos = new PagosControl();

    try {

        $userInfo = $_POST["UserInfo"] ?? null;
        if (!$userInfo) {
            echo json_encode(["error" => "Debe ingresar una CI o correo."]);
            return;
        }

        // Determinar ID
        if (filter_var($userInfo, FILTER_VALIDATE_EMAIL)) {
            $id_usuario = $modelo->ObtenerIdPorEmail($userInfo);
        } elseif (ctype_digit($userInfo)) {
            $id_usuario = $modelo->ObtenerIdPorCI($userInfo);
        } else {
            echo json_encode(["error" => "Formato inválido."]);
            return;
        }

        if (!$id_usuario) {
            echo json_encode(["error" => "No se encontró usuario."]);
            return;
        }

        // Actualizar deudas
        $controlHoras->actualizarDeudaHorasUsuario($id_usuario);
        $controlPagos->ActualizarDeudaPago($id_usuario);

        // ----------------------------------------------------
        // CAPTURAR TODAS LAS SALIDAS
        // ----------------------------------------------------
        ob_start();
        $this->listado->listadoComun("horas_trabajadas", ["*"], ["usuario_id" => $id_usuario]);
        $json_horas = ob_get_clean();
        $horas = json_decode($json_horas, true);

        ob_start();
        $this->listado->listadoComun("pagos_mensuales", ["*"], ["usuario_id" => $id_usuario]);
        $json_pagos = ob_get_clean();
        $pagos = json_decode($json_pagos, true);

        ob_start();
        $this->listado->listadoComun("Deudas_Mensuales", ["*"], ["usuario_id" => $id_usuario]);
        $json_deudas_m = ob_get_clean();
        $deudas_m = json_decode($json_deudas_m, true);

        ob_start();
        $this->listado->listadoComun("Semana_deudas", ["*"], ["usuario_id" => $id_usuario]);
        $json_deudas_s = ob_get_clean();
        $deudas_s = json_decode($json_deudas_s, true);

        // ----------------------------------------------------
        // RESPUESTA FINAL — UN SOLO JSON LIMPIO
        // ----------------------------------------------------
        echo json_encode([
            "status" => "ok",
            "horas" => $horas,
            "pagos" => $pagos,
            "deudas_mensuales" => $deudas_m,
            "deudas_semanales" => $deudas_s
        ]);

    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}


public function ObtenerTodasConfig(){
    $this->listado->listadoComun(
        "configuracion",
        ["*"],
        [],
        [],
        null
    );    
}
}

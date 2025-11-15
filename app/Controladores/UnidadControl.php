<?php 
require_once __DIR__ . '/../Modelos/UnidadModelo.php';
require_once __DIR__ . '/../Entidades/unidad.php';  
require_once __DIR__ . '/../Controladores/ListadoControl.php';

class UnidadControl {
    private $usuario_id;
    private $listado;
    private $unidadModelo;

    public function __construct() {
        $this->listado = new ListadoControl();
        $this->unidadModelo = new UnidadModelo();
        $this->usuario_id = $_SESSION['usuario_id'] ?? null;
    }
    
public function AsignarUnidadAUsuario($usuario_id_raw) {
    error_log("=== INICIO AsignarUnidadAUsuario ===");
    error_log("POST completo: " . print_r($_POST, true));
    error_log("Usuario ID crudo recibido: " . var_export($usuario_id_raw, true));

    // 1. Validar ID de usuario
    $usuario_id = filter_var($usuario_id_raw, FILTER_VALIDATE_INT);

    if ($usuario_id === false || $usuario_id <= 0) {
        error_log("ERROR: ID de usuario inválido → " . var_export($usuario_id_raw, true));
        throw new Exception("ID de usuario inválido: " . var_export($usuario_id_raw, true));
    }

    error_log("Usuario ID validado: $usuario_id");

    require_once __DIR__ . '/../Modelos/UsuarioModelo.php';
    $modelo = new UsuarioModelo();

    try {
        // 2. Calcular unidad
        $unidad_id = $this->CalcularUnidad();
        error_log("Unidad calculada: " . var_export($unidad_id, true));

        if (!$unidad_id) {
            error_log("ERROR: CalcularUnidad devolvió NULL o 0");
            throw new Exception("No hay unidades disponibles.");
        }

        // 3. Asignar unidad
        error_log("Asignando usuario $usuario_id a unidad $unidad_id ...");
        $modelo->AsignarUnidad($usuario_id, $unidad_id);

        error_log("Asignación completada correctamente.");
        error_log("=== FIN AsignarUnidadAUsuario ===");

        return $unidad_id;

    } catch (Exception $e) {
        error_log("ERROR en AsignarUnidadAUsuario: " . $e->getMessage());
        throw $e;
    }
}


private function ListarUsuariosAsignados($usuario_id) {
    ob_start();
    $this->listado->listadoComun(
        "usuarios_unidades",
        ["unidad_id"],
        ["usuario_id" => $usuario_id]
    );
    $output = ob_get_clean();

    $data = json_decode($output, true);

    if (!empty($data)) {
        return $data[0]["unidad_id"];  
    }

    return null;  
}

public function CalcularUnidad() {
    $unidades = $this->ListarUnidades();
 
    if (empty($unidades)) {
        throw new Exception("No existen unidades registradas.");
    }
 
    $ocupaciones = [];

    foreach ($unidades as $unidad) {
        $usuarios = $this->ListarUsuarios($unidad['id']);
        $ocupaciones[$unidad['id']] = count($usuarios);
    }
 
    $maxOcupacion = max($ocupaciones);
 
    $bloqueActual = ceil($maxOcupacion / 5);  
 
    $limiteBloque = $bloqueActual * 5;
 
    foreach ($unidades as $unidad) {
        if ($ocupaciones[$unidad['id']] < $limiteBloque) {
            return $unidad['id'];
        }
    }
 
    return $unidades[0]['id'];
} 
private function ListarUnidades() {
    ob_start();
    $this->listado->listadoComun(
        "unidades_habitacionales",
        ["id", "codigo" ]
    );
    $output = ob_get_clean();
 
    $data = json_decode($output, true);
 
    if (!is_array($data)) { 
        throw new Exception("Error al obtener las unidades. Respuesta inesperada: " . $output);
    }

    return $data;
}

private function ListarUsuarios($unidad_id) {
    ob_start();
    $this->listado->listadoComun(
        "usuarios_unidades",
        ["usuario_id"],
        ["unidad_id" => $unidad_id]
    );
    $output = ob_get_clean();

    $data = json_decode($output, true);

    if (!is_array($data)) { 
        return [];
    }

    return $data;
}
public function ObtenerDatosUnidad() {  
    //Obtener la unidad asignada al usuario
    ob_start();
    $this->listado->listadoComun(
        "usuarios_unidades",
        ["unidad_id"],
        ["usuario_id" => $this->usuario_id]
    );
    $output = ob_get_clean();
    $data = json_decode($output, true);

    if (empty($data)) {
        echo json_encode(["error" => "No se encontró unidad asignada", "unidad"=>null]);
        return;
    }

    $unidad_id = $data[0]['unidad_id'];

    //Obtener los datos de la unidad
    try{
    $unidad = $this->ObtenerUnidadPorId($unidad_id);
    echo json_encode($unidad);
    return;
    }catch(Exception $e){
        echo json_encode(["error" => "Unidad no encontrada"]);
        return;
    } 
}

public function ObtenerUnidadPorId($id) {  
    $unidad = $this->unidadModelo->UnidadPorID($id);
    return $unidad;
}

}

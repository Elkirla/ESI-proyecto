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
    ob_start();
    $this->listado->listadoComun(
        "unidades_habitacionales",
        ["codigo", "estado"],
        ["id" => $id]
    );
    $output = ob_get_clean();
    $data = json_decode($output, true);
    return $data[0] ?? null;
}

}

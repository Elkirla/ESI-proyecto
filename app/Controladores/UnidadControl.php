<?php 
require_once __DIR__ . '/../Modelos/UnidadModelo.php';
require_once __DIR__ . '/../Entidades/unidad.php';  
require_once __DIR__ . '/../Controladores/ListadoControl.php';

class UnidadControl {

    private $listado;
    private $unidadModelo;

    public function __construct() {
        $this->listado = new ListadoControl();
        $this->unidadModelo = new UnidadModelo();
    }
 
public function CalcularUnidad() {
    $unidades = $this->ListarUnidades();
 
    if (empty($unidades)) {
        throw new Exception("No existen unidades registradas.");
    }
 
    $ocupaciones = [];

    foreach ($unidades as $unidad) {
        $usuarios = $this->ListarUsuarios($unidad['id']); //26
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
public function ObtenerUnidadPorId($id) {
    ob_start();
    $this->listado->listadoComun(
        "unidades_habitacionales",
        ["id", "codigo"],
        ["id" => $id]
    );
    $output = ob_get_clean();
    $data = json_decode($output, true);
    return $data[0] ?? null;
}

}

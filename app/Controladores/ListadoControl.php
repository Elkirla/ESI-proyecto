<?php
class ListadoControl {
    private $modelo;

    public function __construct() {
        require_once __DIR__ . '/../Modelos/ReporteModelo.php';
        $this->modelo = new ReporteModelo();
        header('Content-Type: application/json; charset=utf-8');
    }

    public function listadoComun($tabla, $columnas, $condiciones = [], $orden = null, $limite = null) {
        try {
            $arreglo = $this->modelo->listadoUniversalSimple(
                $tabla,
                $columnas,
                $condiciones,
                $orden,
                $limite
            );
            echo json_encode($arreglo);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
}

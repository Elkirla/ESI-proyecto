<?php
class ListadoControl {
    private $modelo;

    public function __construct() {
        require_once __DIR__ . '/../Modelos/ReporteModelo.php';
        $this->modelo = new ReporteModelo();
        session_start();
        header('Content-Type: application/json; charset=utf-8');
    }

    public function listadoComun($tabla, $columnas, $condiciones = [], $orden = null, $limite = null) {
    $usuarioid = $_SESSION['usuario_id'] ?? null;
    if (!$usuarioid) {
        echo json_encode([
            "error" => "Su sesión ha expirado. Por favor, inicie sesión nuevamente."]);
        exit;
    }
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

    // listado solo para administradores
    public function listadoAdmin($tabla, $columnas, $condiciones = [], $orden = null, $limite = null) {
        if ($_SESSION['rol'] !== 'administrador') {
            http_response_code(404);
            include __DIR__ . '/../Vistas/404.php';
            return;
        }
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
            http_response_code(403);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
}

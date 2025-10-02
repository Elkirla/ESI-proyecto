<?php
class UserControl {
public function cargarDatosUsuario() {   
     require_once __DIR__ . '/../Modelos/ReporteModelo.php';
    session_start();
    header('Content-Type: application/json; charset=utf-8');

    try {
        if (!isset($_SESSION["usuario_id"])) {
            echo json_encode(["error" => "No hay usuario en sesión"]);
            return;
        }

        $modelo = new ReporteModelo();
        $usuario = $modelo->listadoUniversalSimple(
            "usuarios",
            ["nombre", "apellido", "telefono", "email", "ci"],
            ["id" => $_SESSION["usuario_id"]],
            null,
            1
        );

        if (!$usuario) {
            echo json_encode(["error" => "Usuario no encontrado"]);
            return;
        }

        // Como limit=1, $usuario es un array con una fila
        echo json_encode($usuario[0]);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}



public function cargarUsuariosPendientes() {
    require_once __DIR__ . '/../Modelos/ReporteModelo.php';
    session_start();
    header('Content-Type: application/json; charset=utf-8');

    if ($_SESSION['rol'] == 'administrador') {
        try {
            $modelo = new ReporteModelo();
            $usuarios = $modelo->listadoUniversalSimple(
                "usuarios",
                ["id", "nombre", "apellido"],
                ["estado" => "pendiente"]
            );

            echo json_encode($usuarios);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }

   } else {
        http_response_code(404);
        include __DIR__ . '/../Vistas/404.php'; 
    }
}


public function ObtenerUsuarioPorId() {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            throw new Exception("ID de usuario no proporcionado");
        }

        $modelo = new UsuarioModelo();
        $usuario = $modelo->obtenerDatosUsuario($id);

        if (!$usuario) {
            echo json_encode(["error" => "Usuario no encontrado"]);
            return;
        }

        $resultado = [
            "nombre"   => $usuario->getNombre(),
            "apellido" => $usuario->getApellido(),
            "telefono" => $usuario->getTelefono(),
            "email"    => $usuario->getEmail(),
            "ci"       => $usuario->getCi()
        ];

        echo json_encode($resultado);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}


}

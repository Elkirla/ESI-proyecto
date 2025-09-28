<?php
class UserControl {
    public function cargarDatosUsuario() {
        session_start();

        header('Content-Type: application/json; charset=utf-8');

        try {
            if (!isset($_SESSION["usuario_id"])) {
                echo json_encode(["error" => "No hay usuario en sesión"]);
                return;
            }

            $modelo = new UsuarioModelo();
            $usuario = $modelo->obtenerDatosUsuario($_SESSION["usuario_id"]);

            if (!$usuario) {
                echo json_encode(["error" => "Usuario no encontrado"]);
                return;
            }

            // Solo JSON, nada de var_dump
            echo json_encode([
                "nombre"   => $usuario->getNombre(),
                "apellido" => $usuario->getApellido(),
                "telefono" => $usuario->getTelefono(),
                "email"    => $usuario->getEmail()
            ]);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
  public function cargarUsuariosPendientes() {
                session_start();
    header('Content-Type: application/json; charset=utf-8');

if($_SESSION['rol'] == 'administrador') {
    try {
        $modelo = new UsuarioModelo();
        $usuarios = $modelo->obtenerUsuariosPendientes();  

        $resultado = [];
        foreach ($usuarios as $usuario) {
            $resultado[] = [
                "id"       => $usuario->getId(),
                "nombre"   => $usuario->getNombre(),
                "apellido" => $usuario->getApellido(),
            ];
        }

        echo json_encode($resultado);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}else{
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

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
}

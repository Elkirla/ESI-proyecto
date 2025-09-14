<?php
// test_insert_completo.php
require_once "Config/database.php";
require_once "Entidades/usuario.php";
require_once "Modelos/UsuarioModelo.php";

try {
    $fecha = date('Y-m-d H:i:s');
    $usuario = new Usuario(
        1,                   // ID del rol
        "Prueba",
        "Pedrito",
        "0981234567",        // teléfono
        "12345678",          // cédula
        "test2@example.com", // email diferente
        password_hash("password123", PASSWORD_BCRYPT),
        "pendiente",
        $fecha
    );
    
    $modelo = new UsuarioModelo();
    $result = $modelo->CrearUsuario($usuario);
    
    echo $result ? "✅ Inserción exitosa con todos los campos" : "❌ Error en inserción";
    
} catch(Exception $e) {
    echo "❌ Exception: " . $e->getMessage();
}
?>
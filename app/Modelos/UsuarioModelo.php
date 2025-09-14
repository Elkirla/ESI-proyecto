<?php
require_once __DIR__ . '/../Config/database.php';

class UsuarioModelo {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

public function CrearUsuario(Usuario $usuario) {
    $sql = "INSERT INTO usuarios (rol_id, nombre, apellido, email, telefono, ci, password_hash, estado, fecha_registro) 
            VALUES (:rol_id, :nombre, :apellido, :email, :telefono, :ci, :password_hash, :estado, :fecha_registro)";
    
    $stmt = $this->db->prepare($sql);
    
    return $stmt->execute([
        ':rol_id' => $usuario->getRol(),
        ':nombre' => $usuario->getNombre(),
        ':apellido' => $usuario->getApellido(),
        ':email' => $usuario->getEmail(),
        ':telefono' => $usuario->getTelefono(),       
        ':ci' => $usuario->getCi(),                   
        ':password_hash' => $usuario->getPassword(),
        ':estado' => $usuario->getEstado(),
        ':fecha_registro' => $usuario->getFechaRegistro()
    ]);
}
}
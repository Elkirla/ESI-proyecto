<?php
require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Entidades/usuario.php'; 

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

    public function AsignarUnidad($usuario_id, $unidad_id) {
    $sql = "INSERT INTO usuarios_unidades (usuario_id, unidad_id) VALUES (:usuario_id, :unidad_id)";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':unidad_id' => $unidad_id
    ]);
}

    public function VerificarLogin($email, $password) {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        $sql = "SELECT u.id, u.email, u.password_hash, u.estado, r.nombre AS rol
                FROM usuarios u
                JOIN roles r ON u.rol_id = r.id
                WHERE u.email = :email
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            if (!password_verify($password, $usuario['password_hash'])) {
                return false;
            }

            if ($usuario['estado'] !== 'activo') {
                return 'inactivo';
            }

            return [
                'id' => $usuario['id'],
                'rol' => $usuario['rol']
            ];
        }

        return false;
    }
public function rechazarUsuario($usuario_id) {
    $sql = "UPDATE usuarios SET estado = 'rechazado' WHERE id = :usuario_id";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([':usuario_id' => $usuario_id]);
}


    public function aceptarUsuario($usuario_id) {
        $sql = "UPDATE usuarios SET estado = 'activo' WHERE id = :usuario_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':usuario_id' => $usuario_id]);
    }

public function actualizarDatos(Usuario $usuario) {
    $sql = "UPDATE usuarios 
            SET nombre = :nombre, apellido = :apellido, telefono = :telefono, ci = :ci
            WHERE id = :id";
    
    $stmt = $this->db->prepare($sql);
    
    return $stmt->execute([
        ':nombre' => $usuario->getNombre(),
        ':apellido' => $usuario->getApellido(),
        ':telefono' => $usuario->getTelefono(),
        ':ci' => $usuario->getCi(),
        ':id' => $usuario->getId()
    ]);
}

public function ExisteEmail($email) {
    $sql = "SELECT id FROM usuarios WHERE email = :email LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetch() !== false;
}

public function ExisteCI($ci) {
    $sql = "SELECT id FROM usuarios WHERE ci = :ci LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':ci', $ci);
    $stmt->execute();
    return $stmt->fetch() !== false;
}
public function ObtenerTodosUsuarios() {
    // Selecciona usuarios activos que no sean administradores
    $sql = "SELECT u.id, u.nombre, u.apellido, u.telefono, u.email
            FROM usuarios u
            JOIN roles r ON u.rol_id = r.id
            WHERE u.estado = 'activo' AND r.nombre != 'administrador'";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}

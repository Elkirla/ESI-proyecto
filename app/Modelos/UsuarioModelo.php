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

    public function obtenerDatosUsuario($userId) {
        $sql = "SELECT nombre, apellido, telefono, email, ci
                FROM usuarios 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $userId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $usuario = new Usuario();
        $usuario->setNombre($row['nombre']);
        $usuario->setApellido($row['apellido']);
        $usuario->setTelefono($row['telefono']);
        $usuario->setEmail($row['email']);
        $usuario->setCi($row['ci']);

        return $usuario;
    }
    public function obtenerUsuariosPendientes() { 
        $sql = "SELECT id, nombre, apellido 
                FROM usuarios 
                WHERE estado = 'pendiente'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(); 
        $usuarios = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuario = new Usuario();
            $usuario->setId($row['id']);
            $usuario->setNombre($row['nombre']);
            $usuario->setApellido($row['apellido']);
            $usuarios[] = $usuario;
        }

        return $usuarios;
    }
}

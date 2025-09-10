<?php
class usuario {
    private $rol;
    private $nombre;
    private $apellidos;
    private $email;
    private $password;
    private $estado;
    private $fecha_registro;

    public function __construct($rol, $nombre, $apellidos, $email, $password, $estado, $fecha_registro) {
        $this->rol = $rol;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->email = $email;
        $this->password = $password;
        $this->estado = $estado;
        $this->fecha_registro = $fecha_registro;
    }
    //getters y setters
    public function getRol() {
        return $this->rol;
    }
    public function setRol($rol) {
        $this->rol = $rol;
    }
    public function getNombre() {
        return $this->nombre;
    }
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    public function getApellidos() {
        return $this->apellidos;
    }
    public function setApellidos($apellidos) {
        $this->apellidos = $apellidos;
    }
    public function getEmail() {
        return $this->email;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    public function getPassword() {
        return $this->password;
    }
    public function setPassword($password) {
        $this->password = $password;
    }
    public function getEstado() {
        return $this->estado;
    }
    public function setEstado($estado) {
        $this->estado = $estado;
    }
    public function getFechaRegistro() {
        return $this->fecha_registro;
    }
    public function setFechaRegistro($fecha_registro) {
        $this->fecha_registro = $fecha_registro;
    }

}
?>
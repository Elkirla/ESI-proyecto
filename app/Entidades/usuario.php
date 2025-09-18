<?php
class Usuario {
    private $rol;
    private $nombre;
    private $apellido;
    private $email;
    private $telefono;  
    private $ci;          
    private $password;
    private $estado;
    private $fecha_registro;

    public function __construct() {}
 
    public function getTelefono() { return $this->telefono; }
    public function getCi() { return $this->ci; }
    public function getRol() { return $this->rol; }
    public function getNombre() { return $this->nombre; }
    public function getApellido() { return $this->apellido; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getEstado() { return $this->estado; }
    public function getFechaRegistro() { return $this->fecha_registro; }
 
    public function setTelefono($telefono) { $this->telefono = $telefono; }
    public function setCi($ci) { $this->ci = $ci; }
    public function setRol($rol) { $this->rol = $rol; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellido($apellido) { $this->apellido = $apellido; }
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = $password; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setFechaRegistro($fecha_registro) { $this->fecha_registro = $fecha_registro; }
}

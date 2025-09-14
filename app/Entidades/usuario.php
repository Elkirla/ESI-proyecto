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

    public function __construct($rol, $nombre, $apellido, $telefono, $ci, $email, $password, $estado, $fecha_registro) {
        $this->rol = $rol;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->telefono = $telefono;    
        $this->ci = $ci;                
        $this->email = $email;
        $this->password = $password;
        $this->estado = $estado;
        $this->fecha_registro = $fecha_registro;
    }

    // Asegúrate de tener estos getters
    public function getTelefono() { return $this->telefono; }
    public function getCi() { return $this->ci; }
    
    // ... los otros getters existentes
    public function getRol() { return $this->rol; }
    public function getNombre() { return $this->nombre; }
    public function getApellido() { return $this->apellido; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getEstado() { return $this->estado; }
    public function getFechaRegistro() { return $this->fecha_registro; }
}
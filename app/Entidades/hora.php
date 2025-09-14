<?php
class Hora {
    public $usuario_id;
    public $fecha;  
    public $horas;   

    public function __construct($usuario_id = null, $fecha = null, $horas = null) {
        $this->usuario_id = $usuario_id;
        $this->fecha = $fecha;
        $this->horas = $horas;
    }

    // Getters y Setters
    public function getUsuarioId() { return $this->usuario_id; }
    public function getFecha() { return $this->fecha; }
    public function getHoras() { return $this->horas; }
    public function setUsuarioId($usuario_id) { $this->usuario_id = $usuario_id; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    public function setHoras($horas) { $this->horas = $horas; }
}
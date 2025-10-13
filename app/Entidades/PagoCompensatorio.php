<?php
class PagoCompensatorio {
    public $id;
    public $usuario_id;
    public $monto;
    public $fecha;
    public $horas;
    public $archivo_url;
    public $estado;

public function __construct($usuario_id, $monto, $fecha, $horas, $id = null, $archivo_url = null, $estado = 'pendiente') {
    $this->id = $id;
    $this->usuario_id = $usuario_id;
    $this->monto = $monto;
    $this->fecha = $fecha;
    $this->horas = $horas;
    $this->archivo_url = $archivo_url;
    $this->estado = $estado;
}


}


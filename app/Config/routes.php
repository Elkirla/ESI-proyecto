<?php
$routes = [
    'GET' => [
        "/"              => "HomeControl@index",
        "/normas"        => "HomeControl@normas",
        "/exitoregistro" => "HomeControl@exitoregistro",
        "/login"         => "AuthControl@loginView",
        "/registro"      => "AuthControl@registroView",
        "/pagosusuario"  => "PagosControl@verPagosUsuario",
        "/pagosadmin"    => "PagosControl@verPagosAdmin",
        "/horasusuario"  => "HorasControl@verHorasUsuario",
        "/horasadmin"    => "HorasControl@verHorasAdmin",
    ],
    "POST" => [
        "/login"    => "AuthControl@login",
        "/registro" => "AuthControl@registrar",
        "/pago"    => "PagosControl@IngresarPago",
        "/horas"    => "HorasControl@IngresarHoras",
    ]
];
?>

<?php
$routes = [
    'GET' => [
        "/"              => "HomeControl@index",
        "/normas"        => "HomeControl@normas",
        "/exitoregistro" => "HomeControl@exitoregistro", 
        "/dashboard-admin" => "HomeControl@dashboardAdmin",
        "/dashboard-usuario" => "HomeControl@dashboardUsuario",
        "/login"         => "AuthControl@loginView",
        "/registro"      => "AuthControl@registroView",
        "/pagosusuario"  => "PagosControl@verPagosUsuario",
        "/pagosadmin"    => "PagosControl@verPagosAdmin",
        "/horasusuario"  => "HorasControl@verHorasUsuario",
        "/horasadmin"    => "HorasControl@verHorasAdmin",
        "/usuariodatos"   => "UserControl@cargarDatosUsuario",
        "/usuariospendientes" => "UserControl@cargarUsuariosPendientes",
    ],
    "POST" => [
        "/login"    => "AuthControl@login",
        "/registro" => "AuthControl@registrar",
        "/pago"     => "PagosControl@IngresarPago",
        "/horas"    => "HorasControl@IngresarHoras",
    ]
];
?>
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
        "/fecha-limite"  => "PagosControl@obtenerFechaLimite",
        "/pagosadmin"    => "PagosControl@verPagosAdmin",
        "/pagosusuario"  => "PagosControl@verPagosUsuario",
        "/horasusuario"  => "HorasControl@verHorasUsuario",
        "/horasadmin"    => "HorasControl@verHorasAdmin",
        "/usuariodatos"   => "UserControl@cargarDatosUsuario",
        "/usuariospendientes" => "UserControl@cargarUsuariosPendientes",
        "/usuario-por-id" => "UserControl@ObtenerUsuarioPorId",
        "/logout"        => "AuthControl@logout",
    ],
    "POST" => [
        "/login"    => "AuthControl@login",
        "/registro" => "AuthControl@registrar",
        "/pago"     => "PagosControl@IngresarPago",
        "/horas"    => "HorasControl@IngresarHoras",
        "/aprobar-pago" => "PagosControl@aprobarPago",
        "/rechazar-pago" => "PagosControl@rechazarPago", 
        "/cambiar-fecha-limite" => "PagosControl@cambiarFechaLimite",
        "/aprobar-usuario" => "UserControl@aprobarUsuario",
        "/rechazar-usuario" => "UserControl@rechazarUsuario",
    ]

];
?>
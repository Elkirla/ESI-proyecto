<?php
$routes = [
    'GET' => [

        // ======================
        // VISTAS PÚBLICAS
        // ======================
        "/"                 => "HomeControl@index",
        "/normas"           => "HomeControl@normas",
        "/exitoregistro"    => "HomeControl@exitoregistro",
        "/login"            => "AuthControl@loginView",
        "/registro"         => "AuthControl@registroView",

        // Dashboards
        "/dashboard-admin"  => "HomeControl@dashboardAdmin",
        "/dashboard-usuario"=> "HomeControl@dashboardUsuario",

        // ======================
        // PAGOS
        // ======================
        "/pagosusuario"     => "PagosControl@verPagosUsuario",
        "/pagosadmin"       => "PagosControl@verPagosAdmin",
        "/listar-pago-deudas" => "PagosControl@listarPagosDeudas",
        "/fecha-limite"     => "PagosControl@obtenerFechaLimite",

        // ======================
        // HORAS
        // ======================
        "/horasusuario"     => "HorasControl@verHorasUsuario",
        "/horasadmin"       => "HorasControl@verHorasAdmin",

        // ======================
        // JUSTIFICATIVOS
        // ======================
        "/listar-justificativos"        => "JustificativoControl@listarJustificativos",
        "/listar-justificativos-admin"  => "JustificativoControl@listarJustificativosAdmin",

        // ======================
        // USUARIOS
        // ======================
        "/usuariodatos"          => "UserControl@cargarDatosUsuario",
        "/usuariospendientes"    => "UserControl@cargarUsuariosPendientes",
        "/usuario-por-id"        => "UserControl@ObtenerUsuarioPorId",
        
        // ======================
        // NOTIFICACIONES
        // ======================
        "/listar-notificaciones" => "NotiControl@ObtenerNotificaciones"
        "/notis-no-leídas"       => "NotiControl@NotisNoLeidas",

        // ======================
        // SESIÓN
        // ======================
        "/logout"           => "AuthControl@logout",
    ],

    "POST" => [

        // ======================
        // AUTENTICACIÓN
        // ======================
        "/login"    => "AuthControl@login",
        "/registro" => "AuthControl@registrar",

        // ======================
        // PAGOS
        // ======================
        "/pago"             => "PagosControl@IngresarPago",
        "/aprobar-pago"     => "PagosControl@aprobarPago",
        "/rechazar-pago"    => "PagosControl@rechazarPago",
        "/cambiar-fecha-limite" => "PagosControl@cambiarFechaLimite",

        // ======================
        // HORAS
        // ======================
        "/horas" => "HorasControl@IngresarHoras",

        // ======================
        // JUSTIFICATIVOS
        // ======================
        "/IngresarJustificativo"    => "HorasControl@IngresarJustificativo",
        "/aceptar-justificativo"    => "JustificativoControl@aceptarJustificativo",
        "/rechazar-justificativo"   => "JustificativoControl@rechazarJustificativo",

        // ======================
        // USUARIOS
        // ======================
        "/aprobar-usuario"  => "UserControl@aprobarUsuario",
        "/rechazar-usuario" => "UserControl@rechazarUsuario",
                
        // ======================
        // NOTIFICACIONES
        // ======================
        "/marcar-todas-leidas" => "NotiControl@MarcarTodasLeidas",
        "/crear-notificacion"  => "NotiControl@CrearNotificacion",
    ]
];

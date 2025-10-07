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

        // DASHBOARDS
        "/dashboard-admin"  => "AdminControl@dashboardAdmin",
        "/dashboard-usuario"=> "HomeControl@dashboardUsuario",

        // ======================
        // PAGOS
        // ======================
        "/pagosusuario"        => "PagosControl@verPagosUsuario",
        "/pagosadmin"          => "PagosControl@verPagosAdmin",
        "/listar-pago-deudas"  => "PagosControl@listarPagosDeudas",
        "/fecha-limite"        => "PagosControl@obtenerFechaLimite",
        "/obtener-mensualidad"  => "PagosControl@obtenerMensualidad",

        // ======================
        // HORAS
        // ======================
        "/horasusuario"     => "HorasControl@verHorasUsuario",
        "/horasadmin"       => "HorasControl@verHorasAdmin",
        "/horas-semanales"  => "HorasControl@verHorasSemanales"

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
        "/listar-notificaciones" => "NotiControl@ObtenerNotificaciones",
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
        "/pago"                 => "PagosControl@IngresarPago",
        "/aprobar-pago"         => "AdminControl@aprobarPago",
        "/rechazar-pago"        => "AdminControl@rechazarPago",
        "/pago-compensatorio"   => "PagosControl@IngresarPagoCompensatorio",
        "/aprobar-pago-compensatorio"   => "AdminControl@aprobarPagoCompensatorio",
        "/rechazar-pago-compensatorio"  => "AdminControl@rechazarPagoCompensatorio",
        "/cambiar-fecha-limite" => "PagosControl@cambiarFechaLimite",

        // ======================
        // HORAS
        // ======================
        "/horas" => "HorasControl@IngresarHoras",

        // ======================
        // JUSTIFICATIVOS
        // ======================
        "/IngresarJustificativo"    => "HorasControl@IngresarJustificativo",
        "/aceptar-justificativo"    => "AdminControl@aceptarJustificativo",
        "/rechazar-justificativo"   => "AdminControl@rechazarJustificativo",

        // ======================
        // USUARIOS
        // ======================
        "/aprobar-usuario"  => "AdminControl@AceptarUsuario",
        "/rechazar-usuario" => "AdminControl@RechazarUsuario",
                
        // ======================
        // NOTIFICACIONES
        // ======================
        "/marcar-todas-leidas" => "NotiControl@MarcarTodasLeidas",
        "/crear-notificacion"  => "AdminControl@CrearNotificacion",
    ]
];

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

        // ======================
        // DASHBOARDS
        // ======================
        "/dashboard-admin"   => "AdminControl@dashboardAdmin",
        "/dashboard-usuario" => "HomeControl@dashboardUsuario",

        // ======================
        // PAGOS (usuario)
        // ======================
        "/pagosusuario"              => "PagosControl@verPagosUsuario",
        "/fecha-limite"              => "PagosControl@obtenerFechaLimite",
        "/obtener-mensualidad"       => "PagosControl@obtenerMensualidad",
        "/pagos-compensatorios"      => "PagosControl@verPagosCompensatorios",
        "/actualizarPagoDeudas"      => "PagosControl@ActualizarDeudaPago",
        "/verPagosDeuda"             => "PagosControl@verPagosDeuda",

        // ======================
        // PAGOS (admin)
        // ======================
        "/pagosadmin"                 => "AdminControl@verPagosAdmin",
        "/listar-pagodeudas"          => "AdminControl@listarPagosDeudas",
        "/pagos-compensatorios-admin" => "AdminControl@listarPagosCompensatorios",

        // ======================
        // HORAS
        // ======================
        "/horasusuario"                => "HorasControl@verHorasUsuario",
        "/horasadmin"                  => "AdminControl@verHorasAdmin",
        "/horas-semanales"             => "HorasControl@verHorasSemanales",
        "/valor-semanal"               => "HorasControl@verValorSemanal",

        // ======================
        // JUSTIFICATIVOS
        // ======================
        "/listar-justificativos"        => "JustificativoControl@listarJustificativos",
        "/listar-justificativos-admin"  => "AdminControl@listarJustificativosAdmin",

        // ======================
        // USUARIOS
        // ======================
        "/usuariodatos"        => "UserControl@cargarDatosUsuario",
        "/usuariospendientes"  => "AdminControl@cargarUsuariosPendientes",
        "/usuario-por-id"      => "AdminControl@ObtenerUsuarioPorId",
        
        // ======================
        // NOTIFICACIONES
        // ======================
        "/listar-notificaciones" => "NotiControl@ObtenerNotificaciones",
        "/notis-no-leídas"       => "NotiControl@NotisNoLeidas",

        // ======================
        // SESIÓN
        // ======================
        "/logout"=> "AuthControl@logout",
    ],

    "POST" => [

        // ======================
        // AUTENTICACIÓN
        // ======================
        "/login"    => "AuthControl@login",
        "/registro" => "AuthControl@registrar",

        // ======================
        // PAGOS (usuario)
        // ======================
        "/pago"                   => "PagosControl@IngresarPago",
        "/pago-compensatorio"     => "PagosControl@IngresarPagoCompensatorio",
        "/cambiar-fecha-limite"   => "PagosControl@cambiarFechaLimite",
        "/calcular-saldo-compensatorio"=> "HorasControl@calcularsaldoCompensatorio",
        "/ingresar-pago-compensatorio" => "PagoCompensatorioControl@IngresarPagoCompensatorio",
        "/ver-pagos-compensatorios"     => "PagoCompensatorioControl@verPagosCompensatorios",

        // ======================
        // PAGOS (admin)
        // ======================
        "/aprobar-pago"                => "AdminControl@aprobarPago",
        "/rechazar-pago"               => "AdminControl@rechazarPago",
        "/aprobar-pago-compensatorio"  => "AdminControl@aprobarPagoCompensatorio",
        "/rechazar-pago-compensatorio" => "AdminControl@rechazarPagoCompensatorio",

        // ======================
        // HORAS
        // ======================
        "/horas" => "HorasControl@IngresarHoras",

        // ======================
        // JUSTIFICATIVOS
        // ======================
        "/IngresarJustificativo"  => "HorasControl@IngresarJustificativo",
        "/aceptar-justificativo"  => "AdminControl@aceptarJustificativo",
        "/rechazar-justificativo" => "AdminControl@rechazarJustificativo",

        // ======================
        // USUARIOS (admin)
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

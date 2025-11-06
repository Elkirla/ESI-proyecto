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
        "/pagoInicial"      => "AuthControl@PagoInicialView",

        // ======================
        // DASHBOARDS
        // ======================
        "/dashboard-admin"   => "AuthControl@backoffice",
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
        "/VerEstadoPagos"            => "PagosControl@verEstadoPagos",
        "/verDeudasMensuales"        => "PagosControl@verMesesDeudaPagos",
        // ======================
        // PAGOS (admin)
        // ======================
        "/pagosadmin"                 => "AdminControl@verPagosAdmin",
        "/listar-pagodeudas"          => "AdminControl@listarPagosDeudas",
        "/pagos-compensatorios-admin" => "AdminControl@listarPagosCompensatorios",  
 
        // ======================
        // HORAS
        // ======================
        "/horasusuario"           => "HorasControl@verHorasUsuario",
        "/horasadmin"             => "AdminControl@verHorasAdmin",
        "/horas-semanales"        => "HorasControl@verHorasSemanales",
        "/valor-semanal"          => "HorasControl@verValorSemanal",
        "/horasDeudasTotal"       => "HorasControl@verDeudasHorasUsuario",
        "/horas-deuda-admin"      => "AdminControl@verDeudasHorasAdmin",
        "/VerHorasTrabajadas"     => "HorasControl@obtenerHorasTrabajadasSemana",
        "/verHorasDeudaSemanal"   => "HorasControl@verHorasDeudaSemanal",
        "/saldo-compensatorio"    => "HorasControl@SaldoCompensatorioUsuario",
        "/verTodasDeudasSemanas"  => "HorasControl@verTodasDeudasSemanasUsuario",

       "/test" => "HorasControl@actualizarDeudaHorasUsuario",

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
        // UNIDADES HABITACIONALES
        // ======================
        "/obtenerdatosunidad" => "UnidadControl@ObtenerDatosUnidad",
        
        // ======================
        // NOTIFICACIONES
        // ======================
        "/listarNotificaciones" => "NotiControl@ObtenerNotificaciones",
        "/notis-noleidas"       => "NotiControl@NotisNoLeidas",

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
        "/actualizarPagoDeudas"      => "PagosControl@ActualizarDeudaPago",

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
        "/actualizar-deuda-horas" => "HorasControl@actualizarDeudaHorasUsuario",

        // ======================
        // JUSTIFICATIVOS
        // ======================
        "/IngresarJustificativo"  => "JustificativoControl@IngresarJustificativo",
        "/aceptar-justificativo"  => "AdminControl@aceptarJustificativo",
        "/rechazar-justificativo" => "AdminControl@rechazarJustificativo",

        // ======================
        // USUARIOS (admin)
        // ======================
        "/aprobar-usuario"  => "AdminControl@AceptarUsuario",
        "/rechazar-usuario" => "AdminControl@RechazarUsuario",
        
        "/actualizar-DatosUsuario" => "UserControl@ActualizarDatosUsuario",

        // ======================
        // NOTIFICACIONES
        // ======================
        "/marcar-todas-leidas" => "NotiControl@MarcarTodasLeidas",
        "/crear-notificacion"  => "AdminControl@CrearNotificacion",
    ]
];

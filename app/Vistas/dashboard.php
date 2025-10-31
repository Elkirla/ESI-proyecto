<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/dashboard.css">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <link rel="icon" href="public/imagenes/logo.png" type="icon">
    <title>Página usuario</title>
</head>
<body>

<div class="notificaciónes-container">
    <div class="notificaciónes" id="notificaciónes">
        <h3>Notificaciones</h3>
        <p id="cerrar-notis">X</p>
        <ul id="lista-notificaciones">
        </ul>
    </div>
</div>

     <div class="heder"></div>
    <div class="sider">
        <div class="opcion-div"></div>
        <a href="/logout">Cerrar sesión</a>
        <h1>Cooperativa</h1>
        <button id="btn-inicio">Inicio</button>
        <button id="btn-mi-perfil">Mi perfil</button>
        <button id="btn-pagos">Pagos</button>
        <button id="btn-horas">Horas</button>
        <button id="btn-deudas">Deudas</button>
        <button id="btn-mensajes">Mensajes</button>
        <button id="btn-soporte">Soporte</button>
    </div>

<div class="info">

<div class="inicio section" id="inicio"> 
    <h2 id="nombre_usuario"></h2> 
    
    <div class="InfoInicio">
        <div class="info-item">
            <img src="public/imagenes/dinero.png" alt="dinero_icon">
            <h3>Pagos</h3>
            <h3 id="EstadoPagosID"></h3>
        </div>

        <div class="info-item">
            <img src="public/imagenes/reloj.png" alt="reloj_icon">
            <h3 id="HorasTrabajadasID">Horas</h3>  
        </div>

        <div class="info-item">
            <img src="public/imagenes/casa.png" alt="casa_icon">
            <h3 id="UnidadInicioID"></h3>
        </div>
    </div>
</div>
 
<div class="mi-perfil section">
    <div class="texto-icono">
        <img src="public/imagenes/usuario.png" alt="usuario_icon"> 
        <h2>MI PERFIL</h2>
    </div>

    <div class="datosUsuario-container">
        <div id="editar-datos">
            <button id="btn-editar-datos">
                <img src="public/imagenes/lapiz.png" alt="lapiz_icon">
                <p>Editar datos</p>
            </button>
        </div>
     
        <div id="vista-datos" class="datos">
            <p id="Nombre-datos"></p>
            <p id="Apellido-datos"></p>
            <p id="Telefono-datos"></p>
            <p id="ci-datos"></p>
        </div>
        
        <div class="formulario-editar-datos-container">
            <form id="form-editar-datos" class="datos" style="display:none;">
                <input type="text" id="input-nombre" placeholder="Nombre">
                <input type="text" id="input-apellido" placeholder="Apellido">
                <input type="text" id="input-telefono" placeholder="Teléfono">
                <input type="text" id="input-ci" placeholder="CI">
            
                <div class="form-buttons">
                    <button type="submit" id="btn-guardar">Guardar cambios</button>
                    <button type="button" id="btn-cancelar">Cancelar</button>
                </div>
            </form>
        </div>


    </div>

</div>

 
<div class="pagos section"> 
    <div class="PagosHeaderDiv">
        <h2 class="PagosTitulo">MIS PAGOS</h2>
        <h2 id="EstadoPagos-pagos"></h2>
    </div>

    <div class="PagosContentDiv">
         <h3 class="TextFechaLimite">
             Fecha límite: <span id="FechaLimite"></span>
         </h3>


<form class="filtropagos" onsubmit="return false;">
    <select id="filtro-pagos">
        <option value="todos">Mes</option>
        <option value="pendientes">Monto</option>
        <option value="aprobados">Envio</option>
        <option value="rechazados">Estado</option>
    </select>
    <input type="text">
    <button type="submit">Aplicar</button>
</form>


<div class="tablaPagos">
    <table>
        <tr>
            <th>Mes</th>
            <th>Monto</th>
            <th>Envio</th>
            <th>Estado</th>
        </tr> 
    </table>
</div>


       <div class="IngresarComprobanteDiv">
    <div class="botones-comp">
        <button class="active">Subir Comprobante</button>
        <button>Pago Compensatorio</button>
    </div>

    <div class="IngresarPago">  
        <div class="info-pago">
            <p id="MontoMensual">Monto:</p>
            <p id="mes">Mes:</p> 
        </div>
        <form action="" class="form-pago" id="form-pago" enctype="multipart/form-data"> 
            <input type="file" name="archivo" id="archivo" accept=".jpg,.jpeg,.png,.pdf">
            <button type="submit" id="btn-pagar" class="but">Subir comprobante</button>
        </form>
    </div>

    <div class="IngresarCompensatorio">
        <div class="info-pago">
            <p id="MontoCompensatorio">Monto semanal</p>
            <p id="HorasRestantes">Horas restantes</p>
            <p id="MontoTotal">Monto total</p> 
        </div>
        <form action="" class="form-compensatorio" id="form-compensatorio" enctype="multipart/form-data"> 
            <input type="file" name="archivo" id="archivo-compensatorio" accept=".jpg,.jpeg,.png,.pdf">
            <button type="submit" id="btn-compensatorio" class="but">Subir comprobante</button>
        </form>
    </div>
</div>

    </div> 
</div>
    <div class="horas section">
        <div>
        <h2 class="HorasTitulo">MIS HORAS</h2>
        </div>
 
        <div class="Envio-container">

        <button id="btnHoras">Ingresar horas</button>
        <button id="btnJustificativo">Enviar justificativo</button>

            <div class="Envio-horas" id="IngresoHorasDiv"> 
                <label for="hr" id="fecha-horas"></label>
                <input type="text" id="hr" placeholder="Horas trabajadas">
                <button id="subirhoras">Registrar</button>
            </div>

            <form id="formJustificativo" enctype="multipart/form-data" class="form-justificativo">
            
                <label for="motivo">Razón del justificativo</label>
                <textarea id="motivo" name="motivo" placeholder="Describa el motivo del justificativo." required></textarea>
            
                <label for="fecha">Fecha inicio</label>
                <input type="date" id="fecha" name="fecha" required>
            
                <label for="fecha_final">Fecha fin</label>
                <input type="date" id="fecha_final" name="fecha_final">
            
                <label for="archivo">Seleccionar archivo (PDF/Imagen)</label>
                <input type="file" id="archivo" name="archivo" accept="image/*,application/pdf" required>
            
                <button type="submit" id="btn-submit-justificativo">Enviar Justificativo</button>
            </form>

        </div>

        <div class="grafico-horas">
            <h3 id="Horas-semana-grafico">Horas trabajadas</h3>
        
            <div class="circle-chart">
                <svg width="150" height="150">
                    <circle cx="75" cy="75" r="60" class="bg-circle"></circle>
                    <circle cx="75" cy="75" r="60" class="progress-circle"></circle>
                </svg>
                <div class="circle-inner">
                    <span id="percentage-text">0%</span>
                    <small>Horas trabajadas esta semana</small>
                </div>
            </div>
        </div>

        <div class="tablas-HorasJustificativos">
            <h3>Registro de</h3> 
            <select id="filtro-horas">
                <option value="todos">Horas</option>
                <option value="pendientes">Justificativos</option>
            </select>

            <table class="tabla-horas">
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                </tr>
            </table>
            <table class="tabla-justificativos">
                <tr>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </table>
        </div> 
</div>

<div class="deudas section">
    <div>
        <h2>HORAS</h2>
            <table class="tabla-deudas-horas">
                <tr>
                    <th>Semana</th>
                    <th>Horas ingresadas</th>
                    <th>Horas faltantes</th>
                </tr>
            </table>
    </div>

    <div>
        <h2>PAGOS</h2>
            <table class="tabla-deudas-pagos">
                <tr>
                    <th>Mes</th>
                    <th>Pago esperado</th> 
                </tr>
            </table>
    </div>
    <div>
        <h2>DEUDA TOTAL</h2>
        <p id="MontoDeudaTotal"></p>
        <p id="CantidadMesesDeuda"></p>
    </div>
</div>

    <div class="mensajes section">
        <h2>Mensajes</h2>
        <div id="notificaciones-mensajes">  
        </div>
    </div>

    <div class="soporte section">
        <img src="public/imagenes/celular.png" alt="celular_icon">
        <h2>Soporte al usuario</h2>
        <h3>Si ha experimentado algún error o tiene alguna duda, consulte al personal por nuestros medios de comunicación </h3>
        <p>+099.123.456</p> 
        <p>Correo@gmail.com</p>
    </div>
</div>

<script src="public/js/dashboard.js"></script>
</body>
</html>
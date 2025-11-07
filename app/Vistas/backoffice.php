<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/backoffice.css">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <link rel="icon" href="public/imagenes/logo.png" type="icon">
    <title>BackOffice</title>
</head>
<body>

    <!-- NOTIFICACIONES -->
    <div class="notificaciones-container">
        <div class="notificaciones" id="notificaciones">
            <h3>Notificaciones</h3>
            <p id="cerrar-notis">X</p>
            <ul id="lista-notificaciones"></ul>
        </div>
    </div>

    <!-- HEADER -->
    <div class="heder"></div>

    <!-- SIDEBAR -->
    <div class="sider">
        <div class="opcion-div"></div>

        <a href="/logout">Cerrar sesión</a>
        <h1>Cooperativa</h1>

        <button id="btn-mi-perfil">Mi perfil</button>
        <button id="btn-Usuarios">Usuarios</button>
        <button id="btn-Pagos">Pagos</button>
        <button id="btn-ingresar">Ingresar</button>
        <button id="btn-Horas">Horas</button>
        <button id="btn-Unidades">Unidades</button>
        <button id="btn-Config">Config.</button>
    </div>

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="info">

        <!-- ========== MI PERFIL ========== -->
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
            <h3>Nombre</h3>
            <p id="Nombre-datos"></p>
            <h3>Apellido</h3>
            <p id="Apellido-datos"></p>
            <h3>Teléfono</h3>
            <p id="Telefono-datos"></p>
            <h3>CI</h3>
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

        <!-- ========== USUARIOS ========== -->
        <div class="usuarios section" style="display:none;">
            <h2>Usuarios</h2>
            <!-- Tabla de usuarios, búsqueda, filtros -->
        </div>

        <!-- ========== PAGOS ========== -->
        <div class="pagos section" style="display:none;">
            <h2>Pagos</h2>
            <!-- Gestión de pagos -->
        </div>

        <!-- ========== INGRESAR (ALTA DE USUARIO) ========== -->
        <div class="ingresar section" style="display:none;">
            <h2>Ingresar usuario</h2>
            <!-- Formulario para ingresar nuevos usuarios -->
        </div>

        <!-- ========== HORAS ========== -->
        <div class="horas section" style="display:none;">
            <h2>Horas</h2>
            <!-- Registro de horas -->
        </div>

        <!-- ========== UNIDADES ========== -->
        <div class="unidades section" style="display:none;">
            <h2>Unidades</h2>
            <!-- Gestión de unidades u hogares -->
        </div>

        <!-- ========== CONFIGURACIÓN ========== -->
        <div class="config section" style="display:none;">
            <h2>Configuración</h2>
            <!-- Preferencias, temas, permisos, etc -->
        </div>

    </div><!-- FIN INFO -->

    <script src="public/js/backoffice.js"></script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/backoffice.css">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
 
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
 
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <link rel="icon" href="public/imagenes/logo.png" type="icon">
    <title>BackOffice</title>
</head>

<body>
 
    <div class="notificaciones-container">
        <div class="notificaciones" id="notificaciones">
            <h3>Notificaciones</h3>
            <p id="cerrar-notis">X</p>
            <ul id="lista-notificaciones"></ul>
        </div>
    </div>
 
    <div class="heder"></div>
 
    <div class="sider">
        <div class="opcion-div"></div>

        <a href="/logout">Cerrar sesión</a>
        <h1>Backoffice</h1>

        <button id="btn-mi-perfil">Mi perfil</button>
        <button id="btn-ingresar">Ingresar</button>
        <button id="btn-Pagos">Pagos</button>
        <button id="btn-Usuarios">Usuarios</button>
        <button id="btn-Horas">Horas</button>
        <button id="btn-Unidades">Unidades</button>
        <button id="btn-Config">Config.</button>
    </div>
 
<div class="content">
 
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

        <div class="input-group">
            <input type="text" id="input-nombre" placeholder="Nombre">
            <small class="error-msg" id="error-nombre"></small>
        </div>

        <div class="input-group">
            <input type="text" id="input-apellido" placeholder="Apellido">
            <small class="error-msg" id="error-apellido"></small>
        </div>

        <div class="input-group">
            <input type="text" id="input-telefono" placeholder="Teléfono">
            <small class="error-msg" id="error-telefono"></small>
        </div>

        <div class="input-group">
            <input type="text" id="input-ci" placeholder="CI">
            <small class="error-msg" id="error-ci"></small>
        </div>

        <div class="form-buttons">
            <button type="submit" id="btn-guardar">Guardar cambios</button>
            <button type="button" id="btn-cancelar">Cancelar</button>
        </div>
    </form>
        </div>

    </div>

</div>
 
<div class="ingresar section" style="display:none;">
 
    <aside class="usuariosPendientes-div" id="usuariosPendientes-div">
        <h2>Usuarios pendientes</h2>  
    </aside>

    <button id="CerrarUsuariosPendientes"><</button>
 
    <section id="HojaRegistro">
 
        <div class="hoja">

            <h3>Solicitud de ingreso</h3>

            <form id="formRegistro">

                <div class="campo">
                    <label for="Nombre-Registro">Nombre</label>
                    <input type="text" id="Nombre-Registro" readonly>
                </div>

                <div class="campo">
                    <label for="Apellido-Registro">Apellido</label>
                    <input type="text" id="Apellido-Registro" readonly>
                </div>

                <div class="campo">
                    <label for="Telefono-Registro">Teléfono</label>
                    <input type="text" id="Telefono-Registro" readonly>
                </div>

                <div class="campo">
                    <label for="Correo-Registro">Correo</label>
                    <input type="email" id="Correo-Registro" readonly>
                </div>

                <div class="campo">
                    <label for="CI-Registro">Cédula</label>
                    <input type="text" id="CI-Registro" readonly>
                </div>

                <div class="acciones">
                    <button class="btn-aceptar" id="A-Registro">Aceptar</button>
                    <button class="btn-rechazar" id="R-Registro">Rechazar</button>
                </div>

            </form>

        </div>

    </section>
</div>


 
<div class="pagos section" style="display:none;">
    <h2>Gestión de Pagos</h2>

    <div class="cards-resumen">
        <div class="card-pago al-dia">
            <span class="numero" id="usuariosAlDia">0</span>
            <span class="desc">Usuarios al día</span>
        </div>
        <div class="card-pago atrasados">
            <span class="numero" id="usuariosAtrasados">0</span>
            <span class="desc">Usuarios atrasados</span>
        </div>
    </div>

    <table id="tabla-pagos" class="display">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Estado de pagos</th>
        </tr>
    </thead>
    <tbody> 
    </tbody>
</table> 

<div class="AdministrarPagos">
    <a href="/AdministrarPagos" target="_blank">
        <button class="btn-admin-pagos">Administrar pagos pendientes</button>
    </a>
</div>


</div>
 
<div class="usuarios section" style="display:none;">
    <h2>Gestión de Usuarios</h2>

    <!-- Navegación entre pestañas -->
    <div class="tabs">
        <button class="tab-button active" data-tab="listar">Lista de Usuarios</button>
        <button class="tab-button" data-tab="crear">Crear Usuario</button>
        <button class="tab-button" data-tab="modificar">Modificar Usuario</button>
        <button class="tab-button" data-tab="eliminar">Eliminar Usuario</button>
    </div>

    <!-- Contenido de pestañas -->
    <div class="tab-content">
        <!-- TAB LISTAR USUARIOS -->
        <div id="tab-listar" class="tab active">
            <table id="tablaUsuarios" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Unidad</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>CI</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- DataTables insertará aquí los datos -->
                </tbody>
            </table>
        </div>

        <!-- TAB CREAR USUARIO -->
        <div id="tab-crear" class="tab">
            <form id="formCrearUsuario" class="form-usuarios">
                <h3>Crear Nuevo Usuario</h3>
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" required>
                </div>
                <div class="form-group">
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" required>
                </div>
                <div class="form-group">
                    <label for="ci">Cédula:</label>
                    <input type="text" id="ci" name="ci" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-success">Crear Usuario</button>
            </form>
        </div>

        <!-- TAB MODIFICAR USUARIO -->
        <div id="tab-modificar" class="tab">
            <form id="formModificarUsuario" class="form-usuarios">
                <h3>Modificar Usuario</h3>
                <p>Selecciona un usuario de la lista para cargar sus datos.</p>
                <div class="form-group">
                    <label for="mod-nombre">Nombre:</label>
                    <input type="text" id="mod-nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="mod-apellido">Apellido:</label>
                    <input type="text" id="mod-apellido" name="apellido" required>
                </div>
                <div class="form-group">
                    <label for="mod-correo">Correo:</label>
                    <input type="email" id="mod-correo" name="correo" required>
                </div>
                <div class="form-group">
                    <label for="mod-telefono">Teléfono:</label>
                    <input type="text" id="mod-telefono" name="telefono" required>
                </div>
                <div class="form-group">
                    <label for="mod-ci">Cédula:</label>
                    <input type="text" id="mod-ci" name="ci" required>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>

        <!-- TAB ELIMINAR USUARIO -->
        <div id="tab-eliminar" class="tab">
            <form id="formEliminarUsuario" class="form-usuarios">
                <h3>Eliminar Usuario</h3>
                <p>Selecciona un usuario de la lista para eliminarlo.</p>
                <div class="form-group">
                    <label for="elim-nombre">Nombre:</label>
                    <input type="text" id="elim-nombre" name="nombre" readonly>
                </div>
                <div class="form-group">
                    <label for="elim-apellido">Apellido:</label>
                    <input type="text" id="elim-apellido" name="apellido" readonly>
                </div>
                <div class="form-group">
                    <label for="elim-correo">Correo:</label>
                    <input type="email" id="elim-correo" name="correo" readonly>
                </div>
                <div class="form-group">
                    <label for="elim-ci">Cédula:</label>
                    <input type="text" id="elim-ci" name="ci" readonly>
                </div>
                <button type="submit" class="btn btn-danger">Eliminar Usuario</button>
            </form>
        </div>
    </div>
</div>

 
        <div class="horas section" style="display:none;">
            <h2>Horas</h2> 
        </div>
 
        <div class="unidades section" style="display:none;">
            <h2>Unidades</h2> 
        </div>
 
        <div class="config section" style="display:none;">
            <h2>Configuración</h2> 
        </div>

    </div> 

    <script src="public/js/backoffice.js"></script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/backoffice.css">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
 
    <script src="js/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <script src="js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="css/sweetalert2.min.css">
    <script src="js/sweetalert2.min.js"></script>

    <link rel="icon" href="imagenes/logo.png" type="image/png">
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
        <img src="imagenes/usuario.png" alt="usuario_icon"> 
        <h2>MI PERFIL</h2>
    </div>

    <div class="datosUsuario-container">
        <div id="editar-datos">
            <button id="btn-editar-datos">
                <img src="imagenes/lapiz.png" alt="lapiz_icon">
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
        <tbody></tbody>
    </table> 

    <div class="AdministrarPagos">
        <a href="/AdministrarPagos" target="_blank">
            <button class="btn-admin-pagos">Administrar pagos pendientes</button>
        </a>
    </div>

</div>
 
<div class="usuarios section" style="display:none;">
    <h2>Gestión de Usuarios</h2>

    <div class="tabs">
        <button class="tab-button active" data-tab="listar">Lista de Usuarios</button>
        <button class="tab-button" data-tab="crear">Crear Usuario</button>
        <button class="tab-button" data-tab="modificar">Modificar Usuario</button>
        <button class="tab-button" data-tab="eliminar">Eliminar Usuario</button>
    </div>

    <div class="tab-content">

        <div id="tab-listar" class="tab active">
            <table id="tablaUsuarios" class="display">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Unidad</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>CI</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div id="tab-crear" class="tab">
            <form id="formCrearUsuario" class="form-usuarios">
                <h3>Crear Nuevo Usuario</h3>

                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" maxlength="50" required>
                    <span id="error-nombre" class="error-msg"></span>
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" maxlength="50" required>
                    <span id="error-apellido" class="error-msg"></span>
                </div>

                <div class="form-group">
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="email" required>
                    <span id="error-email" class="error-msg"></span>
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono:</label>

                    <select id="pais" name="pais" class="select-pais" required>
                        <option value="+598" selected>🇺🇾 +598</option>
                        <option value="+54">🇦🇷 +54</option>
                        <option value="+55">🇧🇷 +55</option>
                        <option value="+56">🇨🇱 +56</option>
                        <option value="+57">🇨🇴 +57</option>
                        <option value="+34">🇪🇸 +34</option>
                        <option value="+1">🇺🇸 +1</option>
                        <option value="+52">🇲🇽 +52</option>
                    </select>

                    <input type="text" id="telefono" name="telefono" maxlength="8" required>
                    <span id="error-telefono" class="error-msg"></span>
                </div>

                <div class="form-group">
                    <label for="ci">Cédula:</label>
                    <input type="text" id="ci" name="ci" required>
                    <span id="error-ci" class="error-msg"></span>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                    <span id="error-password" class="error-msg"></span>
                </div>

                <button type="submit" class="btn btn-success">Crear Usuario</button>
            </form>
        </div>

        <div id="tab-modificar" class="tab">
            <form id="formModificarUsuario" class="form-usuarios">
                <h3>Modificar Usuario</h3>
                <p>Selecciona un usuario de la lista para cargar sus datos.</p>
        
                <input type="hidden" id="mod-id" name="id">
        
                <div class="form-group">
                    <label for="mod-nombre">Nombre:</label>
                    <input type="text" id="mod-nombre" name="nombre" required>
                    <span id="mod-error-nombre" class="error-msg"></span>
                </div>
        
                <div class="form-group">
                    <label for="mod-apellido">Apellido:</label>
                    <input type="text" id="mod-apellido" name="apellido" required>
                    <span id="mod-error-apellido" class="error-msg"></span>
                </div>
        
                <div class="form-group">
                    <label for="mod-telefono">Teléfono:</label>
                    <input type="text" id="mod-telefono" name="telefono" required>
                    <span id="mod-error-telefono" class="error-msg"></span>
                </div>
        
                <div class="form-group">
                    <label for="mod-ci">Cédula:</label>
                    <input type="text" id="mod-ci" name="ci" required>
                    <span id="mod-error-ci" class="error-msg"></span>
                </div>
        
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>

        <div id="tab-eliminar" class="tab">
            <form id="formEliminarUsuario" class="form-usuarios">
                <h3>Eliminar Usuario</h3>
                <p>Selecciona un usuario de la lista para eliminarlo.</p>

                <input type="hidden" id="elim-id" name="id">

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

    <div class="horas-header">
        <h2>Horas trabajadas</h2>

        <div class="semana-rango">
            <span id="semanaInicio"></span> 
            <span> / </span>
            <span id="semanaFin"></span>
        </div>

        <button id="btnActualizarHoras" class="btn-actualizar">
            Actualizar
            <img src="imagenes/reload.png" alt="reload_icon">
        </button>
    </div>

    <table id="tablaHoras" class="display tabla-horas">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Horas trabajadas</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

</div>

<div class="unidades section" style="display:none;">
    <h2>Gestión de Unidades Habitacionales</h2>

    <div class="tabs">
        <button class="tab-button active" data-tab="listar-unidades">Lista de Unidades</button>
        <button class="tab-button" data-tab="crear-unidad">Crear Unidad</button>
        <button class="tab-button" data-tab="modificar-unidad">Modificar Unidad</button>
        <button class="tab-button" data-tab="eliminar-unidad">Eliminar Unidad</button>
    </div>

    <div class="tab-content">
                
        <div id="tab-listar-unidades" class="tab active">
            <table id="tablaUnidades" class="display">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Estado</th>  
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div id="tab-crear-unidad" class="tab">
            <form id="formCrearUnidad" class="form-unidades">
                <h3>Crear Nueva Unidad Habitacional</h3>

                <div class="form-group">
                    <label for="codigo-unidad">Código de Unidad:</label>
                    <input type="text" id="codigo-unidad" name="codigoUnidad" maxlength="20" placeholder="Ej: A-101, Casa-5" required>
                    <span id="error-codigo-unidad" class="error-msg"></span>
                </div>

                <div class="form-group">
                    <label for="estado-unidad">Estado:</label>
                    <select id="estado-unidad" name="estadoUnidad" required>
                        <option value="">-- Seleccionar estado --</option>
                        <option value="Cimientos">Cimientos</option>
                        <option value="Estructura">Estructura</option>
                        <option value="Terminaciones">Terminaciones</option>
                        <option value="Finalizada">Finalizada</option>
                    </select>
                    <span id="error-estado-unidad" class="error-msg"></span>
                </div>

                <button type="submit" class="btn btn-success">Crear Unidad</button>
            </form>
        </div>

        <div id="tab-modificar-unidad" class="tab">
            <form id="formModificarUnidad" class="form-unidades">
                <h3>Modificar Unidad Habitacional</h3>
                <p>Selecciona una unidad de la lista para cargar sus datos.</p>

                <input type="hidden" id="mod-unidad-id" name="idUnidad">

                <div class="form-group">
                    <label for="mod-codigo-unidad">Código de Unidad:</label>
                    <input type="text" id="mod-codigo-unidad" name="codigoUnidad" readonly>
                    <span id="mod-error-codigo-unidad" class="error-msg"></span>
                </div>

                <div class="form-group">
                    <label for="mod-estado-unidad">Estado:</label>
                    <select id="mod-estado-unidad" name="nuevoEstado" required>
                        <option value="">-- Seleccionar estado --</option>
                        <option value="Cimientos">Cimientos</option>
                        <option value="Estructura">Estructura</option>
                        <option value="Terminaciones">Terminaciones</option>
                        <option value="Finalizada">Finalizada</option>
                    </select>
                    <span id="mod-error-estado-unidad" class="error-msg"></span>
                </div>

                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>

        <div id="tab-eliminar-unidad" class="tab">
            <form id="formEliminarUnidad" class="form-unidades">
                <h3>Eliminar Unidad Habitacional</h3>
                <p>Selecciona una unidad de la lista para eliminarla.</p>

                <input type="hidden" id="elim-unidad-id" name="idUnidad">

                <div class="form-group">
                    <label for="elim-codigo-unidad">Código de Unidad:</label>
                    <input type="text" id="elim-codigo-unidad" name="codigoUnidad" readonly>
                </div>

                <div class="form-group">
                    <label for="elim-estado-unidad">Estado:</label>
                    <input type="text" id="elim-estado-unidad" name="estado" readonly>
                </div> 

                <button type="submit" class="btn btn-danger">Eliminar Unidad</button>
            </form>
        </div>

    </div>
</div>
 
<div class="config section" style="display:none;">
    <div class="ConfigHeader">
        <h2>Configuración de la Cooperativa</h2>
    
        <div class="config-nav">
            <button class="btn-config active" data-target="config-form-section">
                Editar Configuración
            </button>
            <button class="btn-config" data-target="user-data-section">
                Datos del Usuario
            </button>
        </div>
    </div>
 
    <div class="config-content">
        <section id="config-form-section" class="config-section active">
            <h3>Valores Actuales</h3>

            <form id="form-configuracion" class="form-config">
                <div class="form-group">
                    <label>Fecha límite de pago</label>
                    <input type="number" name="fecha_limite_pago" min="1" max="31">
                </div>

                <div class="form-group">
                    <label>Mensualidad</label>
                    <input type="number" name="mensualidad" min="0">
                </div>

                <div class="form-group">
                    <label>Horas semanales requeridas</label>
                    <input type="number" name="horas_semanales" min="1">
                </div>

                <div class="form-group">
                    <label>Valor semanal</label>
                    <input type="number" name="valor_semanal" min="0">
                </div>

                <div class="form-group">
                    <label>Cuota semanal</label>
                    <input type="number" name="cuota_semanal" min="0">
                </div>

                <button type="submit" class="btn-guardar-config">
                    Guardar Configuración
                </button>
            </form>
        </section>

        <section id="user-data-section" class="config-section">
            <h3>Datos del Usuario</h3>

            <form class="user-search">
                <input type="text" id="buscadorUsuario" placeholder="Buscar usuario por CI o correo...">
                <button id="btnBuscarUsuario">Buscar</button>
            </form> 

            <div class="tabs-user-data">
                <button class="tab-btn active" data-tab="tab-pagos-mensuales">Pagos Mensuales</button>
                <button class="tab-btn" data-tab="tab-horas">Horas Trabajadas</button>
                <button class="tab-btn" data-tab="tab-deudas-mensuales">Deudas Mensuales</button>
                <button class="tab-btn" data-tab="tab-deudas-semanales">Deudas Semanales</button>
            </div>

            <div id="tab-pagos-mensuales" class="tab-content active">
                <table id="tablaPagosMensuales" class="display datatable">
                    <thead>
                        <tr>
                            <th>Mes</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div id="tab-horas" class="tab-content">
                <table id="tablaHorasUsuario" class="display datatable">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Horas</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div id="tab-deudas-mensuales" class="tab-content">
                <table id="tablaDeudasMensuales" class="display datatable">
                    <thead>
                        <tr>
                            <th>Mes</th>
                            <th>Monto</th>
                            <th>Adeudado</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div id="tab-deudas-semanales" class="tab-content">
                <table id="tablaDeudasSemanales" class="display datatable">
                    <thead>
                        <tr>
                            <th>Semana</th>
                            <th>Horas trabajadas</th>
                            <th>Horas faltantes</th>
                            <th>Horas justificadas</th>
                            <th>Horas compensadas</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </section>

    </div>
</div>

<script src="js/sweetalert2.min.js"></script>
<script src="js/backoffice.js"></script>

</body>
</html>
